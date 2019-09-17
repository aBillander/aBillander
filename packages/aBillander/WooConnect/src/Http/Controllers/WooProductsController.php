<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \App\Product;
use \aBillander\WooConnect\WooProduct;

class WooProductsController extends Controller 
{


   protected $product;

   public function __construct(WooProduct $product)
   {
         $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 * GET /wproducts
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
	    	'orderby' => 'id',		// Options: date, id, include, title and slug. Default is date.
	    	'order'   => 'asc',		// Options: asc and desc. Default is desc.
		];

		// status : any, draft, pending, private and publish. Default is any.
		// type : simple, grouped, external and variable.

		foreach ($columns as $column) {
			if (request()->has($column) && request($column)) {

				$params[$column] = request($column);
			}
		}


        try {

			$results = WooCommerce::get('products', $params);

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

		$products = collect($results);

		// Allready imported? Let's see deeply
		$first = $products->first()["id"];
		$last  = $products->last()["id"];


		$products = new LengthAwarePaginator($products, $total, $perPage, $page, ['path' => $request->url(), 'query' => $query]);

        return view('woo_connect::woo_products.index', compact('products', 'query'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /wproducts/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /wproducts
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /wproducts/{id}
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
	 * GET /wproducts/{id}/edit
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
	 * PUT /wproducts/{id}
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
	 * DELETE /wproducts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}


/* ********************************************************************************************* */   


	
	public function importProductList( $list )
	{
        // 
	}

	public function importProducts( Request $request )
	{

        return $this->importProductList( $request->input('wproducts', []) );
	} 


	public function import($id)
	{
		
        return $this->importProductList( [$id] );
	}


	// Fetch Product by SKU
	public function fetch($id)
	{
		$product = WooProduct::fetch( $id );

		if ( !$product )
			$product = WooProduct::fetchById( $id );

		abi_r($product, true);
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


	public function importProductDescriptions()
	{
		// Route::get('wproducts/importProductDescriptions'
		// <url>/wwoc/wproducts/importProductDescriptions

		// En el Servidor se recuperaron las imagnes de la WooTienda, pero dió un gateway timeout => ¿Se podría hacer por partes (chuncks) para que no pase esto?

		// abi_r('ok');

		// Products
		// $list = Product::select('id', 'reference', 'name')->where('reference', '!=', '')->get();
		$list = Product::select('id', 'reference', 'name')->where('procurement_type', 'manufacture')->where('reference', '<>', '')->orderBy('reference', 'asc')->get();		// ->where('reference', '!=', '');	// ->pluck('reference');

		$nbr_p = $list->count(); abi_r('> '.$nbr_p);
		$i=0;

		foreach( $list as $p )
		{
			//
			$sku = $p->reference;

			$wp = WooProduct::fetch( $sku );

			// if ($wp) abi_r($sku);

			if ( $wp )
			{

				$p->update([
							'description' => $wp['description'],
							'description_short' => $wp['short_description'],
					]);

			}
			$i++;

			abi_r($i.' - '.$sku.' - '.(!empty($wp) ? 'ok' : ''));

			// abi_r($sku.' :: '.$img_src);
		}

		die();

        return redirect('products')
                ->with('success', l('Some Product Descriptions has been retrieved from WooCommerce Shop.'));
	}

}


/* ********************************************************************************************* */
