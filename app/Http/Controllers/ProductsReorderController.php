<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

use App\Product;
use App\Category;
use App\Supplier;
use App\WorkCenter;

use Excel;

use App\Traits\DateFormFormatterTrait;

class ProductsReorderController extends Controller
{

   use DateFormFormatterTrait;


   protected $product;

   public function __construct(Product $product)
   {
        $this->product = $product;
   }
   
    /**
     * Display a listing of the resource.
     *
     */

    public function indexQueryRaw(Request $request)
    {
        return $this->product
                              ->isActive()
                              ->filter( $request->all() )
                              ->with('measureunit')
//                              ->with('mainsupplier')
//                              ->with('combinations')                                  
//                              ->with('category')
//                              ->with('tax')
//                            ->orderBy('position', 'asc')
//                            ->orderBy('name', 'asc')
//                              ->orderByRaw('(quantity_onhand - reorder_point) asc')
                              ->orderBy('work_center_id', 'asc')
                              ->orderBy('reference', 'asc')
                              ;
    }

    /**
     * Show something useful.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// https://laracasts.com/discuss/channels/eloquent/order-by-the-difference-of-two-columns-in-laravel-53
		
		// $category_id = $request->input('category_id', 0);
		// $category = 

        // Set default query
        if ( ! $request->has('search_status') )
        {
            // return redirect()->route('products.reorder.index', [
            //     'search_status' => 1,
            //     'mrp_type'      => 'reorder', // 'onorder',
            // ]);

            $request->merge([
                'search_status' => 1,
                'search_query'  => 0,
                'items_per_page' => 25,
            ]);

            $products = collect([]);
        } else {

            $items_per_page = $request->input('items_per_page', Configuration::get('DEF_ITEMS_PERPAGE'));
            if ($items_per_page <= 0)
            {
                    $items_per_page = Configuration::get('DEF_ITEMS_PERPAGE');
                    // Lots of duplicated queries, so let's stay short:
                    $items_perpage = 25;
            }
            
            $products = $this->indexQueryRaw( $request );

            // abi_toSQL($products);die();
            
            $products = $products->paginate( $items_per_page );

            $products->setPath('reorder');     // Customize the URI used by the paginator
        }

        $supplierList = Supplier::select('id', \DB::raw("concat('[', id, '] ', name_fiscal) as full_name"))->pluck('full_name', 'id')->toArray();

        // abi_r($supplierList);die();

        // abi_r($products, true);

        // $categoryList = ;		<= See ViewComposerServiceProvider

        $product_procurementtypeList = Product::getProcurementTypeList();

        $product_mrptypeList = Product::getMrpTypeList();

        $work_centerList = WorkCenter::pluck('name', 'id')->toArray();

        return view('products_reorder.index', compact('products', 'product_procurementtypeList', 'product_mrptypeList', 'supplierList', 'work_centerList'));

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(Request $request)
    {
        $category_id = $request->input('category_id', 0);
        $procurement_type = $request->input('procurement_type', null);
        $mrp_type = $request->input('mrp_type', null);
        $stock_control = $request->input('stock_control', -1);
        $work_center_id = $request->input('work_center_id', -1);

        $products = $this->indexQueryRaw( $request )->get();

        $supplierList = Supplier::select('id', \DB::raw("concat('[', id, '] ', name_fiscal) as full_name"))->pluck('full_name', 'id')->toArray();

        $work_centerList = WorkCenter::pluck('name', 'id')->toArray();

        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $ribbon1 = $category_id > 0 ? optional(Category::find($category_id))->name : 'todos';
        $ribbon2 = $procurement_type == '' ? 'todos' : $procurement_type;
        $ribbon3 = $mrp_type == '' ? 'todos' : $mrp_type;
        $ribbon4 = $stock_control < 0 ? 'todos' : ($stock_control == 1 ? 'Sí' : 'No');
        $ribbon5 = $work_center_id < 0 ? 'todos' : $work_centerList[ $work_center_id ];
        // $ribbon6 = $work_center_id < 0 ? '' : ' :: ' . $work_centerList[ $work_center_id ];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Re-Aprovisionamiento de Productos', '', '', '', '', '', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Centro de Trabajo: '.$ribbon5];
        $data[] = ['Categorías: '.$ribbon1];
        $data[] = ['Aprovisionamiento: '.$ribbon2];
        $data[] = ['Planificación: '.$ribbon3];
        $data[] = ['Control de Stock: '.$ribbon4];

        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = [

                        l('Centro de Trabajo'), l('Reference'), l('Product Name'), l('Main Supplier'), l('Procurement type'), l('MRP type'), l('Stock Control'),

                        l('Stock'), l('Allocated'), l('On Order'), l('Available'), 
                        l('Re-Order Point'), l('Maximum stock'), l('Suggested Quantity'), l('Measure Unit'),

        ];

        $data[] = $header_names;

        if ( $products->count() > 0 )
        foreach ($products as $product) 
        {
                $supplier_label = '';
                if ( ($product->procurement_type == 'purchase') && ($product->main_supplier_id > 0) )
                    $supplier_label = $supplierList[ $product->main_supplier_id ] ?? '-';

                $row = [];
                $row[] = (string) array_key_exists($product->work_center_id, $work_centerList) ? $work_centerList[ $product->work_center_id ] : '-';
                $row[] = (string) $product->reference;
                $row[] = $product->name;
                $row[] = $supplier_label;
                $row[] = $product->procurement_type;
                $row[] = $product->mrp_type;
                $row[] = (string) $product->stock_control;

                $row[] = $product->quantity_onhand *1.0;
                $row[] = (float) $product->quantity_allocated;
                $row[] = $product->quantity_onorder *1.0;
                $row[] = $product->quantity_available *1.0;

                $row[] = $product->reorder_point * 1.0;
                $row[] = $product->maximum_stock * 1.0;
                $row[] = (float) $product->quantity_reorder_suggested;
                $row[] = $product->measureunit->sign;
    
                $data[] = $row;

        }

        $sheetName = 'Re-Aprovisionamiento';

        // Generate and return the spreadsheet
        Excel::create('Re-Aprovisionamiento de Productos', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');

                $sheet->getStyle('A2:A2')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->getStyle('A3:A3')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->getStyle('A9:O9')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
//                    'C' => '0.00',
                    'C' => '0',
                    'D' => '0.00',
                    'E' => '0.00',
                    'F' => '0.00',
                    'G' => '0.00',

                ));

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));


        abi_r($data);

    }
}
