<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Configuration;
use App\Models\Product;
use App\Models\StockCount;
use App\Models\StockCountLine;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;

class ImportStockCountsController extends Controller
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
    public static $table = 'stock_count_lines';

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

   protected $stockcount;
   protected $stockcountline;
   protected $product;

   public function __construct(StockCount $stockcount, StockCountLine $stockcountline, Product $product)
   {
        $this->stockcount = $stockcount;
        $this->stockcountline  = $stockcountline;
        $this->product = $product;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import($id)
    {
        $stockcount = $this->stockcount->findOrFail($id);

        return view('imports.stock_counts', compact('stockcount'));
    }

    public function process(Request $request, $id)
    {
        $stockcount = $this->stockcount->findOrFail($id);

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


        $name = '['.$stockcount->id.'] '.$stockcount->name;

        // Start Logger
        $logger = ActivityLogger::setup( 'Import Stock Count', __METHOD__ )
                    ->backTo( route('stockcounts.stockcountlines.index', [$stockcount->id]) );


        if ( $request->input('empty_log', 0) ) 
        {
            $logger->empty();
        }

        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargará el Recuento de Stock {name} desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['name' => $name, 'file' => $file]);

/*
        if ( $request->input('round_price', 0) )
        {
            $decimal_places = $stockcount->currency->decimalPlaces;
            $logger->log("INFO", 'Se redondearán los Precios a <span class="log-showoff-format">{decimal_places} decimales</span>.', ['decimal_places' => $decimal_places]);
            
        } else {

            $decimal_places = -1;
        }
*/


        $params = [
                    'stock_count_id' => $stockcount->id, 
                    'name' => $name, 
//                    'decimal_places' => $decimal_places, 
                    'simulate' => $request->input('simulate', 0),
        ];

        // Delete Stock Count Lines
        if ( !($params['simulate'] > 0) )
            $stockcount->stockcountlines()->delete();


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se ha cargado el Recuento de Stock :name desde el Fichero: <strong>:file</strong> .', ['name' => $name, 'file' => $file]));
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

            $stock_count_id = intval($params['stock_count_id']);
            $stockcount = $this->stockcount->findOrFail($stock_count_id);

            $name = $params['name'];
//            $decimal_places = $params['decimal_places'];

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
                    if ( intval($data['stock_count_id']) != $stock_count_id ) {
                        
                        $logger->log("ERROR", 'La fila <span class="log-ERROR-format">('.$item.')</span> no corresponde al Recuento de Stock <span class="log-showoff-format">'.$name.'</span>');

                        $i++;
                        continue;
                    }

                    // Need rounding?
//                    if ( $decimal_places >= 0 ) {
//                        $data['price'] = round($data['price'], $decimal_places);
//                    }

                    // Get Product
                    $product = null;
                    if ( array_key_exists('product_id', $data) && ($product_id=trim($data['product_id'])) ) {

                        $product = $this->product->find($product_id);

                    } else

                    if ( array_key_exists('reference', $data) && ($reference=trim($data['reference'])) ) {

                        $product = $this->product->where('reference', $reference)->first();
                    } 

                    if ( $product ) {
                            $data['product_id'] = $product->id;
                            $data['reference']  = $product->reference;
                            $data['name']  = $product->name;

                            // Cost Price
                            if ( array_key_exists('cost_price', $data) )
                            {
                                if ( floatval( trim($data['cost_price']) ) <= 0.0 ) 
                                {
                                    // Use current
                                    $data['cost_price'] = $product->cost_price;
                                } else {
                                    $data['cost_price'] = floatval( trim($data['cost_price']) );
                                }
                            } else {
                                // Use current
                                $data['cost_price'] = $product->cost_price;
                            }

                            // Cost Average
                            if ( array_key_exists('cost_average', $data) )
                            {
                                if ( ! (($data['cost_average']=(float) $data['cost_average']) > 0) ) 
                                {
                                    // Use current
                                    $data['cost_average'] = $product->cost_average;
                                }

                            } else {
                                // Use current
                                $data['cost_average'] = $product->cost_average;
                            }

                            // Last Purchase Price
                            if ( array_key_exists('last_purchase_price', $data) )
                            {
                                if ( floatval( trim($data['last_purchase_price']) ) <= 0.0 ) 
                                {
                                    // Use current
                                    $data['last_purchase_price'] = $product->last_purchase_price;
                                } else {
                                    $data['last_purchase_price'] = floatval( trim($data['last_purchase_price']) );
                                }
                            } else {
                                // Use current
                                $data['last_purchase_price'] = $product->last_purchase_price;
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
                            // Create Stock Count line
                            $stockcountline = $this->stockcountline->create( $data );

                            $stockcount->stockcountlines()->save($stockcountline);

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
                $logger->log('WARNING', 'No se encontraton datos de Recuento de Stock en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Líneas de Recuento de Stock.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Líneas de Recuento de Stock.', ['i' => $i]);

// Process reader ENDS

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export($id)
    {
//        Configuration::updateValue('EXPORT_DECIMAL_SEPARATOR', ',');
        
/*        $stockcount = $this->stockcount
                    ->with('stockcountlines')
                    ->with('stockcountlines.product')
                    ->findOrFail($id);
*/

        $stockcount  = $this->stockcount->with('warehouse')->findOrFail($id);
        $lines = $this->stockcountline
                        ->select('stock_count_lines.*', 'products.id', 'products.reference', 'products.name')
//                        ->with('product')
                        ->where('stock_count_id', $id)
                        ->join('products', 'products.id', '=', 'stock_count_lines.product_id')       // Get field to order by
                        ->orderBy('products.reference', 'asc')
                        ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [ 'product_id', 'reference', 'NOMBRE', 'quantity', 'cost_price', 'cost_average', 'last_purchase_price', 'stock_count_id', 'stock_count_NAME' ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($lines as $line) {
            // $data[] = $line->toArray();
            $data[] = [
                            'product_id' => $line->product_id,
                            'reference' => $line->reference,
                            'NOMBRE' => $line->name,
                            'quantity'   => (float) $line->quantity,
                            'cost_price' => (float) $line->cost_price,
                            'cost_average' => (float) $line->cost_average,
                            'last_purchase_price' => (float) $line->last_purchase_price,
                            'stock_count_id' => $id,
                            'stock_count_NAME' => $stockcount->name,
            ];
        }

        $styles = [];

        $sheetTitle = $stockcount->warehouse->alias.' - '.$stockcount->warehouse->name;

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $sheetFileName = 'Stock_Count_'.$id.'_'.$stockcount->warehouse->alias;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}