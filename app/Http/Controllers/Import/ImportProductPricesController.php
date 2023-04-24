<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Product;
use App\Models\Tax;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;

class ImportProductPricesController extends Controller
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
        return view('imports.product_prices');
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
        $logger = ActivityLogger::setup( 'Import Product Prices', __METHOD__ )
                    ->backTo( route('products.prices.import') );


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán los Precios de los Productos desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $params = ['simulate' => $request->input('simulate', 0)];


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado los Precios de los Productos desde el Fichero: <strong>:file</strong> .', ['file' => $file]));
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

                        
                Product::unguard();      

                foreach($reader as $row)
                {
                    // do stuff
                    // if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    $item = '[<span class="log-showoff-format">'.($data['reference'] ?? $data['id'] ?? '').'</span>] <span class="log-showoff-format">'.( $data['price_tax_inc'] ?? '').' - '.($data['cost_price'] ?? '').' - '.($data['price'] ?? '').'</span>';

                    // Some Poor Man checks:
                    $data['id'] = intval(trim( $data['id'] ?? 0 ));
                    $data['reference'] = trim( $data['reference'] ?? '' );

                    // Product
                    $product = null;

                    if ( array_key_exists('id', $data) && ($data['id'] > 0) )
                        $product = $this->product->where('id', $data['id'])->first();
                    else
                    if ($data['reference'])
                        $product = $this->product->with('tax')->where('reference', $data['reference'])->first();
                    
                    if ( ! $product )
                    {
                        $logger->log("ERROR", "El Producto ".$item." no se actualizará porque no se ha encontrado.");

                        continue;
                    }

                    // Tax
                    $data['tax_id'] = intval( $data['tax_id'] ?? 0 );
                    if ($data['tax_id']>0)
                    {
                        if ( ! Tax::where('id', $data['tax_id'])->exists() )
                        {
                            $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'tax_id' es inválido: " . ($data['tax_id'] ?? '') . " . El Impuesto no existe.");
    
                            unset($data['tax_id']);
                        }
                        if ( $data['tax_id'] != $product->tax_id )
                        {
                            $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'tax_id' " . ($data['tax_id'] ?? '') . ' no se corresponde con el Producto: ' . $product->tax_id);
    
                            // Commenting this, you may change Productc Tax
                            // unset($data['tax_id']);
                        }
                    } else {
                        // Invalid, for sure!
                        $logger->log("ERROR", "Producto ".$item.":<br />" . "El campo 'tax_id' es inválido: " . ($data['tax_id'] ?? ''));
                        
                        unset($data['tax_id']);
                    }

                    if ( !($data['tax_id'] ?? 0) )
                    {
                        $logger->log("ERROR", "El Producto ".$item." no se actualizará porque hay errores.");

                        continue;                    
                    }

                    if ( $product )

                    try{
                        
                        if ( !($params['simulate'] > 0) ) 
                        {
                            // Update Product
                            // $product = $this->product->create( $data );
                            // $product = $this->product->updateOrCreate( [ 'reference' => $data['reference'] ], $data );


                            $data1 = [];

                            if (array_key_exists('tax_id', $data))
                            {
                                // 
                                $data1['tax_id']        = $data['tax_id'] ?? $product->tax_id;
                                $data1['price_tax_inc'] = trim($data['price_tax_inc']) == '' ? $product->price_tax_inc : (float) $data['price_tax_inc'];
                                $data1['price']         = trim($data['price'])         == '' ? $product->price         : (float) $data['price'];

                                $data1['recommended_retail_price_tax_inc'] = 
                                    trim($data['recommended_retail_price_tax_inc']) == '' ? $product->recommended_retail_price_tax_inc : (float) $data['recommended_retail_price_tax_inc'];
                                
                                $data1['recommended_retail_price'] = 
                                    trim($data['recommended_retail_price']) == '' ? $product->recommended_retail_price : (float) $data['recommended_retail_price'];
                            }

                            if (array_key_exists('cost_price', $data))
                            {
                                // 
                                $data1['cost_price']    = trim($data['cost_price']) == '' ? $product->cost_price : (float) $data['cost_price'];
                            }

                            if (array_key_exists('cost_average', $data))
                            {
                                // 
                                $data1['cost_average']    = trim($data['cost_average']) == '' ? $product->cost_average : (float) $data['cost_average'];
                            }

                            if (array_key_exists('last_purchase_price', $data))
                            {
                                // 
                                $data1['last_purchase_price']    = trim($data['last_purchase_price']) == '' ? $product->last_purchase_price : (float) $data['last_purchase_price'];
                            }                            

                            $product->update( $data1 );
                            
                        }

                        $i_ok++;

                    }
                    catch(\Exception $e){

//                            $item = '[<span class="log-showoff-format">'.$data['reference'].'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar el Producto ".$item.":<br />" . $e->getMessage());

                    }
                        

                    $i++;

                }   // End foreach

                Product::reguard();

            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Productos en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Productos.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Productos.', ['i' => $i]);

// Process reader ENDS

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export()
    {
        $products = $this->product
                          ->with('tax')
                          ->orderBy('reference', 'asc')
                          ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'reference', 'NAME', 'price_tax_inc', 'price', 'tax_id', 'TAX_NAME', 'cost_price', 'cost_average', 'last_purchase_price', 'recommended_retail_price_tax_inc', 'recommended_retail_price'
        ];

        $float_headers = [ 'price_tax_inc', 'price', 'cost_price', 'cost_average', 'last_purchase_price', 'recommended_retail_price_tax_inc', 'recommended_retail_price'
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
            $row['NAME']     = $product->name;
            $row['TAX_NAME'] = $product->tax ? $product->tax->name : '';

            // $row['price_tax_inc']     = str_replace('.', ',', (string) $product->price_tax_inc);

            $data[] = $row;
        }

        $styles = [];

        $sheetTitle = 'Product Prices';

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}