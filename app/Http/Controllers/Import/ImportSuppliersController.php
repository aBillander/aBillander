<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Sequence;
use App\Models\State;
use App\Models\Supplier;
use Excel;
use Illuminate\Http\Request;

class ImportSuppliersController extends Controller
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
    public static $table = 'suppliers';

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

   protected $supplier;
   protected $address;
   protected $product;

   public function __construct(Supplier $supplier, Address $address, Product $product)
   {
        $this->supplier = $supplier;
        $this->address  = $address;
        $this->product = $product;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import()
    {
        return view('imports.suppliers');
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
        $logger = ActivityLogger::setup( 'Import Suppliers', __METHOD__ )
                    ->backTo( route('suppliers.import') );        // 'Import Suppliers :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Proveedores desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $simulate = (int) $request->input('simulate', 0);
        $truncate = (int) $request->input('truncate', 0);

        $params = ['simulate' => $simulate, 'file' => $file];

        // Truncate table
        if ( $truncate > 0 ) {

            $nbr = Supplier::count();

            if ( $simulate > 0 ) {

                $logger->log("INFO", "NO se han borrado los Proveedores antes de la Importación (Modo SIMULACION). En total {nbr} Proveedores.", ['nbr' => $nbr]);
            } else {
                
                Supplier::truncate();

                // SELECT * FROM `addresses` where `addressable_type`='App\\Supplier'

                // Soft-deleting...We dont want thies here!
                // $this->address->where('addressable_type', 'App\\Supplier')->delete();

                // $collection = Address::where('addressable_type', "App\\Supplier")->get(['id']);
                // Address::destroy($collection->toArray());

                \DB::table('addresses')->where('addressable_type', "App\\Supplier")->delete();

                // Note: This solution is for resetting the auto_increment of the table without truncating the table itself
                $max = \DB::table('addresses')->max('id') + 1; 
                \DB::statement("ALTER TABLE addresses AUTO_INCREMENT = $max");

                $logger->log("INFO", "Se han borrado todos los Proveedores antes de la Importación. En total {nbr} Proveedores.", ['nbr' => $nbr]);
            }
        }


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado los Proveedores desde el Fichero: <strong>:file</strong> .', ['file' => $file]));


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

                
                // Supplier::unguard();        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    // $item = implode(', ', $data);
                    $item = http_build_query($data, null, ', ');

                    // $item = '[<span class="log-showoff-format">'.$data['reference_external'].'</span>] <span class="log-showoff-format">'.$data['name_fiscal'].'</span>';

                    // Some Poor Man checks:
                    if ( ! $data['name_fiscal'] )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'name_fiscal' está vacío");

                    if ( strlen( $data['name_fiscal'] ) > 128 )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'name_fiscal' es demasiado largo (128). ".$data['name_fiscal']);

                    if ( strlen( $data['name_commercial'] ) > 64 )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'name_commercial' es demasiado largo (64). ".$data['name_commercial']);

                    if ( 0 && $data['identification'] )
                    {
                        $data['identification'] = Supplier::normalize_spanish_nif_cif_nie( $data['identification'] );
                        
                        // if ( Supplier::check_spanish_nif_cif_nie( $data['identification'] ) <= 0 )
                        //    $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'identification' es inválido. ".$data['identification']);
                    }

                    $data['approval_number'] = $data['approval_number'] ?? '';

                    // $data['sales_equalization'] = (int) $data['sales_equalization'] > 0 ? 1 : 0;

                    $data['blocked']  = (int) ($data['blocked']  ?? 0) > 0 ? 1 : 0;

                    $data['approved'] = (int) ($data['approved'] ?? 1) > 0 ? 1 : 0;



                    // Check related tables
                    // 'customer_group_id',   'price_list_id',    'payment_method_id',    'shipping_method_id'

                    if ( $data['payment_method_id'] )
                    {
                        if ( !PaymentMethod::where('id', $data['payment_method_id'])->exists() )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'payment_method_id' no existe. ".$data['payment_method_id']);
                    }

                    if ( $data['language_id'] )
                    {
                        if ( !Language::where('id', $data['language_id'])->exists() )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'language_id' no existe. ".$data['language_id']);
                    }

                    if ( $data['currency_id'] )
                    {
                        if ( !Currency::where('id', $data['currency_id'])->exists() )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'currency_id' no existe. ".$data['currency_id']);
                    }

                    if ( $data['invoice_sequence_id'] )
                    {
                        if ( !Sequence::where('id', $data['invoice_sequence_id'])->exists() )
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'invoice_sequence_id' no existe. ".$data['invoice_sequence_id']);
                    }


                    $data['notes'] = $data['notes'] ?? '';

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

                    if ( strlen( $data['address1'] ) > 128 )
                    {
                            $data['notes'] .= "\n" . $data['address1'];

                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'address1' es demasiado largo (128). ".$data['address1']);
                    }

                    if ( strlen( $data['firstname'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['firstname'];

                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'firstname' es demasiado largo (32). ".$data['firstname']);
                    }

                    if ( strlen( $data['lastname'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['lastname'];

                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'lastname' es demasiado largo (32). ".$data['lastname']);
                    }

                    if ( strlen( $data['email'] ) > 128 )
                    {
                            $data['notes'] .= "\n" . $data['email'];

                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'email' es demasiado largo (128). ".$data['email']);
                    }

                    if ( strlen( $data['phone'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['phone'];

                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'phone' es demasiado largo (32). ".$data['phone']);
                    }

                    if ( strlen( $data['phone_mobile'] ) > 32 )
                    {
                            $data['notes'] .= "\n" . $data['phone_mobile'];

                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'phone_mobile' es demasiado largo (32). ".$data['phone_mobile']);
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
                        $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'state_id' es inválido o no corresponde con el país: " . ($data['state_id'] ?? $data['STATE_NAME'] ?? ''));

                    // VAT ID 'identification'
                    if ( 0 && array_key_exists('identification', $data) && trim($data['identification'])) 
                    {
                        // Check
                        if ( !$country->checkIdentification( $data['identification'] ) ) 
                        {
                        
                            $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'identification' es inválido o no corresponde con el país: " . $data['identification']);
                        }
                    }

} else {

                    // No Country
                    $logger->log("ERROR", "Proveedor ".$item.":<br />" . "El campo 'country_id' es inválido: " . ($data['country_id'] ?? ''));

}

                    $data['alias'] = l('Main Address', [],'addresses');


                    if ( $data['notes'] )
                    {
                        $data['notes'] = trim( $data['notes'] );

                        // $logger->log("WARNING", "Proveedor ".$item.":<br />" . "El campo 'notes' es: " . $data['notes']);
                    }


                    // Let's see which Supplier
                    $key_name = $key_val = '';

                    if ( array_key_exists('id', $data) )
                    {
                        $key_name = 'id';
                        $key_val = trim( $data['id'] );

                        unset( $data['id'] );
                        // Unomment lines to prevent changes in reference value
                        // if ( array_key_exists('reference', $data) )
                        //     unset( $data['reference'] );
                        
                    } else
/*
                    if ( array_key_exists('reference_external', $data) )
                    {
                        $key_name = 'reference_external';
                        $key_val = trim( $data['reference_external'] );

                        unset( $data['reference_external'] );
                        
                    }
*/

                    // http://fideloper.com/laravel-database-transactions
                    // https://stackoverflow.com/questions/45231810/how-can-i-use-db-transaction-in-laravel


                    \DB::beginTransaction();
                    try {

                        $supplier = null;

                        if ( $key_name != '' )
                            $supplier = $this->supplier->where( $key_name, $key_val )
                                                    ->with('address')
                                                    ->first();

                        if ( !($params['simulate'] > 0) && $supplier ) 
                        {

                            $logger->log("INFO", "El Proveedor ". $key_val ." existe y se actualizará");

                            $address = $supplier->address;

                            $supplier->update( $data );

                            unset( $data['webshop_id'] );       // Address has a 'webshop_id' field

                            if ( $address )
                            {

                                $address->fill($data);
                                $address->save();

                            } else {

                                $address = $this->address->create($data);
                                $supplier->addresses()->save($address);

                                $supplier->update(['invoicing_address_id' => $address->id, 'shipping_address_id' => $address->id]);
                                
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
                            // $logger->log("INFO", "El Proveedor ".$data['reference_external'] ." se ha actualizado (".$customer->id.")");

                            $i_updated++;

                        } else

                        if ( !($params['simulate'] > 0) ) 
                        {
                            // Create Supplier
                            // $product = $this->product->create( $data );
    //                        $customer = $this->customer->storeOrUpdate( [ 'reference_external' => $data['reference_external'] ], $data );
/*
                            $customer = new Supplier;
                            $customer = $customer->fill($data);
                            if ( isset($data['id']) ) $customer->id = $data['id'];
                            else unset( $customer->id );
                            $customer->save();
*/

                            if ( !$supplier )

                            $supplier = $this->supplier->create( $data );
                            // $customer = $this->customer->updateOrCreate( [ 
                            //     'reference_external' => $data['reference_external'] 
                            // ], $data );

                            // else

                            // $customer->update( $data );


                            // $logger->log("TIMER", " Se ha creado el Proveedor: ".$item." - " . $customer->id);

                            unset( $data['webshop_id'] );

                            $address = $supplier->address;

                            if ( $address )
                            {

                                $address->fill($data);
                                $address->save();

                            } else {

                                $address = $this->address->create($data);
                                $supplier->addresses()->save($address);

                                $supplier->update(['invoicing_address_id' => $address->id, 'shipping_address_id' => $address->id]);
                                
                            }

                            $logger->log("INFO", "El Proveedor ". $key_val ." se ha creado (".$supplier->id.")");

                            $i_created++;

                        } else {

                            // if ( !$supplier )

                            // $logger->log("INFO", "El Proveedor ".$data['reference_external'] ." no existe y debe crearse");

                        }

                        $i_ok++;

                    }
                    catch(\Exception $e) {

                            \DB::rollback();

                            // $item = '[<span class="log-showoff-format">'. $key_val.'</span>] <span class="log-showoff-format">'.$data['name_fiscal'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar el Proveedor ".$item.":<br />" . $e->getMessage());

                    }

                    // If we reach here, then
                    // data is valid and working.
                    // Commit the queries!
                    \DB::commit();

                    $i++;

                }   // End foreach


                // Supplier::reguard();


            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Proveedores en el fichero.');
            }

            $logger->log('INFO', 'Se han creado {i} Proveedores.', ['i' => $i_created]);

            $logger->log('INFO', 'Se han actualizado {i} Proveedores.', ['i' => $i_updated]);

            $logger->log('INFO', 'Se han creado / actualizado {i} Proveedores.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Proveedores.', ['i' => $i]);

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
        
        $suppliers = $this->supplier
                        ->with('paymentmethod')
                        ->with('address')
                        ->with('address.country')
                        ->with('address.state')
//                        ->with('currency');
                        ->orderBy('name_fiscal', 'asc')
                        ->get();


        // Initialize the array which will be passed into the Excel generator.
        $data = [];  

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'name_fiscal', 'name_commercial', 'identification', 'approval_number', 'reference_external', 'accounting_id', 
                    // 'sales_equalization', 
                    'website', // 'customer_center_url', 'customer_center_user', 'customer_center_password', 

                    'payment_method_id', 'PAYMENT_METHOD_NAME', 'discount_percent', 'discount_ppd_percent', 'payment_days', 'delivery_time', 
                    'creditor', 'customer_id',
                    'notes', 'approved', 'blocked', 'active', 
                    'currency_id', 'CURRENCY_NAME', 'language_id', 'LANGUAGE_NAME',  
                    'invoice_sequence_id', 

                    'address1', 'address2', 'postcode', 'city', 'state_id', 'STATE_NAME', 'country_id', 'COUNTRY_NAME', 
                    'firstname', 'lastname', 'email', 'phone', 'phone_mobile', 'fax', 
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($suppliers as $supplier) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $supplier->{$header} ?? ( $supplier->address->{$header} ?? '');
            }

            // Casting
            $row['discount_percent'] = (float) $row['discount_percent'];
            $row['discount_ppd_percent'] = (float) $row['discount_ppd_percent'];

            $row['creditor'] = (int) $row['creditor'];
            $row['approved'] = (int) $row['approved'];
            $row['blocked'] = (int) $row['blocked'];
            $row['active'] = (int) $row['active'];

            $row['PAYMENT_METHOD_NAME']  = $supplier->paymentmethod  ? $supplier->paymentmethod->name : '';
            $row['CURRENCY_NAME']        = $supplier->currency->name    ? $supplier->currency->name : '';
            $row['LANGUAGE_NAME']        = $supplier->language->name    ? $supplier->language->name : '';
            $row['STATE_NAME']           = $supplier->address->state    ? $supplier->address->state->name : '';
            $row['COUNTRY_NAME']         = $supplier->address->country  ? $supplier->address->country->name : '';

            $data[] = $row;
        }

        $sheetName = 'Suppliers' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Suppliers', function($excel) use ($sheetName, $data) {

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


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function exportProducts()
    {
 /*       
        $suppliers = $this->supplier
                        ->with('paymentmethod')
                        ->with('address')
                        ->with('address.country')
                        ->with('address.state')
//                        ->with('currency');
                        ->orderBy('name_fiscal', 'asc')
                        ->get();
*/
        $products = $this->product
                          ->where('procurement_type', 'purchase')
                          ->with('measureunit')
                          ->with('purchasemeasureunit')
//                          ->with('combinations')                                  
                          ->with('category')
                          ->with('supplier')
                          ->orderBy('main_supplier_id', 'asc')
                          ->orderBy('category_id', 'asc')
                          ->orderBy('reference', 'asc')
                          ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];  

        // Define the Excel spreadsheet headers
        $headers = [ 
                    'id', 'reference', 'name', 'ean13', 'category_id', 'CATEGORY_NAME', 
                    'cost_price', 'cost_average', 'last_purchase_price', 
                    'stock_control', 'reorder_point', 'maximum_stock', 'lot_tracking', 'expiry_time', 'out_of_stock', 'out_of_stock_text', 
                    'blocked', 'active', 'measure_unit_id', 'MEASURE_UNIT_NAME', 'purchase_measure_unit_id', 'PURCHASE_MEASURE_UNIT_NAME', 
                    'main_supplier_id', 'SUPPLIER_IDENTIFICATION', 'SUPPLIER_NAME', 'supplier_reference', 'supply_lead_time', 

/*
                    'id', 'name_fiscal', 'name_commercial', 'identification', 'reference_external', 'accounting_id', 
                    // 'sales_equalization', 
                    'website', // 'customer_center_url', 'customer_center_user', 'customer_center_password', 

                    'payment_method_id', 'PAYMENT_METHOD_NAME', 'discount_percent', 'discount_ppd_percent', 'payment_days', 'delivery_time', 
                    'creditor', 'customer_id',
                    'notes', 'approved', 'blocked', 'active', 
                    'currency_id', 'CURRENCY_NAME', 'language_id', 'LANGUAGE_NAME',  
                    'invoice_sequence_id', 

                    'address1', 'address2', 'postcode', 'city', 'state_id', 'STATE_NAME', 'country_id', 'COUNTRY_NAME', 
                    'firstname', 'lastname', 'email', 'phone', 'phone_mobile', 'fax', 
*/
        ];

        $float_headers = [ 'price_tax_inc', 'price', 'cost_price', 'cost_average', 'last_purchase_price', 'recommended_retail_price', 'recommended_retail_price_tax_inc', 'width', 'height', 'depth', 'volume', 'weight', 'reorder_point', 'maximum_stock', 

        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($products as $product) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                // $row[$header] = $supplier->{$header} ?? ( $supplier->address->{$header} ?? '');

                if ( in_array($header, $float_headers) )
                    $row[$header] = (float) $product->{$header} ?? '';
                else
                    $row[$header] = $product->{$header} ?? '';
            }
            $row['CATEGORY_NAME']     = $product->category ? $product->category->name : '';
            $row['MEASURE_UNIT_NAME'] = $product->measureunit ? $product->measureunit->name : '';
            $row['PURCHASE_MEASURE_UNIT_NAME'] = $product->purchasemeasureunit ? $product->purchasemeasureunit->name : '';
            $row['SUPPLIER_NAME']     = $product->supplier ? $product->supplier->name_fiscal : '';
            $row['SUPPLIER_IDENTIFICATION']     = $product->supplier ? $product->supplier->identification : '';

            $data[] = $row;
        }

        $sheetName = 'Supplier Products' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Supplier_Products', function($excel) use ($sheetName, $data) {

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