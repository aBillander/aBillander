<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Product as Product;

use Excel;

class ImportProductsController extends Controller
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
    public static $table = 'products';

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

   protected $product;

   public function __construct(Product $product)
   {
        $this->product = $product;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import()
    {
        return view('imports.products');
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
        $logger = \App\ActivityLogger::setup( 'Import Products', __METHOD__ );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Productos desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $truncate = $request->input('truncate', 0);

        // Truncate table
        if ( $truncate > 0 ) {

            $nbr = Product::count();
            
            Product::truncate();

            $logger->log("INFO", "Se han borrado todos los Productos antes de la Importación. En total {nbr} Productos.", ['nbr' => $nbr]);
        }


        try{
            
            $this->processFile( $request->file('data_file'), $logger );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado los Productos desde el Fichero: <strong>:file</strong> .', ['file' => $file]));


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
    protected function processFile( $file, $logger )
    {

        // 
        // See: https://www.youtube.com/watch?v=rWjj9Slg1og
        // https://laratutorials.wordpress.com/2017/10/03/how-to-import-excel-file-in-laravel-5-and-insert-the-data-in-the-database-laravel-tutorials/
        Excel::filter('chunk')->load( $file )->chunk(250, function ($reader) use ( $logger )
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

            $i = 0;
            $i_ok = 0;
            $max_id = 1000;


            if(!empty($reader) && $reader->count()) {

                        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    // if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    // Some Poor Man checks:
                    $data['quantity_decimal_places'] = intval( $data['quantity_decimal_places'] );

                    $data['manufacturing_batch_size'] = intval( $data['manufacturing_batch_size'] );
                    if ( $data['manufacturing_batch_size'] <= 0 ) $data['manufacturing_batch_size'] = 1;

                    $data['measure_unit_id'] = intval( $data['measure_unit_id'] );
                    if ( $data['measure_unit_id'] <= 0 ) $data['measure_unit_id'] = \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS');

                    $data['work_center_id'] = intval( $data['work_center_id'] );
                    if ( $data['work_center_id'] <= 0 ) $data['work_center_id'] = NULL;

                    $data['main_supplier_id'] = intval( $data['main_supplier_id'] );
                    if ( $data['main_supplier_id'] <= 0 ) $data['main_supplier_id'] = NULL;




                    try{
                        
                        // Create Product
                        // $product = $this->product->create( $data );
                        $product = $this->product->updateOrCreate( [ 'reference' => $data['reference'] ], $data );

                        $i_ok++;

                    }
                    catch(\Exception $e){

                            $item = '[<span class="log-showoff-format">'.$data['reference'].'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar el Producto ".$item.":<br />" . $e->getMessage());

                    }

                    $i++;

                }   // End foreach

            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Productos en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Productos.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Productos.', ['i' => $i]);

// Process reader          
    
        }, false);      // should not queue $shouldQueue

    }
}