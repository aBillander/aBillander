<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Image;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use WooCommerce;
use aBillander\WooConnect\WooCustomer;
use aBillander\WooConnect\WooCustomerImporter;

class WooCustomersController extends Controller 
{

   protected $customer;
   protected $abi_customer;

   public function __construct(WooCustomer $customer, Customer $abi_customer)
   {
         $this->customer = $customer;
         $this->abi_customer = $abi_customer;
   }

	/**
	 * Display a listing of the resource.
	 * GET /wcustomers
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if (request()->has('dupes'))
		{
				abi_r('>>>>> Clientes con igual webshop_id');

				$customers = $this->abi_customer->select('id', 'webshop_id')->where('webshop_id', '!=', '')->get();
		
		
				$groupedByValue = $customers->groupBy('webshop_id');
		
		
			    $dupes = $groupedByValue->filter(function ($groups) {
			        return $groups->count() > 1;
			    });

			    abi_r($dupes->toArray());

				abi_r('>>>>> Clientes con igual reference_external');

				$customers = $this->abi_customer->select('id', 'reference_external')->where('reference_external', '!=', '')->get();
		
		
				$groupedByValue = $customers->groupBy('reference_external');
		
		
			    $dupes = $groupedByValue->filter(function ($groups) {
			        return $groups->count() > 1;
			    });

			    abi_r($dupes->toArray());die();
		}

	    






		// 
		$perPage = request('perPage', intval(Configuration::get('WOOC_ORDERS_PER_PAGE')));

		// abi_r($perPage);die();

		$queries = [];
		$columns = ['search', 'email'];	// ['after', 'before', 'status'];

		foreach ($columns as $column) {
			if (request()->has($column)) {

				$queries[$column] = request($column);
			}
		}

		$query = array_merge($request->query(), $queries);

		$page = Paginator::resolveCurrentPage();  // $request->input('page', 1); // Get the current page or default to 1
		$perPage = $perPage > 0 ? $perPage : intval(Configuration::get('WOOC_ORDERS_PER_PAGE'));	// Waiting for Configuration::get('WOOC_PRODUCTS_PER_PAGE') if needed
		if ($perPage<1) $perPage=10;
		$offset = ($page * $perPage) - $perPage;


		// https://stackoverflow.com/questions/39101445/how-to-sort-products-in-woocommerce-wordpress-json-api
		// https://www.storeurl.com/wc-api/v3/products?orderby=title&order=asc
		// https://www.storeurl.com/wc-api/v3/products?filter[order]=asc&filter[orderby]=meta_value_num&filter[orderby_meta_key]=_regular_price  =>  the "Filter" parameter, which allows you to use any WP_Query style arguments you may want to add to your request
		$params = [
//		    'context',				// Scope under which the request is made; determines fields present in response. Options: view and edit. Default is view.
		    'per_page' => $perPage,	// Default: 10 :: Maximum: 100
		    'page' => $page,
//		    'search' => 'LEoN',			// Limit results to those matching a string. Busca en varios campos la aparición de la cadena, sensible a acentos (con acentos no encuentra, aunque contenga la búsqueda), case insensitive. Ver: https://stackoverflow.com/questions/45442275/wordpress-rest-api-url-decoding
//		    'exclude' 	array 	Ensure result set excludes specific IDs.
//		    'include' 	array 	Limit result set to specific IDs.
//		    'offset' 	integer 	Offset the result set by a specific number of items.
	    	'orderby' => 'registered_date',	// Sort collection by object attribute. Options: id, include, name and registered_date. Default is name.
	    	'order'   => 'desc',		// Options: asc and desc. Default is asc.
//	    	'email' => 'glagier@hotmail.com',	// Full email, case insensitive
//	    	'role',
		];

		// display : Customer archive display type. Options: default, products, subcustomers and both. Default is default.

		foreach ($columns as $column) {
			if (request()->has($column) && request($column)) {

				$params[$column] = request($column);
			}
		}


        try {

			$results = WooCommerce::get('customers', $params);

		}

			catch(WooHttpClientException $e) {

//			$e->getMessage(); // Error message.

//			$e->getRequest(); // Last request data.

//			$e->getResponse(); // Last response data.
//			abi_r($e->getResponse);

			$err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

			// Improbe this: 404 doesnot show upper-left logo , and language is English
			return redirect()->back()
				->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

		}


		// abi_r($results, true);





		
		// So far so good, then
		$total = WooCommerce::totalResults();

		$customers = collect($results);

		// Allready imported? Let's see deeply
		$first = $customers->first()["id"];
		$last  = $customers->last()["id"];


		$customers = new LengthAwarePaginator($customers, $total, $perPage, $page, ['path' => $request->url(), '
			query' => $query]);

		$abi_customers = [];		// $this->abi_customer::whereIn('webshop_id', $ids)->get();

		// abi_r($total);
		// abi_r($customers);die();


		// Not so far, kawaii bunny
		$ids = $customers->pluck('id')->toArray();

		$abi_customers = $this->abi_customer::whereIn('webshop_id', $ids)->get();

		$customers->getCollection()->transform(function ($customer) use ($abi_customers) {
		    // Your code here
			$abi_customer = $abi_customers->where('webshop_id', $customer["id"]);

			$customer["abi_customer"] = $abi_customer->first();
			$customer["abi_customer_count"] = $abi_customer->count();

		    return $customer;
		});

		// abi_r($customers);die();

		$stub = request()->has('p') ? '_p' : '';

        return view('woo_connect::woo_customers.index'.$stub, compact('customers', 'abi_customers', 'query'));
        // return view('woo_connect::woo_customers.index', compact('customers', 'abi_customers', 'query'));
	}



	public function fetchAbiOrphans()
	{

		$ids = WooCustomer::fetchAll( ['id'], 100 );

		if (0)
		foreach ($ids as $id) {
			# code...
			echo $id.'<br />';
		}

		$abi_customers = $this->abi_customer::where('webshop_id', '!=', '')->whereNotIn('webshop_id', $ids)->get();


		foreach ($abi_customers as $abi_customer) {
			# code...
			abi_r("abi_id: ".$abi_customer->id." - woo_id: ".$abi_customer->webshop_id);
		}

		// $abi_ids = $abi_customers->pluck('id')->toArray();

		// abi_r($abi_ids);



		die();
	}



	public function fetchWoocOrphans()
	{

		$ids = WooCustomer::fetchAllEmail( ['email'], 100 );

		if (0)
		foreach ($ids as $id) {
			# code...
			echo $id.'<br />';
		}

		$abi_customers = $this->abi_customer::
								whereHas('addresses', function($q) use ($ids) 
					            {
					                // $q->where('email', 'LIKE', '%' . $email . '%');
					                $q->whereIn('email', $ids);

					            })
					            ->get();





		


		foreach ($abi_customers as $abi_customer) {
			# code...
			if('webshop_id' && 'webshop_id' != '') continue;
			abi_r("abi_id: ".$abi_customer->id." - woo_id: ".$abi_customer->webshop_id);
		}

		// $abi_ids = $abi_customers->pluck('id')->toArray();

		// abi_r($abi_ids);



		die();
	}



	/**
	 * Show the form for creating a new resource.
	 * GET /wcustomers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /wcustomers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$abi_customer = $this->abi_customer
									->with('parent')
									->findOrFail($request->input('abi_customer_id', 0));

		// abi_r($abi_customer);die();

		$data = [
		    'name' => $abi_customer->name,
		    'slug' => '',
		    'parent' => $abi_customer->parent ? (int) $abi_customer->parent->webshop_id : 0,
		    'description' => $abi_customer->description,		// HTML
		    'display' => 'default',		//  Options: default, products, subcustomers and both. Default is default.
		    'image' => [
		    		'src' => '',		// Image URL.
		    		'name' => '',
		    		'alt' => ''
		    ],
		    'menu_order' => 0,
		];

		// https://rudrastyh.com/woocommerce/rest-api-create-update-remove-products.html#remove_product
		// $result = WooCommerce::delete('products/customers/'.$abi_customer->webshop_id, ['force' => true]);

		unset($data['slug']);
		unset($data['image']);
		unset($data['menu_order']);

		// abi_r($data);die();

		$result = WooCommerce::post('products/customers', $data);

		$abi_customer->update(['webshop_id' => $result['id']]);

		
		return redirect()->to(url()->previous() . '#internet')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $result['id']], 'layouts') );
	}

	/**
	 * Display the specified resource.
	 * GET /wcustomers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, Request $request)
	{
		$customer = WooCustomer::fetch( $id );

        if ($request->has('embed'))
        {
        	if($request->ajax()){

	            return response()->json( [
	                'success' => $customer ? 'OK' : 'KO',
	                'msg' => 'OK'." $id ".($customer['name'] ?? ''),
//	                'html-raw' => '<pre>'.print_r($customer, true).'</pre>',
	                'html' => view('woo_connect::woo_customers.show_embed', compact('customer'))->render(),
	            ] );

	        }
        }
        else
        	return $this->fetch($id);	// To do: return data into proper view
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /wcustomers/{id}/edit
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
	 * PUT /wcustomers/{id}
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
	 * DELETE /wcustomers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}


/* ********************************************************************************************* */   


	
	public function importCustomerList( $list )
	{
        // 
        if ( count( $list ) == 0 ) 
            return redirect()->route('wcustomers.index')
                ->with('warning', l('No se ha seleccionado ningún Cliente, y no se ha realizado ninguna acción.'));


        // Prepare Logger
        $logger = WooCustomerImporter::logger();

        $logger->empty();
        $logger->start();

        // Do the Mambo!
        foreach ( $list as $pID ) 
        {
        	$logger->log("INFO", 'Se descargará el Cliente <span class="log-showoff-format">{pid}</span> .', ['pid' => $pID]);

        	$importer = WooCustomerImporter::processCustomer( $pID );
        }

        $logger->stop();

        return redirect('activityloggers/'.$logger->id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $logger->id], 'layouts'));
	}

	public function importCustomers( Request $request )
	{

        return $this->importCustomerList( $request->input('wcustomers', []) );
	} 


	public function import($id)
	{
		
        return $this->importCustomerList( [$id] );
	}


	public function fetch($id)
	{
		$customer = WooCustomer::fetch( $id );

		abi_r($customer, true);
	}


	// Would be: importCustomerImages() or something alike
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

			        $image = Image::createForProductFromUrl($img_src, ['caption' => $p->name]);
					
			        $p->images()->save($image);

			        if ( $p->images()->count() == 1 )
			        	$p->setFeaturedImage( $image );

				}

			} else {

				// Culo 4U:
				$img_src = 'https://<wp_site>/wp-content/plugins/woocommerce/assets/images/placeholder.png';
				
			}

			// abi_r($sku.' :: '.$img_src);
		}

		// die();

        return redirect('products')
                ->with('success', l('Some Product Images has been retrieved from WooCommerce Shop.'));
	}



	public function ascription( Request $request )
	{
		
		// abi_r($request->toArray(), true);

		$current_customer_id = $request->input('current_customer_id');
		$customer_id = $request->input('customer_id', null);		
		$webshop_id  = $request->input('webshop_id');

		if ( (int) $customer_id > 0 )
		{
			$customer = Customer::findOrFail($customer_id);

			$customer->update(['webshop_id' => $webshop_id]);

		} else {
			$customer = Customer::find($current_customer_id);

			if ($customer)
				$customer->update(['webshop_id' => null]);
		}

		
		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $webshop_id], 'layouts') );
	}



}


/* ********************************************************************************************* */
