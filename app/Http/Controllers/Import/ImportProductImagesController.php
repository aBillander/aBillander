<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Product;

use Excel;

class ImportProductImagesController extends Controller
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
   protected $image;

   protected $images_folder;

   public function __construct(Product $product)
   {
        $this->product = $product;

        $this->images_folder = public_path().'/uploads/products/images/';
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import()
    {
        $images_folder = $this->images_folder;

        return view('imports.product_images', compact('images_folder'));
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
        // return redirect()->back()                ->with('info', 'No se ha hecho nada.');

        // dd($request);

        $path  = $request->input('images_folder', '');
        if(!File::exists($path)) {
            // path does not exist
            $request->merge( ['images_folder' => NULL] );
        }
        
        $extra_data = [
            'extension' => ( $request->file('data_file') ? 
                             $request->file('data_file')->getClientOriginalExtension() : 
                             null 
                            ),
        ];

        $rules = [
                'data_file' => 'required | max:8000',
                'extension' => 'in:csv,xlsx,xls,ods', // all working except for ods
                'images_folder' => 'string',
        ];

        $this->validate($request->merge( $extra_data ), $rules);


        

        // Start Logger
        $logger = \App\ActivityLogger::setup( 'Import Product Images', __METHOD__ )
                    ->backTo( route('products.images.import') );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán las Imágenes de Productos usando el Fichero: <br /><span class="log-showoff-format">{file}</span>  <br /> Desde la Carpeta: <br /><span class="log-showoff-format">{images_folder}</span> .', ['file' => $file, 'images_folder' => $path]);



        $params = ['images_folder' => $path];


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado las Imágenes de Productos desde el Fichero: <strong>:file</strong> .', ['file' => $file]));
    }


    /**
     * Process a file of the resource.
     *
     * @return 
     */
    protected function processFile( $file, $logger, $params )
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

                    $item = '[<span class="log-showoff-format">'.($data['id'] ?? $data['reference'] ?? '').'</span>] <span class="log-showoff-format">'.$data['image_file_name'].'</span>';

                    // Some Poor Man checks:
                    $data['id'] = intval(trim( $data['id'] ));
                    $data['reference'] = trim( $data['reference'] );

                    $image_file_name = trim( $data['image_file_name'] );

                    $product = null;

                    if ($data['id'])
                        $product = $this->product->where('id', $data['id'])->withCount('images')->first();
                    else
                    if ($data['reference'])
                        $product = $this->product->where('reference', $data['reference'])->withCount('images')->first();



                    if ($image_file_name && $product)

                    try{
                        

                    // Load image...
                        try {

                            $img_path = $this->images_folder.$image_file_name;

                            // Is featured?
                            if ( ( $data['image_is_featured'] ?? 0 ) ) $is_featured = 1;
                            else
                            if ( $product->images_count == 0 ) $is_featured = 1;
                            else
                            $is_featured = 0;

                            $image = \App\Image::createForProductFromPath($img_path, ['caption' => $product->name, 'is_featured'=> $is_featured]);
                            
                            if ( $image )
                            {
                                $product->images()->save($image);

                                if ( ($product->images_count > 0) && $image->is_featured )
                                    $product->setFeaturedImage( $image );
                            }
                            else
                                $logger->log("ERROR", 'El Producto [:codart] (:reference) :desart NO se ha podido cargar la Imagen (:img_path), porque: :img_error ', ['codart' => $product->id, 'reference' => $product->reference, 'desart' => $product->name, 'img_path' => $img_path, 'img_error' => 'No se encontró la imagen <span class="log-showoff-format">'.$image_file_name.'</span>']);
                            
                        } catch (\Exception $e) {

                            $logger->log("ERROR", 'El Producto [:codart] (:reference) :desart NO se ha podido cargar la Imagen (:img_path), porque: :img_error ', ['codart' => $product->id, 'reference' => $product->reference, 'desart' => $product->name, 'img_path' => $img_path, 'img_error' => $e->getMessage()]);                        
                        }


                        $i_ok++;

                        $logger->log('INFO', 'Se han creado / actualizado la Fila {i} [:codart] (:reference) :desart .', ['i' => $i_ok, 'codart' => $product->id, 'reference' => $product->reference, 'desart' => $product->name]);

                    }
                    catch(\Exception $e){

//                            $item = '[<span class="log-showoff-format">'.$data['reference'].'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar el Producto ".$item.":<br />" . $e->getMessage());

                    }

                    else
                        $logger->log("ERROR", "No se ha encontrado el Producto ".$item." y la imagen no se ha añadido.");

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


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export()
    {
/*        $products = $this->product
                          ->with('measureunit')
//                          ->with('combinations')                                  
                          ->with('category')
                          ->with('tax')
                          ->with('supplier')
                          ->orderBy('reference', 'asc')
                          ->get();

        $pricelist = $this->pricelist
                    ->with('pricelistlines')
                    ->with('pricelistlines.product')
                    ->findOrFail($id);

        $pricelist  = $this->pricelist->findOrFail($id);
        $lines = $this->pricelistline
                        ->select('price_list_lines.*', 'products.id', 'products.reference', 'products.name')
//                        ->with('product')
                        ->where('price_list_id', $id)
                        ->join('products', 'products.id', '=', 'price_list_lines.product_id')       // Get field to order by
                        ->orderBy('products.reference', 'asc')
                        ->get();
*/

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [
                'id', 'reference', 'NAME', 'image_file_name', 'image_is_featured'
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
/*        foreach ($products as $product) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $product->{$header} ?? '';
            }
            $row['CATEGORY_NAME']     = $product->category ? $product->category->name : '';
            $row['TAX_NAME']          = $product->tax ? $product->tax->name : '';
            $row['MEASURE_UNIT_NAME'] = $product->measureunit ? $product->measureunit->name : '';
            $row['SUPPLIER_NAME']     = $product->supplier ? $product->supplier->name : '';

            $data[] = $row;
        }
*/
        $sheetName = 'Product Images' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('ProductImages', function($excel) use ($sheetName, $data) {

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


    /**
     * Delete ALL Images.
     *
     * @return 
     */
    public function deleteAll()
    {
        $products = $this->product
                              ->has('images')
//                              ->where('id', 88)
                              ->get();

        # abi_r($products);
        $p = 0;
        $i = 0;

        foreach ($products as $product) {
            # code...
            foreach ($product->images as $image) {
                # code...

                // Delete file images
                $image->deleteImage();

                // Delete now!
                $image->delete();
                $i++;
            }
            $p++;
        }


        return redirect()->route('products.images.import')
                ->with('success', l('Se han borrado las Imágenes de Productos: <strong>:ni</strong> Imágen(es) de <strong>:np</strong> Producto(s).', ['np' => $p, 'ni' => $i]));
    }
}