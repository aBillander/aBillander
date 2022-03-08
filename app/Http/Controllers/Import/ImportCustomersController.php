<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\PaymentMethod;
use App\Models\PriceList;
use App\Models\ShippingMethod;
use App\Models\State;
use Excel;
use Illuminate\Http\Request;

class ImportCustomersController extends Controller
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
    public static $table = 'customers';

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

   protected $customer;
   protected $address;

   public function __construct(Customer $customer, Address $address)
   {
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
        return view('imports.customers');
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
        $logger = ActivityLogger::setup( 'Import Customers', __METHOD__ )
                    ->backTo( route('customers.import') );        // 'Import Customers :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Clientes desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $truncate = $request->input('truncate', 0);
        $params = ['simulate' => $request->input('simulate', 0)];

        // Truncate table
        if ( $truncate > 0 ) {

            $nbr = Customer::count();
            
            Customer::truncate();

            // SELECT * FROM `addresses` where `addressable_type`='App\\Customer'

            // Soft-deleting...We dont want thies here!
            // $this->address->where('addressable_type', 'App\\Customer')->delete();

            // $collection = Address::where('addressable_type', "App\\Customer")->get(['id']);
            // Address::destroy($collection->toArray());

            \DB::table('addresses')->where('addressable_type', "App\\Customer")->delete();

            // Note: This solution is for resetting the auto_increment of the table without truncating the table itself
            $max = \DB::table('addresses')->max('id') + 1; 
            \DB::statement("ALTER TABLE addresses AUTO_INCREMENT = $max");

            $logger->log("INFO", "Se han borrado todos los Clientes antes de la Importación. En total {nbr} Clientes.", ['nbr' => $nbr]);
        }


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado los Clientes desde el Fichero: <strong>:file</strong> .', ['file' => $file]));


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
            //
            // Remember: spread sheet .xlsx format only!!
            //
            
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
            $i_created = 0;
            $i_updated = 0;
            $i_ok = 0;
            $max_id = 2000;

            // Custom
            $state_names = [];
            if (file_exists(__DIR__.'/FSOL_provincias.php')) {
                $state_names = include __DIR__.'/FSOL_provincias.php';
            }


            if(!empty($reader) && $reader->count()) {

                
                // Customer::unguard();        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    $item = '[<span class="log-showoff-format">'.$data['reference_external'].'</span>] <span class="log-showoff-format">'.$data['name_fiscal'].'</span>';

                    // Some Poor Man checks:
                    if ( ! $data['name_fiscal'] )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'name_fiscal' está vacío");

                    if ( strlen( $data['name_fiscal'] ) > 128 )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'name_fiscal' es demasiado largo (128). ".$data['name_fiscal']);

                    if ( strlen( $data['name_commercial'] ) > 64 )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'name_commercial' es demasiado largo (64). ".$data['name_commercial']);

                    if ( $data['identification'] )
                    {
                        $data['identification'] = Customer::normalize_spanish_nif_cif_nie( $data['identification'] );
                        
                        // if ( Customer::check_spanish_nif_cif_nie( $data['identification'] ) <= 0 )
                        //    $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'identification' es inválido. ".$data['identification']);
                    }

                    $data['sales_equalization'] = (int) $data['sales_equalization'] > 0 ? 1 : 0;

                    $data['allow_login'] = (int) $data['allow_login'] > 0 ? 1 : 0;

                    $data['blocked'] = (int) $data['blocked'] > 0 ? 1 : 0;


                    // Precedence
                    $customer_index = 'id';
                    if ( isset( $data['id'] ) && $data['id'] > 0 )
                    {
                        // Ok. Product should exist
                        ;
                    }
                    else if ( isset($data['reference_external']) && $data['reference_external'] )
                    {
                        // Ok. Product does not exist and comes from an external system

                        if ( isset( $data['id'] ) )
                             unset( $data['id'] );

                        $customer_index = 'reference_external';
                    }
                    else
                    {
                        // Ok. Product does not exist

                        $data['id'] = '';
                    }



                    // Check related tables
                    // 'customer_group_id',   'price_list_id',    'payment_method_id',    'shipping_method_id'

                    if ( isset( $data['customer_group_id'] ) )
                    // if ( $data['customer_group_id'] )
                    {
                        if ( !CustomerGroup::where('id', $data['customer_group_id'])->exists() )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'customer_group_id' no existe. ".$data['customer_group_id']);
                    }

                    if ( isset( $data['price_list_id'] ) )
                    // if ( $data['price_list_id'] )
                    {
                        if ( !PriceList::where('id', $data['price_list_id'])->exists() )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'price_list_id' no existe. ".$data['price_list_id']);
                    }

                    if ( isset( $data['payment_method_id'] ) )
                    // if ( $data['payment_method_id'] )
                    {
                        if ( !PaymentMethod::where('id', $data['payment_method_id'])->exists() )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'payment_method_id' no existe. ".$data['payment_method_id']);
                    }

                    if ( isset( $data['shipping_method_id'] ) )
                    // if ( $data['shipping_method_id'] )
                    {
                        if ( !ShippingMethod::where('id', $data['shipping_method_id'])->exists() )
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'shipping_method_id' no existe. ".$data['shipping_method_id']);
                    }


                    $data['notes'] = $data['notes'] ?? '';

                    // Discard data sanitization (for now)
                    if ( 0 && $data['phone'] )
                    {
                            $phone = str_replace([' '], '', $data['phone']);

                            if ( strlen( $phone ) > 32 )
                            {
                                $data['notes'] = $data['phone'] * "\n" + $data['notes'];
                                $data['phone'] = substr($phone, 0, 9);

                            } else {
                                $data['phone'] = $phone;

                            }
                    }

                    // Discard data sanitization (for now)
                    if ( 0 && $data['phone_mobile'] )
                    {
                            $phone = str_replace([' '], '', $data['phone_mobile']);

                            if ( strlen( $phone ) > 32 )
                            {
                                $data['notes'] = $data['phone_mobile'] * "\n" + $data['notes'];
                                $data['phone_mobile'] = substr($phone, 0, 9);

                            } else {
                                $data['phone_mobile'] = $phone;

                            }
                    }
/*
                    $data['reference_external'] = intval( $data['reference_external'] );
                    $data['id'] = $data['reference_external'];
                    if ( $data['reference_external'] <= 0 ) {
                        $data['reference_external'] = '';
                        unset( $data['id'] );
                    }
*/
                    // 'webshop_id'
//                    $data['webshop_id'] = '';
//                    $reference_external = intval( $data['reference_external'] );
//                    if ( $reference_external > 50000 ) 
//                        $data['webshop_id'] = $reference_external - 50000;


                    if ( isset( $data['address1'] ) )
                    if ( strlen( $data['address1'] ) > 128 )
                    {
                            $data['notes'] .= "\n" . $data['address1'];

                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'address1' es demasiado largo (128). ".$data['address1']);
                    }

                    if ( isset( $data['firstname'] ) )
                    if ( strlen( $data['firstname'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['firstname'];

                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'firstname' es demasiado largo (32). ".$data['firstname']);
                    }

                    if ( isset( $data['lastname'] ) )
                    if ( strlen( $data['lastname'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['lastname'];

                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'lastname' es demasiado largo (32). ".$data['lastname']);
                    }

                    if ( isset( $data['email'] ) )
                    if ( strlen( $data['email'] ) > 128 )
                    {
                            $data['notes'] .= "\n" . $data['email'];

                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'email' es demasiado largo (128). ".$data['email']);
                    }

                    if ( isset( $data['phone'] ) )
                    if ( strlen( $data['phone'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['phone'];

                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'phone' es demasiado largo (32). ".$data['phone']);
                    }

                    if ( isset( $data['phone_mobile'] ) )
                    if ( strlen( $data['phone_mobile'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['phone_mobile'];

                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'phone_mobile' es demasiado largo (32). ".$data['phone_mobile']);
                    }


                    // Country
                    if ( !array_key_exists('country_id', $data) || intval($data['country_id']) == 0 ) 
                    {
                        $data['country_id'] = Configuration::get('DEF_COUNTRY');
                    }
                    $country = Country::find($data['country_id']);

if ($country) {                    

                    // State
                    if ( !array_key_exists('state_id', $data) ) 
                    {
                        // Guess
                        $data['state_id'] = 0;
                        if ( array_key_exists('STATE_NAME', $data) ) 
                        {
                        
                            if ( array_key_exists($data['STATE_NAME'], $state_names) )
                                $data['state_id'] = $state_names[$data['STATE_NAME']];
                            else
                                if ( array_key_exists(strtoupper($data['STATE_NAME']), $state_names) )
                                    $data['state_id'] = strtoupper($state_names[$data['STATE_NAME']]);
                        }
                    }
                    if ( !$country->hasState( $data['state_id'] ) )
                        $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'state_id' es inválido o no corresponde con el país: " . ($data['state_id'] ?? $data['STATE_NAME'] ?? ''));

                    // VAT ID 'identification'
                    if ( array_key_exists('identification', $data) && trim($data['identification'])) 
                    {
                        // Check
                        if ( !$country->checkIdentification( $data['identification'] ) ) 
                        {
                        
                            $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'identification' es inválido o no corresponde con el país: " . $data['identification']);
                        }
                    }

} else {

                    // No Country
                    $logger->log("ERROR", "Cliente ".$item.":<br />" . "El campo 'country_id' es inválido: " . ($data['country_id'] ?? ''));

}

                    $data['alias'] = l('Main Address', [],'addresses');

                    $data['outstanding_amount_allowed'] = $data['outstanding_amount_allowed'] ?? Configuration::get('DEF_OUTSTANDING_AMOUNT');


                    if ( $data['notes'] )
                    {
                        $data['notes'] = trim( $data['notes'] );

                        // $logger->log("WARNING", "Cliente ".$item.":<br />" . "El campo 'notes' es: " . $data['notes']);
                    }


                    // http://fideloper.com/laravel-database-transactions
                    // https://stackoverflow.com/questions/45231810/how-can-i-use-db-transaction-in-laravel


                    \DB::beginTransaction();
                    try {

                        $customer = $this->customer->where( $customer_index, $data[$customer_index] )
                                                    ->with('address')
                                                    ->first();

                        if ( !($params['simulate'] > 0) && $customer ) 
                        {

                            $logger->log("INFO", "El Cliente ".$data[$customer_index] ." existe y se actualizará");

                            $address = $customer->address;

                            $customer->update( $data );

                            unset( $data['webshop_id'] );       // Address has a 'webshop_id' field

                            if ( $address )
                            {

                                $address->fill($data);
                                $address->save();

                            } else {

                                $address = $this->address->create($data);
                                $customer->addresses()->save($address);

                                $customer->update(['invoicing_address_id' => $address->id, 'shipping_address_id' => $address->id]);
                                
                            }




/*      Old implementation, update some fields only

                            $fields = [
                                'sales_equalization',  'customer_group_id',   'price_list_id',    'payment_method_id',    'shipping_method_id',  'outstanding_amount_allowed',  'allow_login', 'blocked', 'email'
                            ];

                            $mini_data = [];

                            foreach ($fields as $field) {
                                # code...
                                 $mini_data[$field] =  $data[$field];
                            }

                            $mini_data['blocked'] =  (int) $mini_data['blocked'];

                            $customer->update( $mini_data );
*/
                            // $logger->log("INFO", "El Cliente ".$data[$customer_index] ." se ha actualizado (".$customer->id.")");

                            $i_updated++;

                        } else

                        if ( !($params['simulate'] > 0) ) 
                        {
                            // Create Customer
                            // $product = $this->product->create( $data );
    //                        $customer = $this->customer->storeOrUpdate( [ 'reference_external' => $data['reference_external'] ], $data );
/*
                            $customer = new Customer;
                            $customer = $customer->fill($data);
                            if ( isset($data['id']) ) $customer->id = $data['id'];
                            else unset( $customer->id );
                            $customer->save();
*/

                            if ( !$customer )

                            $customer = $this->customer->create( $data );
                            // $customer = $this->customer->updateOrCreate( [ 
                            //     'reference_external' => $data['reference_external'] 
                            // ], $data );

                            // else

                            // $customer->update( $data );


                            // $logger->log("TIMER", " Se ha creado el Cliente: ".$item." - " . $customer->id);

                            unset( $data['webshop_id'] );

                            $address = $customer->address;

                            if ( $address )
                            {

                                $address->fill($data);
                                $address->save();

                            } else {

                                $address = $this->address->create($data);
                                $customer->addresses()->save($address);

                                $customer->update(['invoicing_address_id' => $address->id, 'shipping_address_id' => $address->id]);
                                
                            }

                            $logger->log("INFO", "El Cliente ".$data['reference_external'] ." se ha creado (".$customer->id.")");

                            $i_created++;

                        } else {

                            if ( !$customer )

                            $logger->log("INFO", "El Cliente ".$data[$customer_index] ." no existe y debe crearse");

                        }

                        $i_ok++;

                    }
                    catch(\Exception $e) {

                            \DB::rollback();

                            $item = '[<span class="log-showoff-format">'.$data[$customer_index].'</span>] <span class="log-showoff-format">'.$data['name_fiscal'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar el Cliente ".$item.":<br />" . $e->getMessage());

                    }

                    // If we reach here, then
                    // data is valid and working.
                    // Commit the queries!
                    \DB::commit();

                    $i++;

                }   // End foreach


                // Customer::reguard();


            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Clientes en el fichero.');
            }

            $logger->log('INFO', 'Se han creado {i} Clientes.', ['i' => $i_created]);

            $logger->log('INFO', 'Se han actualizado {i} Clientes.', ['i' => $i_updated]);

            $logger->log('INFO', 'Se han creado / actualizado {i} Clientes.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Clientes.', ['i' => $i]);

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
        
        $customers = $this->customer
                        ->with('customergroup')
                        ->with('pricelist')
                        ->with('paymentmethod')
                        ->with('shippingmethod')
                        ->with('address')
                        ->with('address.country')
                        ->with('address.state')
//                        ->with('currency');
                        ->orderBy('id', 'asc')
                        ->get();


        // Initialize the array which will be passed into the Excel generator.
        $data = [];  

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'reference_external', 'accounting_id', 'webshop_id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 
                    'customer_group_id', 'CUSTOMER_GROUP_NAME', 'price_list_id', 'PRICE_LIST_NAME', 
                    'payment_method_id', 'PAYMENT_METHOD_NAME', 'shipping_method_id', 'SHIPPING_METHOD_NAME', 
                    'outstanding_amount_allowed', 'notes', 'allow_login', 'blocked', 'active', 
                    'currency_id', 'language_id', 

                    'address1', 'address2', 'postcode', 'city', 'state_id', 'STATE_NAME', 'country_id', 'COUNTRY_NAME', 
                    'firstname', 'lastname', 'email', 'phone', 'phone_mobile', 'fax', 'sales_rep_id'
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($customers as $customer) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $customer->{$header} ?? ( $customer->address->{$header} ?? '');
            }
            $row['CUSTOMER_GROUP_NAME']  = $customer->customergroup  ? $customer->customergroup->name : '';
            $row['PRICE_LIST_NAME']      = $customer->pricelist      ? $customer->pricelist->name : '';
            $row['PAYMENT_METHOD_NAME']  = $customer->paymentmethod  ? $customer->paymentmethod->name : '';
            $row['SHIPPING_METHOD_NAME'] = $customer->shippingmethod ? $customer->shippingmethod->name : '';
            $row['STATE_NAME']           = $customer->address->state    ? $customer->address->state->name : '';
            $row['COUNTRY_NAME']         = $customer->address->country  ? $customer->address->country->name : '';

            $data[] = $row;
        }

        $sheetName = 'Customers' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Customers', function($excel) use ($sheetName, $data) {

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