<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Configuration;
use App\Customer;
use App\CustomerUser;
use App\Address;
use App\Country;
use App\State;

use Excel;

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
/*
		$country = $this->country->findOrFail($id);
		
		return view('countries.edit', compact('country'));

        $customer_orders = $this->customerOrder
                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->orderBy('id', 'desc')->get();

        return view('customer_orders.index', compact('customer_orders'));
*/        
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
                'extension' => 'in:csv,xlsx,xls,ods', // all working except for ods
        ];

        $this->validate($request->merge( $extra_data ), $rules);

/*
        $data_file = $request->file('data_file');

        $data_file_full = $request->file('data_file')->getRealPath();   // /tmp/phpNJt6Fl

        $ext    = $data_file->getClientOriginalExtension();
*/

/*
        abi_r($data_file);
        abi_r($data_file_full);
        abi_r($ext, true);
*/

/*
        \Validator::make(
            [
                'document' => $data_file,
                'format'   => $ext
            ],[
                'document' => 'required',
                'format'   => 'in:csv,xlsx,xls,ods' // all working except for ods
            ]
        )->passOrDie();
*/

        // Avaiable fields
        // https://www.youtube.com/watch?v=STJV2hTO1Zs&t=4s
        // $columns = \DB::getSchemaBuilder()->getColumnListing( self::$table );

//        abi_r($columns);


        

        // Start Logger
        $logger = \App\ActivityLogger::setup( 'Import Customer Users', __METHOD__ )
                    ->backTo( route('customerusers.import') );        // 'Import Customer Users :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Usuarios CC desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $truncate = $request->input('truncate', 0);
        $params = ['simulate' => $request->input('simulate', 0)];

        // Truncate table
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


//        abi_r('Se han cargado: '.$i.' productos');



        // See: https://www.google.com/search?client=ubuntu&channel=fs&q=laravel-excel+%22Serialization+of+%27Illuminate%5CHttp%5CUploadedFile%27+is+not+allowed%22&ie=utf-8&oe=utf-8
        // https://laracasts.com/discuss/channels/laravel/serialization-of-illuminatehttpuploadedfile-is-not-allowed-on-queue

        // See: https://github.com/LaravelDaily/Laravel-Import-CSV-Demo/blob/master/app/Http/Controllers/ImportController.php
        // https://www.youtube.com/watch?v=STJV2hTO1Zs&t=4s
/*
        Excel::filter('chunk')->load('file.csv')->chunk(250, function($results)
        {
                foreach($results as $row)
                {
                    // do stuff
                }
        });

        Excel::filter('chunk')->load(database_path('seeds/csv/users.csv'))->chunk(250, function($results) {
            foreach ($results as $row) {
                $user = User::create([
                    'username' => $row->username,
                    // other fields
                ]);
            }
        });
*/
        // See: https://www.youtube.com/watch?v=z_AhZ2j5sI8  Modificar datos importados
    }


    /**
     * Process a file of the resource.
     *
     * @return 
     */
    protected function processFile( $file, $logger, $params = [] )
    {

        // 
        // See: https://www.youtube.com/watch?v=rWjj9Slg1og
        // https://laratutorials.wordpress.com/2017/10/03/how-to-import-excel-file-in-laravel-5-and-insert-the-data-in-the-database-laravel-tutorials/
        Excel::filter('chunk')->selectSheetsByIndex(0)->load( $file )->chunk(250, function ($reader) use ( $logger, $params )
        {
            
 /*           $reader->each(function ($sheet){
                // ::firstOrCreate($sheet->toArray);
                abi_r($sheet);
            });

            $reader->each(function($sheet) {
                // Loop through all rows
                $sheet->each(function($row) {
                    // Loop through all columns
                });
            });
*/

// Process reader STARTS

            if ( $params['simulate'] > 0 ) 
                $logger->log("WARNING", "Modo SIMULACION. Se mostrarán errores, pero no se cargará nada en la base de datos.");

            $i = 0;
            $i_ok = 0;
            $max_id = 2000;


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
                    $data['CUSTOMER_REFERENCE_EXTERNAL'] = $data['customer_reference_external'];

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
                        $customer = $this->customer->where( 'id', $data['id'] )
                                                    ->with('addresses')
                                                    ->first();
                    }

                    if ( !$customer )
                    {
                        $logger->log("ERROR", "Ususario ".$item.":<br />" . "El Cliente NO existe. ".$data['id'].' / '.$data['CUSTOMER_REFERENCE_EXTERNAL']);

                        continue;
                    }

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

// Process reader          
    
        }, false);      // should not queue $shouldQueue

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
        $headers = [ 'id', 'name', 'email', 'password', 'firstname', 'lastname', 'active',
                    'enable_quotations', 'enable_min_order', 'use_default_min_order_value', 'min_order_value', 'display_prices_tax_inc', 
                    'language_id', 'LANGUAGE_NAME', 'customer_id', 'CUSTOMER_REFERENCE_EXTERNAL', 'CUSTOMER_NAME_COMMERCIAL', 'address_id', 'ADDRESS_NAME_COMMERCIAL'
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
            $row['CUSTOMER_REFERENCE_EXTERNAL'] = $customeruser->customer      ? $customeruser->customer->reference_external : '';
            $row['CUSTOMER_NAME_COMMERCIAL']  = $customeruser->customer  ? $customeruser->customer->name_commercial : '';
            $row['ADDRESS_NAME_COMMERCIAL']   = $customeruser->address ? $customeruser->address->name_commercial : '';

            $data[] = $row;
        }

        $sheetName = 'Customer Users' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Customer_Users', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');           // ->export('pdf');  <= Does not work. See: https://laracasts.com/discuss/channels/general-discussion/dompdf-07-on-maatwebsiteexcel-autoloading-issue

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }
}