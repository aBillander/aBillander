<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Ecotax;
use App\Models\Product;
use App\Models\Tax;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;

class ImportProductsController extends Controller
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
        $logger = ActivityLogger::setup( 'Import Products', __METHOD__ )
                    ->backTo( route('products.import') );


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Productos desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $simulate = (int) $request->input('simulate', 0);
        $truncate = (int) $request->input('truncate', 0);
        $action_create = (int) $request->input('import_action', 0);
        $action_update = $action_create > 0 ? 0 : 1;

        $params = ['simulate' => $simulate, 'file' => $file];

        // Truncate table
        if ( $action_create > 0 )
        if ( $truncate      > 0 ) {

            $nbr = Product::count();

            if ( $simulate > 0 ) {

                $logger->log("INFO", "NO se han borrado los Productos antes de la Importación (Modo SIMULACION). En total {nbr} Productos.", ['nbr' => $nbr]);
            } else {
                
                Product::truncate();

                $logger->log("INFO", "Se han borrado todos los Productos antes de la Importación. En total {nbr} Productos.", ['nbr' => $nbr]);
            }
        }

        if ( $action_update > 0 )
        if ( $truncate      > 0 ) {

            $logger->log("WARNING", "NO es posible borrar los Productos antes de la Importación (Acción: ACTUALIZAR PRODUCTOS).");
        }


        try{
            
            if ( $action_create > 0 )
                $this->processFile(       $request->file('data_file'), $logger, $params );
            else
                $this->processFileUpdate( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado los Productos desde el Fichero: <strong>:file</strong> .', ['file' => $file]));
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

                        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    $item = '[<span class="log-showoff-format">'.($data['reference'] ?? $data['id'] ?? '').'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                    // Some Poor Man checks:
                    $data['reference'] = trim( $data['reference'] );

//                    $data['quantity_decimal_places'] = intval( $data['quantity_decimal_places'] );

                    $data['manufacturing_batch_size'] = intval( $data['manufacturing_batch_size'] );
                    if ( $data['manufacturing_batch_size'] <= 0 ) $data['manufacturing_batch_size'] = 1;

                    $data['measure_unit_id'] = intval( $data['measure_unit_id'] );
                    if ( $data['measure_unit_id'] <= 0 ) $data['measure_unit_id'] = Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS');

                    $data['purchase_measure_unit_id'] = intval( $data['purchase_measure_unit_id'] );
                    if ( $data['measure_unit_id'] <= 0 ) $data['measure_unit_id'] = $data['measure_unit_id'];

                    $data['work_center_id'] = intval( $data['work_center_id'] );
                    if ( $data['work_center_id'] <= 0 ) $data['work_center_id'] = NULL;

                    $data['main_supplier_id'] = intval( $data['main_supplier_id'] );
                    if ( $data['main_supplier_id'] <= 0 ) $data['main_supplier_id'] = NULL;

                    // Category
                    if ( ! Category::where('id', $data['category_id'])->exists() )
                        $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'category_id' es inválido: " . ($data['category_id'] ?? ''));

                    // Tax
                    $data['tax_id'] = intval( $data['tax_id'] );
                    if ( ! Tax::where('id', $data['tax_id'])->exists() )
                        $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'tax_id' es inválido: " . ($data['tax_id'] ?? ''));

                    // Ecotax
                    $data['ecotax_id'] = intval( $data['ecotax_id'] );
                    if ( $data['ecotax_id'] > 0 )
                        if ( ! Ecotax::where('id', $data['ecotax_id'])->exists() )
                            $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'ecotax_id' es inválido: " . ($data['ecotax_id'] ?? ''));
                    else
                        unset($data['ecotax_id']);

                    // Check E13
                    $data['ean13'] = trim( $data['ean13'] );
                    // Should be unique? => check your spreadsheet
                    if ( $data['ean13'] && Product::where('ean13', $data['ean13'])->exists() )
                        $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'ean13' ya existe: " . ($data['ean13'] ?? ''));

                    // Prices
                    $data['cost_price'] = (float) $data['cost_price'];
                    $data['price'] = (float) $data['price'];
                    $data['price_tax_inc'] = (float) $data['price_tax_inc'];
                    $data['recommended_retail_price'] = (float) $data['recommended_retail_price'];
                    $data['recommended_retail_price_tax_inc'] = (float) $data['recommended_retail_price_tax_inc'];


                    // handy conversions:
                    $data['reorder_point'] = (float) $data['reorder_point'];
                    $data['maximum_stock'] = (float) $data['maximum_stock'];

                    $data['lot_tracking'] = (int) $data['lot_tracking'];
                    $data['blocked'] = (int) $data['blocked'];
                    $data['supply_lead_time'] = (int) $data['supply_lead_time'];
                    $data['publish_to_web'] = (int) $data['publish_to_web'];
                    $data['active'] = (int) $data['active'];
                    $data['position'] = (int) $data['position'];

                    $data['lot_number_generator'] = $data['lot_number_generator'] ? $data['lot_number_generator'] : 'Default';
                    $data['lot_policy'] = $data['lot_policy'] ? strtoupper($data['lot_policy']) : 'FIFO';

                    // Check dates?
                    // Do not be lazy, man...



                    try{
                        
                        if ( !($params['simulate'] > 0) ) 
                        {
                            // Create Product
                            // $product = $this->product->create( $data );
                            $product = $this->product->updateOrCreate( [ 'reference' => $data['reference'] ], $data );

                            if ( $product->price_tax_inc == 0.0 )
                            {
                                $product->price_tax_inc = $product->price * ( 1.0 + $product->tax->percent / 100.0 );
                                $product->save();
                            }

                            if ( $product->price == 0.0 )
                            {
                                $product->price = $product->price_tax_inc / ( 1.0 + $product->tax->percent / 100.0 );
                                $product->save();
                            }
                        }

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

// Process reader ENDS

    }


    /**
     * Process a file of the resource (4 Update!).
     *
     * @return 
     */
    protected function processFileUpdate( $file, $logger, $params )
    {

        $logger->log("INFO", 'Se actualizarán los Productos según las columnas que se encuentren en el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $params['file']]);

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

                        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    // $item = implode(', ', $data);
                    $item = http_build_query($data, null, ', ');

                    // Let's see which Product
                    if ( array_key_exists('id', $data) && ( (int) $data['id'] > 0) )
                    {
                        $key_name = 'id';
                        $key_val = (int) $data['id'];

                        unset( $data['id'] );
                        // Uncomment lines to prevent changes in reference value
                        // if ( array_key_exists('reference', $data) )
                        //     unset( $data['reference'] );
                        
                    } else

                    if ( array_key_exists('reference', $data) )
                    {
                        $key_name = 'reference';
                        $key_val = trim( $data['reference'] );

                        unset( $data['reference'] );
                        
                    } else {

                        $logger->log("ERROR", "La fila (".($i+2).") :<br />".$item."<br /> No tiene un índice válido.");

                        continue ;
                    }



                    // $item = '[<span class="log-showoff-format">'.($data['reference'] ?? $data['id'] ?? '').'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';


                    // Cow boy style: avoid data validation and save precious time  ;) ;)  Yeha!


                    try{
                        // Update Product
                        // abi_toSql($this->product->where( $key_name, $key_val )); die();
                        $product = $this->product->where( $key_name, $key_val )->first();

                        if ( !$product )
                        {
                            $logger->log("ERROR", "La fila (".($i+2).") :<br />".$item."<br /> No se encuentra ningún Producto en la Base de Datos.");

                            continue ;
                        }
                        
                        if ( !($params['simulate'] > 0) ) 
                        {

                            $product->update( $data );  // $data array does not contain indexes (id or reference)
                        }

                        $i_ok++;

                    }
                    catch(\Exception $e){

                            // $item = '[<span class="log-showoff-format">'.$data['reference'].'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar la fila (".($i+2).") :<br />".$item.":<br />" . $e->getMessage());

                    }

                    $i++;

                }   // End foreach

            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Productos en el fichero.');
            }

            $logger->log('INFO', 'Se han actualizado {i} Productos.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Productos.', ['i' => $i]);

// Process reader ENDS

    }




    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(Request $request)
    {
        $products = $this->product
                          ->filter( $request->all() )
                          ->with('measureunit')
//                          ->with('combinations')                                  
                          ->with('category')
                          ->with('tax')
                          ->with('ecotax')
                          ->with('supplier')
                          ->with('manufacturer')
                          ->orderBy('reference', 'asc')
                          ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'reference', 'name', 'product_type', 'procurement_type', 'mrp_type', 'phantom_assembly', 'ean13', 'position', 'description', 'description_short', 'category_id', 'CATEGORY_NAME', 'manufacturing_batch_size', 'price_tax_inc', 'price', 'tax_id', 'TAX_NAME', 'ecotax_id', 'ECOTAX_NAME', 'cost_price', 'cost_average', 'last_purchase_price', 'recommended_retail_price', 'recommended_retail_price_tax_inc', 'available_for_sale_date', 'new_since_date', 'location', 'width', 'height', 'depth', 'volume', 'weight', 'notes', 'stock_control', 'reorder_point', 'maximum_stock', 'lot_tracking', 'expiry_time', 'lot_number_generator', 'lot_policy', 'out_of_stock', 'out_of_stock_text', 'publish_to_web', 'webshop_id', 'reference_external_wrin', 'blocked', 'active', 'measure_unit_id', 'MEASURE_UNIT_NAME', 'work_center_id', 'machine_capacity', 'units_per_tray', 'route_notes', 'main_supplier_id', 'SUPPLIER_NAME', 'supplier_reference', 'purchase_measure_unit_id', 'PURCHASE_MEASURE_UNIT_NAME', 'supply_lead_time', 'manufacturer_id', 'MANUFACTURER_NAME',

//             'last_purchase_price', 'cost_average', // <= Easter Eggs!!!
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
                if ( in_array($header, $float_headers) )
                    $row[$header] = (float) $product->{$header} ?? '';
                else
                    $row[$header] = $product->{$header} ?? '';
            }
            $row['CATEGORY_NAME']     = $product->category ? $product->category->name : '';
            $row['TAX_NAME']          = $product->tax ? $product->tax->name : '';
            $row['ECOTAX_NAME']          = $product->ecotax ? $product->ecotax->name : '';
            $row['MEASURE_UNIT_NAME'] = $product->measureunit ? $product->measureunit->name : '';
            $row['PURCHASE_MEASURE_UNIT_NAME'] = $product->purchasemeasureunit ? $product->purchasemeasureunit->name : '';
            $row['SUPPLIER_NAME']     = $product->supplier ? $product->supplier->name_fiscal : '';
            $row['MANUFACTURER_NAME']     = $product->manufacturer ? $product->manufacturer->name : '';

            $data[] = $row;
        }

        $styles = [];

        $sheetTitle = 'Products';

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}