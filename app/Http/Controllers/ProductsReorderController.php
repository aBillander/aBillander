<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

use App\Product;

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
     * Show something useful.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// https://laracasts.com/discuss/channels/eloquent/order-by-the-difference-of-two-columns-in-laravel-53
		
		// $category_id = $request->input('category_id', 0);
		// $category = 

        $products = $this->product
                              ->isActive()
                              ->filter( $request->all() )
                              ->with('measureunit')
//                              ->with('combinations')                                  
//                              ->with('category')
//                              ->with('tax')
//                            ->orderBy('position', 'asc')
//                            ->orderBy('name', 'asc')
                              ->orderByRaw('(quantity_onhand - reorder_point) asc')
                              ->orderBy('reference', 'asc')
                              ;
        
        $products = $products->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($products, true);

        $products->setPath('products');     // Customize the URI used by the paginator

        // $categoryList = ;		<= See ViewComposerServiceProvider

        $product_procurementtypeList = Product::getProcurementTypeList();

        $product_mrptypeList = Product::getMrpTypeList();

        return view('products_reorder.index', compact('products', 'product_procurementtypeList', 'product_mrptypeList'));
    }
}
