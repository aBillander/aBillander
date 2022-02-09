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

use \aBillander\WooConnect\WooProductImporter;

use \App\Configuration;
use \App\PriceList;

class WooProductsController extends Controller 
{


   protected $product;
   protected $abi_product;

   public function __construct(WooProduct $product, Product $abi_product)
   {
         $this->product = $product;
         $this->abi_product = $abi_product;
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

		// Let's hidrate a little bit
		$skus = $products->pluck('sku')->filter()->toArray();

		$abi_products = Product::select('id', 'reference')
							->whereIn('reference', $skus)
							->pluck('id', 'reference')
							->toArray();

		$abi_product_ids = [];

		foreach ($products as $product) {
			# code...
			$abi_product_ids[$product["sku"]] = 0;

			if ( array_key_exists($product["sku"], $abi_products))
			{
				$abi_product_ids[$product["sku"]] = $abi_products[$product["sku"]];
				// abi_r($product["abi_product_id"]);
			}
		}

		// abi_r($abi_products);die();

        return view('woo_connect::woo_products.index', compact('products', 'query', 'abi_product_ids'));
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
		$description_short = $request->input('description_short', '');

		$abi_product = $this->abi_product
							->with('category')
							->findOrFail($request->input('abi_product_id', 0));

		if ($description_short != '')
		{
			$abi_product->description_short = $description_short;
			$abi_product->save();
		}

		// abi_r($abi_product);die();

		$abi_product_price = $abi_product->getPriceByListId( Configuration::getInt('WOOC_DEF_CUSTOMER_PRICE_LIST') );	// Returns a Price Object

		$status = in_array(Configuration::get('WOOC_DEF_PRODUCT_STATUS'), WooProduct::$statuses) 
							? Configuration::get('WOOC_DEF_PRODUCT_STATUS')
							: 'publish';

		$manage_stock = Configuration::getInt('WOOC_DEF_MANAGE_STOCK') < 0
							? (boolean) $abi_product->stock_control
							: (boolean) Configuration::getInt('WOOC_DEF_MANAGE_STOCK');

		$stock = (int) $abi_product->quantity;
		// ^-- Maybe select quantity or quantity_onhand from Configuration. But quantity_onhand is not compatible with select stock from a specific warehouse!!!

		$stock_status = $manage_stock && ($stock <= 0.0)
							? 'outofstock'
							: 'instock';

		$regular_price = $abi_product_price->getPrice();

		$data = [
		    'name' => $abi_product->name,
//		    'slug' => '',
		    'type' => 'simple', 	// Product type. Options: simple, grouped, external and variable. Default is simple.
		    'status' => $status, 	// Product status (post status). Options: draft, pending, private and publish. Default is publish.
//		    'featured' => 			// Featured product. Default is false.
		    'catalog_visibility' => 'visible',		// Catalog visibility. Options: visible, catalog, search and hidden. Default is visible.
		    'description'   => $abi_product->description,
		    'short_description'   => $abi_product->description_short,

			'sku' => $abi_product->reference,
			'regular_price' => (string) $regular_price, // product price
//			'sale_price' 	string 	Product sale price.

//			'virtual',
//			'downloadable'

			'tax_status' => 'taxable',		// Tax status. Options: taxable, shipping and none. Default is taxable.
			'tax_class' => '',				// <= Default WooShop tax  // String
			'manage_stock' => $manage_stock,		// (boolean) $abi_product->stock_control,		// Stock management at product level. Default is false.
			'stock_quantity' => $stock,	// Integer
			'stock_status' => $stock_status,		// ($abi_product->stock_control && ($abi_product->quantity_onhand <= 0.0)) ? 'outofstock' : 'instock',		// Controls the stock status of the product. Options: instock, outofstock, onbackorder. Default is instock.

			'weight' => $abi_product->weight,
			'dimensions' => [
				[
					'length' => $abi_product->depth,
		            'width' => $abi_product->width,
		            'height' => $abi_product->height,
				],
			],

			'menu_order' => $abi_product->position,
/*
			'categories' => [
				[
					'id' => $abi_product->category->webshop_id // each category in a separate array
				],
			],

			'tags' => [
				[
					'id' => '' // each category in a separate array
				],
			],

			'images' => [
				[
					'src'      => 'https://shop.local/path/to/image.jpg',
					'name' => '',
					'alt' => '',
//					'position' => 0,	// First image is talen as "main Image"
				],
			],

			'meta_data' => [
				[
					'id' => '' // each category in a separate array
				],
			],
*/
		];

		// Taxes		
        // Dictionary
        $dic_taxes = json_decode(Configuration::get('WOOC_TAXES_DICTIONARY_CACHE'), true);
        // $dic_taxes = json_decode('{"standard":"1","reduced-rate":"2","r-e":"2"}', true);

        if ($dic_taxes)
        foreach ($dic_taxes as $key => $value) {
        	# code...
        	if ($key == 'standard') continue;		// Default tax

        	// $values are not always unique!!! => take first
        	if ($value == $abi_product->tax_id)
        	{
        		$data['tax_class'] = $key;
        		break ;
        	}
        }

        // abi_r($dic_taxes, true);


		// Categories
		if ( $abi_product->category->webshop_id > 0 )
			$data['categories'] = [
				[
					'id' => $abi_product->category->webshop_id // each category in a separate array
				],
			];

		// Images
		$abi_images = $abi_product->images->sortByDesc('is_featured');

if ( $abi_images->count() > 0 )
{		
		// Add featured image to Galery
		$abi_featured = $abi_images->first();
		$abi_images->push($abi_featured);

		$i=0;

		foreach ($abi_images as $abi_image) {
			# code...
			$src = \URL::to( \App\Image::pathProducts() . $abi_image->getImageFolder() . $abi_image->id . '.' . $abi_image->extension );
			// $src = 'http://abimfg.gmdistribuciones.es/tenants/abimfg/images_p/4/0/2/0/4020.JPG';
			$data['images'][] = 
				[
					'src'      => $src,
					// Avoid problems:
//					'name' => str_replace(' ', '%20', trim($abi_image->caption)),
//					'alt'  => str_replace(' ', '%20', trim($abi_image->caption)),
					'position' => $i,	// First image is taken as "main Image" ??? < Key not documented???
				];

			$i++;
		}
}



		// https://rudrastyh.com/woocommerce/rest-api-create-update-remove-products.html#remove_product
		// $result = WooCommerce::delete('products/categories/'.$abi_product->webshop_id, ['force' => true]);

		// abi_r($data);die();

		
            try {

                $result = WooCommerce::post('products', $data);

                $abi_product->update(['webshop_id' => $result['id']]);
                
            }

            catch( WooHttpClientException $e ) {

                /* * /
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                / * */

                $err = '<ul><li><strong>'.nl2p($e).'</strong></li></ul>';

				return redirect()->back()
					->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

                // abi_r($err);  die();

            }

				
		return redirect()->to( route('products.edit', [$abi_product->id]) . '#internet' )
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $abi_product->id], 'layouts') );
	}

	/**
	 * Display the specified resource.
	 * GET /wproducts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, Request $request)
	{
		$product = WooProduct::fetch( $id );

        if ($request->has('embed'))
        {
        	if($request->ajax()){

	            $woo_product_statusList = [];
		        foreach (WooProduct::$statuses as $value) {
		            // code...
		            $woo_product_statusList[$value] = $value;
		        }

	            $woo_product_stock_statusList = [];
		        foreach (WooProduct::$stock_statuses as $value) {
		            // code...
		            $woo_product_stock_statusList[$value] = $value;
		        }

	            $woo_product_catalog_visibilityList = [];
		        foreach (WooProduct::$catalog_visibility as $value) {
		            // code...
		            $woo_product_catalog_visibilityList[$value] = $value;
		        }

	            return response()->json( [
	                'success' => $product ? 'OK' : 'KO',
	                'msg' => 'OK'." $id ".($product['name'] ?? ''),
	                'html' => view('woo_connect::woo_products.show_embed', compact('product', 'woo_product_statusList', 'woo_product_stock_statusList', 'woo_product_catalog_visibilityList'))->render(),
	            ] );

	        }
        }
        else
        	return $this->fetch($id);	// To do: return data into proper view
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
		$product_sku = $id;

		// Get Woo Product by SKU
		$wproduct = WooProduct::fetch( $product_sku );

		// Oh! Second try:
		if ( !$wproduct )
			$wproduct = WooProduct::fetchById( $product_sku );

		if ( !$wproduct ) return ;

        $wproduct_id = $wproduct['id'];

        // Get Product
        $product = Product::where('reference', $wproduct['sku'])->first();

        // Happyly update WooCommerce Product data ;)
		$data = [
			'status'          => $request->status,
			'featured'        => (bool) $request->featured,
			'manage_stock'    => (bool) $request->manage_stock,
			'stock_status'    => $request->stock_status,
			'reviews_allowed' => (bool) $request->reviews_allowed,

			'stock_quantity'     => (int) $request->stock_quantity,
			'catalog_visibility' => $request->catalog_visibility,
		];

		// To do: catch errores
		WooCommerce::put('products/'.$wproduct_id, $data);


		return redirect()->to( route('products.edit', [$product->id]) . '#internet' )
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $product_sku], 'layouts'));
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
        // ProductionSheetsController
        if ( count( $list ) == 0 ) 
            return redirect()->route('wproducts.index')
                ->with('warning', l('No se ha seleccionado ningún Producto, y no se ha realizado ninguna acción.'));


        // Prepare Logger
        $logger = WooProductImporter::logger();

        $logger->empty();
        $logger->start();

        if ( Configuration::getInt('DEF_CATEGORY') <= 0 )
        {
        	$logger->log("ERROR", 'No se puede descargar los Productos. Debe definir una Categoría por defecto primero.');

        	$logger->stop();

	        return redirect('activityloggers/'.$logger->id)
					->with('error', l('No se puede descargar los Productos. Debe definir una Categoría por defecto primero.'));
        }

        // Do the Mambo!
        foreach ( $list as $pID ) 
        {
        	$logger->log("INFO", 'Se descargará el Producto: <span class="log-showoff-format">{pid}</span> .', ['pid' => $pID]);

        	$importer = WooProductImporter::processProduct( $pID );
        }

        $logger->stop();

        return redirect('activityloggers/'.$logger->id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $logger->id], 'layouts'));
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


	public function importProductImages( Request $request )
	{
		// Route::get('wproducts/importProductImages'

		// En el Servidor se recuperaron las imagnes de la WooTienda, pero dió un gateway timeout => ¿Se podría hacer por partes (chuncks) para que no pase esto?

		// abi_r($request->all());die();

		$product_sku = $request->input('product_sku', '');

		// Products
		$list = Product::select('id', 'reference', 'name')
						->where( function ($q) use ($product_sku) {

							if ( $product_sku == '' )
								// Import All Product Images
								$q->where('reference', '!=', '');
							else
								// Only ONE Product
								$q->where('reference', $product_sku);
						} )
						->get();

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
				// $img_src  = $images[0]['src']  ?? '';
				// $img_name = $images[0]['name'] ?? '';
				// $img_alt  = $images[0]['alt']  ?? '';

				foreach ($images as $position => $image)
				{
					$img_src  = $image['src'];
					$img_name = $image['name'];
					$img_alt  = $image['alt'];

					$img_position  = $position;	// position = 0 => Product Image (Woo Featured image)

					$img_alt = ( $img_alt != '' ? ' :: ' . $img_alt : '' );
					$caption = $img_name . $img_alt;

					// Make the magic
					if( $img_src )
					{

				        $image = \App\Image::createForProductFromUrl($img_src, ['caption' => $caption]);
						
				        $p->images()->save($image);

				        // if ( $p->images()->count() == 1 )
				        if ( $position == 0 )
				        	$p->setFeaturedImage( $image );

					}
				}

			} else {

				// Maybe a default image here???
				// $img_src = 'https://<wp_site>/wp-content/plugins/woocommerce/assets/images/placeholder.png';
				
			}
		}
/*

See https://woocommerce.github.io/woocommerce-rest-api-docs/#product-properties and the Product - Images properties section.

    Image position. 0 means that the image is featured.

The image in position 0 is your featured image.


// https://github.com/woocommerce/woocommerce-rest-api/issues/100


*/

        return redirect()->to(url()->previous() . '#images')
                ->with('success', l('Some Product Images has been retrieved from WooCommerce Shop.') . " $product_sku");
	}


	public function importProductDescriptions( Request $request )
	{
		// Route::get('wproducts/importProductDescriptions'
		// <url>/wwoc/wproducts/importProductDescriptions

		// En el Servidor se recuperaron las imagnes de la WooTienda, pero dió un gateway timeout => ¿Se podría hacer por partes (chuncks) para que no pase esto?

		// abi_r('ok');

		$product_sku = $request->input('product_sku', '');

		// Products
		// $list = Product::select('id', 'reference', 'name')->where('reference', '!=', '')->get();
		$list = Product::select('id', 'reference', 'name')
//						->where('procurement_type', 'manufacture')
						->where( function ($q) use ($product_sku) {

							if ( $product_sku == '' )
								// Import All Product Images
								$q->where('reference', '!=', '');
							else
								// Only ONE Product
								$q->where('reference', $product_sku);
						} )
//						->orderBy('reference', 'asc')
						->get();		// ->where('reference', '!=', '');	// ->pluck('reference');

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

		if ( $product_sku == '' ) die();

        return redirect()->to(url()->previous())
                ->with('success', l('Some Product Descriptions has been retrieved from WooCommerce Shop.'));
	}


	/* ********************************************************************************************* */


	public function updateProductStock( $sku )
	{
		$product_sku = $sku;

		// Get Woo Product by SKU
		$wproduct = WooProduct::fetch( $product_sku );

		// Oh! Second try:
		if ( !$wproduct )
			$wproduct = WooProduct::fetchById( $product_sku );

		if ( !$wproduct ) return ;

        $wproduct_id = $wproduct['id'];

        // Get Product stock
        $product = Product::where('reference', $product_sku)->first();

        $wh_id = Configuration::getInt('WOOC_DEF_WAREHOUSE');
        if ( $wh_id > 0 )
        	$stock = $product->getStockByWarehouse( Configuration::getInt('WOOC_DEF_WAREHOUSE') );
	    else
	    	$stock = $product->quantity;

        // Happyly update WooCommerce Stock ;)
		$data = [
		    'stock_quantity'   => (int) $stock,		// Integer
//		    'stock_status' => '', 	// 	string 	Controls the stock status of the product. Options: instock, outofstock, onbackorder. Default is instock.
//		    'regular_price' => '',	// string
		];

		// To do: catch errores
		WooCommerce::put('products/'.$wproduct_id, $data);


//        return redirect()->to(url()->previous())
        return redirect()->to( route('products.edit', [$product->id]) . '#internet' )
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $product_sku], 'layouts') . $product->as_quantityable($stock));
	}


	public function updateProductPrice( $sku )
	{
		$product_sku = $sku;

		// Get Woo Product by SKU
		$wproduct = WooProduct::fetch( $product_sku );

		// Oh! Second try:
		if ( !$wproduct )
			$wproduct = WooProduct::fetchById( $product_sku );

		if ( !$wproduct ) return ;

        $wproduct_id = $wproduct['id'];

        // Get Product Price
        $product = Product::where('reference', $product_sku)->first();

        $cpl_id = Configuration::getInt('WOOC_DEF_CUSTOMER_PRICE_LIST');
        $price = $product->getPriceByList( PriceList::find($cpl_id) );

        // Happyly update WooCommerce Price ;)
		$data = [
//		    'stock_quantity'   => $stock,		// Integer
//		    'stock_status' => '', 	// 	string 	Controls the stock status of the product. Options: instock, outofstock, onbackorder. Default is instock.
		    'regular_price' => $price->getPrice(),	// string
		];

		// To do: catch errores
		WooCommerce::put('products/'.$wproduct_id, $data);


//        return redirect()->to(url()->previous())
		return redirect()->to( route('products.edit', [$product->id]) . '#internet' )
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $product_sku], 'layouts') . $product->as_priceable( $price->getPrice()));
	}


	public function updateProductImages( $sku )
	{
		$product_sku = $sku;

		// Get Woo Product by SKU
		$wproduct = WooProduct::fetch( $product_sku );

		// Oh! Second try:
		if ( !$wproduct )
			$wproduct = WooProduct::fetchById( $product_sku );

		if ( !$wproduct ) return ;

        $wproduct_id = $wproduct['id'];

        // Get Product stock
        $product = Product::where('reference', $product_sku)->with('images')->first();

		// Images
		$abi_images = $product->images->sortByDesc('is_featured');

		$data = [];

// If no images do nothing: we do not want products on the webshop without image!
if ( $abi_images->count() > 0 )
{		
		// Add featured image to Galery
		$abi_featured = $abi_images->first();
		$abi_images->push($abi_featured);

		$i=0;

		foreach ($abi_images as $abi_image) {
			# code...
			$src = \URL::to( \App\Image::pathProducts() . $abi_image->getImageFolder() . $abi_image->id . '.' . $abi_image->extension );
			// $src = 'http://abimfg.gmdistribuciones.es/tenants/abimfg/images_p/4/0/2/0/4020.JPG';
			$data['images'][] = 
				[
					'src'      => $src,
					// Avoid problems:
//					'name' => str_replace(' ', '%20', trim($abi_image->caption)),
//					'alt'  => str_replace(' ', '%20', trim($abi_image->caption)),
					'position' => $i,	// First image is taken as "main Image" ??? < Key not documented???
				];

			$i++;
		}

        // Happyly update WooCommerce Images ;)

		// To do: catch errores
		WooCommerce::put('products/'.$wproduct_id, $data);
}


//        return redirect()->to(url()->previous())
        return redirect()->to( route('products.edit', [$product->id]) . '#internet' )
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $product_sku], 'layouts') . ' ('.$abi_images->count().')');
	}


}


/* ********************************************************************************************* */
