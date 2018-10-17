<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Category;
use App\Product;

class AbccCatalogueController extends Controller {


   protected $category;
   protected $product;

   public function __construct(Category $category, Product $product)
   {
        $this->middleware('auth:customer');

        $this->category = $category;
        $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$category_id = $request->input('category_id', 0);
		// Not needed: $request->merge( ['category_id' => $category_id] );

		$parentId=0;
		$breadcrumb = [];

		$categories = $this->category
			->with('children')
//			->withCount('products')
			->where('parent_id', '=', intval($parentId))
			->orderBy('name', 'asc')->get();

		if ($category_id>0 && !$request->input('search_status', 0)) {
			//
			// abi_r($categories, true);

			$category = $categories->search(function ($item, $key) use ($category_id) {
			    
			    $cat = $item->children;

			    $c = $cat->search(function ($item, $key) use ($category_id) {
				    // abi_r($item->id.' - '.$category_id);

				    return $item->id == $category_id;
				});

			    // Found?
			    return $c !== false;
			});

			$parent = $categories->slice($category, 1)->first();
			$child = $parent->children->where('id', $category_id)->first();

			$breadcrumb = [$parent, $child];

			// abi_r($parent->name.' / '.$child->name, true);
		}

        $products = $this->product
                              ->filter( $request->all() )
 //                             ->with('measureunit')
							  ->with('combinations')                                  
 //                             ->with('category')
 //                             ->with('tax')
                              ->IsActive()
                              ->orderBy('reference', 'asc');

        $products = $products->paginate( 10 );	// \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $products->setPath('catalogue');     // Customize the URI used by the paginator
        
        return view('abcc.catalogue.index', compact('category_id', 'categories', 'products', 'breadcrumb'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request)
	{
		// Get logged in user

		return 'OK';

		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;
		
		$payment = null;
		
		return view('customer_vouchers.edit', compact('payment', 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
