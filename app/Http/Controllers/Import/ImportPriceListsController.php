<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PriceList;
use App\PriceListLine;
use App\Product;

use Excel;

class ImportPriceListsController extends Controller
{
/*
   use BillableTrait;

   protected $customer, $customerOrder, $customerOrderLine;

   public function __construct(Customer $customer, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine)
   {
        $this->customer = $customer;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
   }
*/
    public static $table = 'price_list_lines';

    public static $column_mask;		// Column fields

//    public $entities = array();	// This controller is for ONE entity

    public $available_fields = array();

    public $required_fields = array();

//    public $cache_image_deleted = array();

    public static $default_values = array();
/*
    public static $validators = array(
        'active' => array('AdminImportController', 'getBoolean'),
        'tax_rate' => array('AdminImportController', 'getPrice'),
        /** Tax excluded * /
        'price_tex' => array('AdminImportController', 'getPrice'),
        /** Tax included * /
        'price_tin' => array('AdminImportController', 'getPrice'),
        'reduction_price' => array('AdminImportController', 'getPrice'),
        'reduction_percent' => array('AdminImportController', 'getPrice'),
        'wholesale_price' => array('AdminImportController', 'getPrice'),
        'ecotax' => array('AdminImportController', 'getPrice'),
        'name' => array('AdminImportController', 'createMultiLangField'),
        'description' => array('AdminImportController', 'createMultiLangField'),
        'description_short' => array('AdminImportController', 'createMultiLangField'),
        'meta_title' => array('AdminImportController', 'createMultiLangField'),
        'meta_keywords' => array('AdminImportController', 'createMultiLangField'),
        'meta_description' => array('AdminImportController', 'createMultiLangField'),
        'link_rewrite' => array('AdminImportController', 'createMultiLangField'),
        'available_now' => array('AdminImportController', 'createMultiLangField'),
        'available_later' => array('AdminImportController', 'createMultiLangField'),
        'category' => array('AdminImportController', 'split'),
        'online_only' => array('AdminImportController', 'getBoolean'),
    );
*/
    public $separator = ';';

    public $multiple_value_separator = ',';


// //////////////////////////////////////////////////// //   

   protected $pricelist;
   protected $pricelistline;
   protected $product;

   public function __construct(PriceList $pricelist, PriceListLine $pricelistline, Product $product)
   {
        $this->pricelist = $pricelist;
        $this->pricelistline  = $pricelistline;
        $this->product = $product;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import($id)
    {
        $pricelist = $this->pricelist->findOrFail($id);

        return view('imports.price_lists', compact('pricelist'));
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

    public function process(Request $request, $id)
    {
        $pricelist = $this->pricelist->findOrFail($id);

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


        $name = '['.$pricelist->id.'] '.$pricelist->name;

        // Start Logger
        $logger = \App\ActivityLogger::setup( 'Import Price List', __METHOD__ );        // 'Import Customers :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        if ( $request->input('empty_log', 0) ) 
        {
            $logger->empty();
        }

        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargará la Tarifa {name} desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['name' => $name, 'file' => $file]);


        // Delete Price List Lines
        $pricelist->pricelistlines()->delete();


        $params = ['price_list_id' => $pricelist->id, 'name' => $name, 'simulate' => $request->input('simulate', 0)];


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se ha cargado la Tarifa :name desde el Fichero: <strong>:file</strong> .', ['name' => $name, 'file' => $file]));


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
        Excel::filter('chunk')->load( $file )->chunk(250, function ($reader) use ( $logger, $params )
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

            $price_list_id = intval($params['price_list_id']);
            $pricelist = $this->pricelist->findOrFail($price_list_id);

            $name = $params['name'];

            $i = 0;
            $i_ok = 0;
            $max_id = 2000;


            if(!empty($reader) && $reader->count()) {

                
                // Customer::unguard();        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    // $item = implode(', ', $data);
                    // $item = str_replace('=', ':', http_build_query($data, null, ', '));
                    $item = http_build_query($data, null, ', ');

                    // Some Poor Man checks:
                    if ( $data['price'] <= 0.0 ) {
                        
                        // Product not for this Price List

                        $i++;
                        continue;
                    }

                    if ( intval($data['price_list_id']) != $price_list_id ) {
                        
                        $logger->log("ERROR", "La fila (".$item.") no corresponde a la Tarifa ".$name);

                        $i++;
                        continue;
                    }

                    // Get Product id
                    if ( array_key_exists('product_id', $data) ) {

                        // OK. Let's move on
                    } else

                    if ( array_key_exists('reference', $data) ) {

                        $product = $this->product->where('reference', $data['reference'])->first();

                        if ( $product ) 
                            $data['product_id'] = $product->id;
                        else {
                            
                            $logger->log("ERROR", "La fila (".$item.") no corresponde a ningún Producto. [".$data['reference']."]");

                            $i++;
                            continue;
                        }

                    } else {
                        
                        $logger->log("ERROR", "La fila (".$item.") no corresponde a ningún Producto.");

                        $i++;
                        continue;
                    }
                    







                    // http://fideloper.com/laravel-database-transactions
                    // https://stackoverflow.com/questions/45231810/how-can-i-use-db-transaction-in-laravel


                    \DB::beginTransaction();
                    try {
                        if ( !($params['simulate'] > 0) ) 
                        {
                            // Create Price List line
                            $pricelistline = $this->pricelistline->create( $data );

                            $pricelist->pricelistlines()->save($pricelistline);

                        }

                        $i_ok++;

                    }
                    catch(\Exception $e) {

                            \DB::rollback();

                            $logger->log("ERROR", "Se ha producido un error al procesar la fila (".$item."):<br />" . $e->getMessage());

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
                $logger->log('WARNING', 'No se encontraton datos de Tarifa en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Líneas de Tarifa.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Líneas de Tarifa.', ['i' => $i]);

// Process reader          
    
        }, false);      // should not queue $shouldQueue

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export($id)
    {
        
/*        $pricelist = $this->pricelist
                    ->with('pricelistlines')
                    ->with('pricelistlines.product')
                    ->findOrFail($id);
*/

        $pricelist  = $this->pricelist->findOrFail($id);
        $lines = $this->pricelistline
                        ->select('price_list_lines.*', 'products.id', 'products.reference', 'products.name')
//                        ->with('product')
                        ->where('price_list_id', $id)
                        ->join('products', 'products.id', '=', 'price_list_lines.product_id')       // Get field to order by
                        ->orderBy('products.reference', 'asc')
                        ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $data[] = [ 'reference', 'NOMBRE', 'price', 'price_list_id' ];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($lines as $line) {
            // $data[] = $line->toArray();
            $data[] = [
                            'reference' => $line->reference,
                            'NOMBRE' => $line->name,
                            'price' => $line->price,
                            'price_list_id' => $id,
            ];
        }

        $sheetName = $pricelist->price_is_tax_inc > 0 ?
                    'Precio incluye IVA' :
                    'Precio es SIN IVA' ;

        // Generate and return the spreadsheet
        Excel::create('Price_List_'.$id, function($excel) use ($id, $sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }
}