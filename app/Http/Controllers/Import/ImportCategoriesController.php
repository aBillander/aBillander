<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Category;
use App\Models\Configuration;
use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use Excel;
use Illuminate\Http\Request;

use App\Helpers\Imports\CategoriesImport;

class ImportCategoriesController extends Controller
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
    public static $table = 'categories';

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

   protected $category;

   public function __construct(Category $category)
   {
        $this->category = $category;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import()
    {
        return view('imports.categories');
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
        $logger = ActivityLogger::setup( 'Import Categories', __METHOD__ )
                    ->backTo( route('categories.import') );        // 'Import Categories :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán las Categorías desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $truncate = $request->input('truncate', 0);

        // Truncate table
        if ( $truncate > 0 ) {

            $nbr = Category::count();
            
            Category::truncate();

            $logger->log("INFO", "Se han borrado todos las Categorías antes de la Importación. En total {nbr} Categorías.", ['nbr' => $nbr]);
        }


        try{
            
            $this->processFile( $request->file('data_file'), $logger );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado las Categorías desde el Fichero: <strong>:file</strong> .', ['file' => $file]));
    }


    /**
     * Process a file of the resource.
     *
     * @return 
     */
    protected function processFile( $file, $logger )
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
            $max_id = 1000;


            if(!empty($reader) && $reader->count()) {

                        
                Category::unguard();      

                foreach($reader as $row)
                {
                    // do stuff
                    // if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    $item = '[<span class="log-showoff-format">'.($data['id'] ?? $data['reference_external'] ?? '').'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                    // Some Poor Man checks:
                    if ( array_key_exists('id', $data)) {
                        $data['id'] = intval( $data['id'] );
                        if ( $data['id'] <= 0 ) $data['id'] = NULL;
                    }

                    $data['publish_to_web'] = intval( $data['publish_to_web'] ) > 0 ? 1 : 0;

                    $data['webshop_id'] = $data['webshop_id'] ? $data['webshop_id'] : null;

                    $data['active'] = intval( $data['active'] ) > 0 ? 1 : 0;

                    $data['parent_id'] = intval( $data['parent_id'] );
                    if ( $data['parent_id'] <= 0 ) $data['parent_id'] = 0;

                    // Handy conversions
                    $data['position'] = (int) $data['position'];
                    

                    // Parent Category
                    if (0)      // Not useful code, I guess
                    if ( !array_key_exists('parent_id', $data) )
                    {
                        if (  array_key_exists('parent_REFERENCE_EXTERNAL', $data) ) 
                        {
                            if ( $category = Category::where('reference_external', $data['parent_REFERENCE_EXTERNAL'])->first())
                                $data['parent_id'] = $category_id;
                            else
                                $logger->log("ERROR", "Categoría ".$item.":<br />" . "El campo 'parent_REFERENCE_EXTERNAL' es inválido: " . ($data['parent_REFERENCE_EXTERNAL'] ?? ''));
                        }
                    } else {
                        if ( $category = Category::find($data['parent_id']))
                                ;
                            else{
                                $logger->log("ERROR", "Categoría ".$item.":<br />" . "El campo 'parent_id' es inválido: " . ($data['parent_id'] ?? ''));
                                $data['parent_id'] = null;
                            }
                    }


                    if ( Configuration::isFalse('ALLOW_PRODUCT_SUBCATEGORIES') && ( $data['parent_id'] > 0 ) )
                    {

                            $logger->log("ERROR", "La Categoría ".$item." no se cargará porque no está permitido el uso de Subcategorías.");

                            $i++;

                            continue;

                    }




                    try{
                        
                        // Create Category
                        // $category = $this->category->create( $data );
                        $category = $this->category->updateOrCreate( ['id' => $data['id']], $data );

                        $i_ok++;

                    }
                    catch(\Exception $e){

                            $item = '[<span class="log-showoff-format">'.($data['id'] ?? '').'</span>] <span class="log-showoff-format">'.$data['name'].'</span>';

                            $logger->log("ERROR", "Se ha producido un error al procesar la Categoría ".$item.":<br />" . $e->getMessage());

                    }

                    $i++;

                }   // End foreach

            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Categorías en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Categorías.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Categorías.', ['i' => $i]);

// Process reader ENDS

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export()
    {
        $categories = $this->category
                          ->orderBy('parent_id', 'asc')
                          ->orderBy('id', 'asc')
//                          ->orderBy('name', 'asc')
                          ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'name', 'description', 'position', 'publish_to_web', 'webshop_id', 'reference_external', 
                     'active', 'parent_id',
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the $data array.
        foreach ($categories as $category) {

            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $category->{$header} ?? '';
            }
//            $row['TAX_NAME']          = $category->tax ? $category->tax->name : '';

            $data[] = $row;
        }

        $styles = [];

        $sheetTitle = 'Categories';

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}