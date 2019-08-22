<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \App\Category;
use \aBillander\WooConnect\WooCategory;

class WooCategoriesController extends Controller 
{


   protected $category;

   public function __construct(WooCategory $category, Category $abi_category)
   {
         $this->category = $category;
         $this->abi_category = $abi_category;
   }

	/**
	 * Display a listing of the resource.
	 * GET /wcategorys
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		// 
		$queries = [];
		$columns = ['after', 'before', 'status'];

		foreach ($columns as $column) {
			if (request()->has($column)) {

				$queries[$column] = request($column);
			}
		}

		$query = array_merge($request->query(), $queries);

		$page = Paginator::resolveCurrentPage();  // $request->input('page', 1); // Get the current page or default to 1
		$perPage = intval(\App\Configuration::get('WOOC_ORDERS_PER_PAGE'));	// Waiting for Configuration::get('WOOC_PRODUCTS_PER_PAGE') if needed
		if ($perPage<1) $perPage=10;
		$offset = ($page * $perPage) - $perPage;

		// https://stackoverflow.com/questions/39101445/how-to-sort-products-in-woocommerce-wordpress-json-api
		// https://www.storeurl.com/wc-api/v3/products?orderby=title&order=asc
		// https://www.storeurl.com/wc-api/v3/products?filter[order]=asc&filter[orderby]=meta_value_num&filter[orderby_meta_key]=_regular_price  =>  the "Filter" parameter, which allows you to use any WP_Query style arguments you may want to add to your request
		$params = [
		    'per_page' => $perPage,
		    'page' => $page,
//		    'status' => 'completed',
//	    	'after'  => '2017-08-01 00:00:00',		//  ISO8601 compliant date
//	    	'before' => '2017-12-31T23:59:59',
//	    	'after'  => $request->input('date_from', '') ? $request->input('date_from').' 00:00:00' : '',
//	    	'before' => $request->input('date_to', '')   ? $request->input('date_to')  .' 23:59:59' : '',
	    	'orderby' => 'name',		// Options: id, include, name, slug, term_group, description and count. Default is name. => seems that "name" order is by menu_order, in fact!
	    	'order'   => 'asc',		// Options: asc and desc. Default is desc.
		];

		// display : Category archive display type. Options: default, products, subcategories and both. Default is default.

		foreach ($columns as $column) {
			if (request()->has($column) && request($column)) {

				$params[$column] = request($column);
			}
		}


        try {

			$results = WooCommerce::get('products/categories', $params);

		}

			catch(WooHttpClientException $e) {

//			$e->getMessage(); // Error message.

//			$e->getRequest(); // Last request data.

//			$e->getResponse(); // Last response data.
//			abi_r($e->getResponse);

			$err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

			// Improbe this: 404 doesnot show upper-left logo , and language is English
			return redirect('404')
				->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

		}


		// abi_r($results, true);





		
		// So far so good, then
		$total = WooCommerce::totalResults();

		$categories = collect($results);

		// Allready imported? Let's see deeply
		$first = $categories->first()["id"];
		$last  = $categories->last()["id"];


		$categories = new LengthAwarePaginator($categories, $total, $perPage, $page, ['path' => $request->url(), 'query' => $query]);

		// Not so far, kawaii bunny
		$ids = $categories->pluck('id')->toArray();

		$abi_categories = $this->abi_category::whereIn('webshop_id', $ids)->get();

		$categories->getCollection()->transform(function ($category) use ($abi_categories) {
		    // Your code here
			$abi_category = $abi_categories->where('webshop_id', $category["id"])->first();

			$category["abi_category"] = $abi_category;

		    return $category;
		});

		// abi_r($categories);die();

        return view('woo_connect::woo_categories.index', compact('categories', 'abi_categories', 'query'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /wcategories/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /wcategories
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /wcategories/{id}
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
	 * GET /wcategories/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /wcategories/{id}
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
	 * DELETE /wcategories/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}


/* ********************************************************************************************* */   


	
	public function importCategoryList( $list )
	{
        // 
	}

	public function importCategories( Request $request )
	{

        return $this->importCategoryList( $request->input('wcategories', []) );
	} 


	public function import($id)
	{
		
        return $this->importCategoryList( [$id] );
	}


	public function fetch($id)
	{
		$category = WooCategory::fetch( $id );

		abi_r($category, true);
	}


	public function importProductImages()
	{
		// Route::get('wproducts/importProductImages'

		// En el Servidor se recuperaron las imagnes de la WooTienda, pero dió un gateway timeout => ¿Se podría hacer por partes (chuncks) para que no pase esto?

		// Products
		$list = Product::select('id', 'reference', 'name')->where('reference', '!=', '')->get();
		// $list = Product::select('id', 'reference', 'name')->where('reference', '4003')->get();		// ->where('reference', '!=', '');	// ->pluck('reference');

		foreach( $list as $p )
		{
			//
			$sku = $p->reference;

			$wp = WooProduct::fetch( $sku );

			// abi_r($wp, true);

			$images = $wp['images'] ?? [];

			if ( $images && count($images) )
			{
				// Initialize with something to show
				$img_src  = $images[0]['src']  ?? '';
				$img_name = $images[0]['name'] ?? '';
				$img_alt  = $images[0]['alt']  ?? '';

				foreach ($images as $image)
				{
					if ($image['position'] == 0)
					{
						$img_src  = $image['src'];
						$img_name = $image['name'];
						$img_alt  = $image['alt'];
						break;
					}
				}

				// Make the magic
				if( $img_src )
				{

			        $image = \App\Image::createForProductFromUrl($img_src, ['caption' => $p->name]);
					
			        $p->images()->save($image);

			        if ( $p->images()->count() == 1 )
			        	$p->setFeaturedImage( $image );

				}

			} else {

				$img_src = 'https://www.laextranatural.com/wp-content/plugins/woocommerce/assets/images/placeholder.png';
				
			}

			// abi_r($sku.' :: '.$img_src);
		}

		// die();

        return redirect('products')
                ->with('success', l('Some Product Images has been retrieved from WooCommerce Shop.'));
	}

}


/* ********************************************************************************************* */
