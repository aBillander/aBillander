<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\PriceList;
use App\Models\PriceListLine;
use App\Models\Product;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;

class ImportPriceListsController extends Controller
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
        $logger = ActivityLogger::setup( 'Import Price List', __METHOD__ )
                    ->backTo( route('pricelists.import', [$pricelist->id]) );


        if ( !$request->input('simulate', 0) && $request->input('empty_log', 0) ) 
        {
            $logger->empty();
        }

        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargará la Tarifa {name} desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['name' => $name, 'file' => $file]);


        if ( $request->input('round_price', 0) )
        {
            $decimal_places = $pricelist->currency->decimalPlaces;
            $logger->log("INFO", 'Se redondearán los Precios a <span class="log-showoff-format">{decimal_places} decimales</span>.', ['decimal_places' => $decimal_places]);
            
        } else {

            $decimal_places = -1;
        }


        // Delete Price List Lines
        $pricelist->pricelistlines()->delete();


        $params = [
                    'price_list_id' => $pricelist->id, 
                    'name' => $name, 
                    'decimal_places' => $decimal_places, 
                    'simulate' => $request->input('simulate', 0),
        ];


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se ha cargado la Tarifa :name desde el Fichero: <strong>:file</strong> .', ['name' => $name, 'file' => $file]));
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

            $price_list_id = intval($params['price_list_id']);
            $pricelist = $this->pricelist->findOrFail($price_list_id);

            $name = $params['name'];
            $decimal_places = $params['decimal_places'];

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

                    // Need rounding?
                    if ( $decimal_places >= 0 ) {
                        $data['price'] = round($data['price'], $decimal_places);
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

                $pricelist->update(['last_imported_at' => \Carbon\Carbon::now()]);


            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Tarifa en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Líneas de Tarifa.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Líneas de Tarifa.', ['i' => $i]);

// Process reader ENDS

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
        $headers = [ 'product_id', 'reference', 'NOMBRE', 'price', 'price_list_id' ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the $data array.
        foreach ($lines as $line) {
            // $data[] = $line->toArray();
            $data[] = [
                            'product_id' => $line->product_id,
                            'reference' => $line->reference,
                            'NOMBRE' => $line->name,
                            'price' => $line->price,
                            'price_list_id' => $id,
            ];
        }

        $styles = [];

        $sheetTitle = $pricelist->price_is_tax_inc > 0 ?
                    'Precio incluye IVA' :
                    'Precio es SIN IVA' ;

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

//        $sheetFileName = $sheetTitle;
        $sheetFileName = 'Price_List_'.$id;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}