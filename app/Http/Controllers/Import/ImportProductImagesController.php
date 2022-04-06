<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Image;
use App\Models\Product;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
    public static $table = 'images';

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

        $this->images_folder = public_path( abi_tenant_local_path( 'products/images/' ) );
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
    }

    public function process(Request $request)
    {
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
        $logger = ActivityLogger::setup( 'Import Product Images', __METHOD__ )
                    ->backTo( route('products.images.import') );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán las Imágenes de Productos usando el Fichero: <br /><span class="log-showoff-format">{file}</span>  <br /> Desde la Carpeta: <br /><span class="log-showoff-format">{images_folder}</span> .', ['file' => $file, 'images_folder' => $path]);



        $params = [
            'images_folder'  => $path, 
            'replace_images' => $request->input('replace_images', 0)
        ];


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
        // $reader = Excel::toArray(new CategoriesImport( $logger ), $file);
        // Excel::import(new CategoriesImport( $logger )), $file);

        // Get data as an array
        $worksheet = Excel::toCollection(new ArrayImport, $file);

        // abi_r($worksheet->first(), true);

        $reader = $worksheet->first();    // First sheet in worksheet


// Process reader STARTS

            $i = 0;
            $i_ok = 0;
            $max_id = 500;


            if(!empty($reader) && $reader->count()) {
                        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    $item = '[<span class="log-showoff-format">'.($data['id'] ?? $data['reference'] ?? '').'</span>] <span class="log-showoff-format">'.$data['image_file_name'].'</span>';

                    // Some Poor Man checks:
                    $image_file_name = trim( $data['image_file_name'] );

                    // Get Product
                    $product = null;
                    if ( array_key_exists('product_id', $data) && ($product_id=trim($data['product_id'])) ) {

                        $product = $this->product->withCount('images')->find($product_id);

                    } else

                    if ( array_key_exists('product_reference', $data) && ($reference=trim($data['product_reference'])) ) {

                        $product = $this->product->withCount('images')->where('reference', $reference)->first();
                    }



                    if ($image_file_name && $product)

                    try{
                        

                    // Load image...
                        try {

                            $img_path = $this->images_folder.$image_file_name;

                            if ( $params['replace_images'] )
                            {
                                # code...
                                foreach ($product->images as $image) {
                                    # code...

                                    // Delete file images
                                    $image->deleteImage();

                                    // Delete now!
                                    $image->delete();
                                }

                                $logger->log('INFO', 'Se han borrado {i} imágenes del Producto [:codart] (:reference) :desart .', ['i' => $product->images_count, 'codart' => $product->id, 'reference' => $product->reference, 'desart' => $product->name]);

                                $product->images_count = 0;
                            }

                            // Is featured?
                            if ( array_key_exists('image_is_featured', $data ) )
                                $is_featured = $product->images_count > 0 ? (int) $data['image_is_featured'] : 1;
                            else
                            if ( $product->images_count == 0 )
                                $is_featured = 1;
                            else
                                $is_featured = 0;

                            $image = Image::createForProductFromPath($img_path, ['caption' => $data['image_caption'] ??$product->name, 'position' => (int) $data['image_position'], 'is_featured'=> $is_featured]);
                            
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

// Process reader ENDS

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export()
    {
        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [
                'product_id', 'product_reference', 'NAME', 'image_file_name', 'image_caption', 'image_position', 'image_is_featured'
        ];

        $data[] = $headers;


        $styles = [];

        $sheetTitle = 'Product Images';

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

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