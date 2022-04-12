<?php

namespace App\Http\Controllers;

use App\Helpers\Exports\ArrayExport;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Product;
use App\Models\Supplier;
use App\Traits\DateFormFormatterTrait;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsStockController extends Controller
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
        $min_stock = $request->input('min_stock', 0.0);

        return $this->product
                              ->isActive()
                              ->filter( $request->all() )
                              ->with('measureunit')
                              ->with('mainsupplier')
//                              ->with('combinations')                                  
//                              ->with('category')
//                              ->with('tax')
                              ->where('quantity_onhand', '<=', $min_stock)
//                            ->orderBy('position', 'asc')
//                            ->orderBy('name', 'asc')
//                              ->orderByRaw('(quantity_onhand - reorder_point) asc')
                              ->orderBy('quantity_onhand', 'asc')
                              ->orderBy('reference', 'desc')
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
                    // $items_perpage = 25;
            }
            
            $products = $this->indexQueryRaw( $request );
            
            $products = $products->paginate( $items_per_page );

            $products->setPath('level');     // Customize the URI used by the paginator
        }

        $supplierList = Supplier::select('id', \DB::raw("concat('[', id, '] ', name_fiscal) as full_name"))->pluck('full_name', 'id')->toArray();

        // abi_r($supplierList);die();

        // abi_r($products, true);

        // $categoryList = ;		<= See ViewComposerServiceProvider

        $product_procurementtypeList = Product::getProcurementTypeList();

        $product_mrptypeList = Product::getMrpTypeList();

        $categoryList = Category::selectorList();

        return view('products_stock.index', compact('products', 'product_procurementtypeList', 'product_mrptypeList', 'supplierList', 'categoryList'));

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
        $min_stock = $request->input('min_stock', 0);

        $products = $this->indexQueryRaw( $request )->get();

        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $ribbon1 = $category_id > 0 ? optional(Category::find($category_id))->name : 'todos';
        $ribbon2 = $procurement_type == '' ? 'todos' : $procurement_type;
        $ribbon3 = $mrp_type == '' ? 'todos' : $mrp_type;
        $ribbon4 = $stock_control < 0 ? 'todos' : ($stock_control == 1 ? 'Sí' : 'No');

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Productos sin Stock :: ', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Categorías: '.$ribbon1];
        $data[] = ['Aprovisionamiento: '.$ribbon2];
        $data[] = ['Planificación: '.$ribbon3];
        $data[] = ['Control de Stock: '.$ribbon4];
        $data[] = ['Stock Mínimo: '.$min_stock];

        $data[] = [''];


        // Define the Excel spreadsheet headers
/*
                        l('Reference'), l('Product Name'), l('Main Supplier'), l('Procurement type'), l('MRP type'), l('Stock Control'),

                        l('Stock'), l('Allocated'), l('On Order'), l('Available'), 
                        l('Re-Order Point'), l('Maximum stock'), l('Suggested Quantity'), l('Measure Unit'),

        ];
*/
        $header_names = [

                        l('Reference'), l('Product Name'), l('Main Supplier'), l('Procurement type'), l('MRP type'), l('Stock Control'),

                        l('Stock'), 
                        l('Re-Order Point'), l('Maximum stock'),  l('Measure Unit'),

        ];

        $data[] = $header_names;

        foreach ($products as $product) 
        {
                $supplier_label = '';
                if ( $product->procurement_type == 'purchase' && $product->mainsupplier )
                    $supplier_label = '['.$product->mainsupplier->id .'] '.$product->mainsupplier->name_fiscal;

                $row = [];
                $row[] = (string) $product->reference;
                $row[] = $product->name;
                $row[] = $supplier_label;
                $row[] = $product->procurement_type;
                $row[] = $product->mrp_type;
                $row[] = (string) $product->stock_control;

                $row[] = $product->quantity_onhand *1.0;
//                $row[] = (float) $product->quantity_allocated;
//                $row[] = $product->quantity_onorder *1.0;
//                $row[] = $product->quantity_available *1.0;

                $row[] = $product->reorder_point * 1.0;
                $row[] = $product->maximum_stock * 1.0;
//                $row[] = (float) $product->quantity_reorder_suggested;
                $row[] = $product->measureunit->sign;
    
                $data[] = $row;

        }


        $styles = [
            'A2:A2'    => ['font' => ['bold' => true]],
            'A9:N9'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:B1', 'A2:B2'];

        $sheetTitle = 'Productos sin Stock';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}
