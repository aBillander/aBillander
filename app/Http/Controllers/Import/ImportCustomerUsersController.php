<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerUser;
use App\Models\State;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;

class ImportCustomerUsersController extends Controller
{
/*
   use BillableControllerTrait;

   protected $customer, $customerOrder, $customerOrderLine;

   public function __construct(Customer $customer, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine)
   {
        $this->customer = $customer;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
   }
*/
    public static $table = 'customer_users';

    public static $column_mask;		// Column fields (?)

//    public $entities = array();	// This controller is for ONE entity

    public $available_fields = array();

    public $required_fields = array();

//    public $cache_image_deleted = array();

    public static $default_values = array();
/*
    public static $validators = [];
*/
    public $separator = ';';

    public $multiple_value_separator = ',';


// //////////////////////////////////////////////////// //   

   protected $customeruser;
   protected $customer;
   protected $address;

   public function __construct(CustomerUser $customeruser, Customer $customer, Address $address)
   {
        $this->customeruser = $customeruser;
        $this->customer = $customer;
        $this->address  = $address;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import()
    {
        return view('imports.customer_users');
    }

    public function process(Request $request)
    {
        $extra_data = [
            'extension' => ( $request->file('data_file') ? 
                             $request->file('data_file')->getClientOriginalExtension() : 
                             null 
                            ),
        ];

        $rules = [
                'data_file' => 'required | max:8000',
                'extension' => 'in:csv,xlsx,xls,ods',
        ];

        $this->validate($request->merge( $extra_data ), $rules);


        // Start Logger
        $logger = ActivityLogger::setup( 'Import Customer Users', __METHOD__ )
                    ->backTo( route('customerusers.import') );


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Usuarios CC desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $truncate = $request->input('truncate', 0);
        $params = ['simulate' => $request->input('simulate', 0)];

        // Truncate table
        if ( ! $params['simulate'] )
        if ( $truncate > 0 ) {

            $nbr = CustomerUser::count();
            
            CustomerUser::truncate();

            $logger->log("INFO", "Se han borrado todos los Usuarios CC antes de la Importación. En total {nbr} Usuarios CC.", ['nbr' => $nbr]);
        }


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado los Usuarios CC desde el Fichero: <strong>:file</strong> .', ['file' => $file]));
    }


    /**
     * Process a file of the resource.
     *
     * @return 
     */
    protected function processFile( $file, $logger, $params = [] )
    {
        // Get data as an array
        $worksheet = Excel::toCollection(new ArrayImport, $file);

        // abi_r($worksheet->first(), true);

        $reader = $worksheet->first();    // First sheet in worksheet


// Process reader STARTS

            if ( $params['simulate'] > 0 ) 
                $logger->log("WARNING", "Modo SIMULACION. Se mostrarán errores, pero no se cargará nada en la base de datos.");

            $i = 0;
            $i_ok = 0;
            $max_id = 1000;


            if(!empty($reader) && $reader->count()) {

                
                // CustomerUser::unguard();        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    // abi_r($data);die();

                    $item = '[<span class="log-showoff-format">'.$data['email'].'</span>] <span class="log-showoff-format">'.$data['customer_id'].'</span>';

                    // Some Poor Man checks:
//                    $data['CUSTOMER_REFERENCE_EXTERNAL'] = $data['customer_reference_external'];

                    // Some fields
                    $data['name'] = '';
                    $data['min_order_value'] = (float) $data['min_order_value'] > 0 ? (float) $data['min_order_value'] : 0.0;
                    $data['display_prices_tax_inc'] = (int) $data['display_prices_tax_inc'] > 0 ? 1 : 0;

                    // Customer
                    if ( array_key_exists('CUSTOMER_REFERENCE_EXTERNAL', $data ) )
                    {
                        $customer = $this->customer->where( 'reference_external', $data['CUSTOMER_REFERENCE_EXTERNAL'] )
                                                    ->with('addresses')
                                                    ->first();
                    } else {
                        $customer = $this->customer->where( 'id', $data['customer_id'] ?? 0 )
                                                    ->with('addresses')
                                                    ->first();
                    }

                    if ( !$customer )
                    {
                        $logger->log("ERROR", "Usuario ".$item.":<br />" . "El Cliente NO existe. ".$data['id'].' / '.$data['CUSTOMER_REFERENCE_EXTERNAL']);

                        continue;
                    }

                    $data['customer_id'] = $customer->id;

                    // Address
                    if (  $data['address_id'] )
                    {
                        $address = $customer->addresses->firstWhere('id', $data['address_id']);

                        $logger->log("ERROR", "Ususario ".$item.":<br />" . "La Dirección NO existe. ".$data['id'].' / '.$data['CUSTOMER_REFERENCE_EXTERNAL'].' / '.$data['address_id'].' / ');

                        continue;
                    }

                    // Customer User
                    if ( $this->customeruser->where('email', $data['email'])->exists() )
                    {
                        if ( !$data['password'] )
                            unset( $data['password'] );
                        else
                            $data['password'] = \Hash::make( $data['password'] );

                    } else {

                        if ( !$data['password'] )
                            $data['password'] = Configuration::get('ABCC_DEFAULT_PASSWORD');

                        $data['password'] = \Hash::make( $data['password'] );
                    }

                    \DB::beginTransaction();
                    try {
                        if ( !($params['simulate'] > 0) ) 
                        {
                            $cuser = $this->customeruser->updateOrCreate( [ 
                                 'email' => $data['email'] 
                            ], $data );
                        }

                        $i_ok++;

                    }
                    catch(\Exception $e) {

                            \DB::rollback();

                            $logger->log("ERROR", "Se ha producido un error al procesar el Ususario ".$item.":<br />" . $e->getMessage());

                    }

                    // If we reach here, then
                    // data is valid and working.
                    // Commit the queries!
                    \DB::commit();

                    $i++;

                }   // End foreach


                // CustomerUser::reguard();


            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Usuarios CC en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Usuarios CC.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Usuarios CC.', ['i' => $i]);

// Process reader ENDS

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export()
    {
        
        $customerusers = $this->customeruser
                        ->with('customer')
                        ->with('address')
                        ->with('language')
                        ->with('address.country')
                        ->with('address.state')
//                        ->with('currency');
                        ->orderBy('id', 'asc')
                        ->get();


        // Initialize the array which will be passed into the Excel generator.
        $data = [];  

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'name', 'email', 'password', 'firstname', 'lastname', 'active', 'is_principal', 
                    'enable_quotations', 'enable_min_order', 'use_default_min_order_value', 'min_order_value', 'display_prices_tax_inc', 
                    'language_id', 'LANGUAGE_NAME', 'customer_id', 'CUSTOMER_NAME_COMMERCIAL', 'address_id', 'ADDRESS_NAME_COMMERCIAL'
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($customerusers as $customeruser) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $customeruser->{$header} ?? ( $customeruser->address->{$header} ?? '');
            }
            $row['min_order_value'] = $row['min_order_value'] * 1.0;

            $row['LANGUAGE_NAME']             = $customeruser->language  ? $customeruser->language->name : '';
//            $row['CUSTOMER_REFERENCE_EXTERNAL'] = $customeruser->customer      ? $customeruser->customer->reference_external : '';
            $row['CUSTOMER_NAME_COMMERCIAL']  = $customeruser->customer  ? $customeruser->customer->name_commercial : '';
            $row['ADDRESS_NAME_COMMERCIAL']   = $customeruser->address ? $customeruser->address->name_commercial : '';

            $data[] = $row;
        }

        $styles = [];

        $sheetTitle = 'Customer Users';

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}