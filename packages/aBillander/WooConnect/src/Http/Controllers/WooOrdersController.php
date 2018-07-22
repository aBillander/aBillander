<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \aBillander\WooConnect\WooOrder;

class WooOrdersController extends Controller {


   protected $order;

   public function __construct(WooOrder $order)
   {
         $this->order = $order;
   }

	/**
	 * Display a listing of the resource.
	 * GET /worders
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		// https://www.youtube.com/watch?v=IcLaNHxGTrs
		// $user = User::query();
		$queries = [];
		$columns = ['after', 'before', 'status'];
		$column_dates = ['after', 'before'];

		// ToDo: convert dates to ISO8601 compliant date

		foreach ($columns as $column) {
			if (request()->has($column)) {
				// $users = $users->where($coulumn, request($column));
				$queries[$column] = request($column);
			}
		}

		// $users = $users->paginate(5)->appends($queries);


		$query = array_merge($request->query(), $queries);

		// https://shendinotes.wordpress.com/2017/01/13/manual-pagination-with-laravel-5/
		// https://laracasts.com/discuss/channels/laravel/laravel-pagination-not-working-with-array-instead-of-collection

		$page = Paginator::resolveCurrentPage();  // $request->input('page', 1); // Get the current page or default to 1
		$perPage = intval(\App\Configuration::get('WOOC_ORDERS_PER_PAGE'));
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
//	    	'orderby' => 'id',
//	    	'order'   => 'asc',
		];

		foreach ($columns as $column) {
			if (request()->has($column) && request($column)) {
				// $users = $users->where($coulumn, request($column));
				$params[$column] = request($column);
			}
		}

		foreach ($column_dates as $column) {
			if (isset($params[$column])) {
				$params[$column] .= ' 00:00:00';	// Convert date to ISO8601 compliant date
			}
		}

		// abi_r($params, true);

        try {

			$results = WooCommerce::get('orders', $params);

		}

			catch(WooHttpClientException $e) {

//			$e->getMessage(); // Error message.

//			$e->getRequest(); // Last request data.

//			$e->getResponse(); // Last response data.
//			abi_r($e->getResponse);

			$err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

			return redirect('404')
				->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

		}
		
		// So far so good, then
		$total = WooCommerce::totalResults();

		$orders = collect($results);

		// Allready imported? Let's see deeply
		$first = $orders->first()["id"];
		$last  = $orders->last()["id"];

//		$abi_orders = \App\CustomerOrder::where('reference', '>=', $last)->where('reference', '<=', $first)->get();
		$abi_orders = \App\CustomerOrder::with('productionsheet')->whereBetween('reference_external', [$last, $first])->get();
		$abi_orders = $abi_orders->keyBy('reference_external');
/*
		foreach ($orders as &$order){
			// Rock 'n Roll!
			$needle = $abi_orders->get( $order["id"] );abi_r($order["id"]);
			if ($needle) {
				$order["imported_at"] = $needle->created_at;
				$order["production_at"] = $needle->productionsheet->due_date;
			} else {
				$order["imported_at"] = '';
				$order["production_at"] = '';
			}

		}
*/

$orders = $orders->map(function ($order, $key) use ($abi_orders)
{
			// Rock 'n Roll!
			$needle = $abi_orders->get( $order["id"] );
			if ($needle) {
				$order["imported_at"] = $needle->created_at->toDateString();
				$order["production_at"] = $needle->productionsheet->due_date;
				$order["production_sheet_id"] = $needle->productionsheet->id;
			} else {
				$order["imported_at"] = '';
				$order["production_at"] = '';
				$order["production_sheet_id"] = '';
			}

            $state = \App\State::findByIsoCode( $order['shipping']['state'], $order['shipping']['country'] );

            $state_name = $state ? $state->name : $order['shipping']['state'];
            $order['shipping']['state_name'] = $state_name;

			return $order;
});
//		abi_r($orders, true);


		$orders = new LengthAwarePaginator($orders, $total, $perPage, $page, ['path' => $request->url(), 'query' => $query]);

		// $orders = collect($results);

//		$availableProductionSheets = \App\ProductionSheet::isOpen()->orderBy('due_date', 'asc')->pluck('due_date', 'id')->toArray();

        return view('woo_connect::woo_orders.index', compact('orders', 'query'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /worders/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /worders
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /worders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// return $id;

		// print_r(json_decode($json));       -> stdClass Object
		// print_r(json_decode($json, true)); -> Array

		// $order = new \StdClass();

		// https://www.tychesoftwares.com/how-to-add-prefix-or-suffix-to-woocommerce-order-number/
		// https://www.tychesoftwares.com/how-to-reset-woocommerce-order-numbers-every-24-hours/
		// https://www.tychesoftwares.com/add-new-column-woocommerce-orders-page/
/*
		try {
			$wc_currency = \App\Currency::findOrFail( intval(\App\Configuration::get('WOOC_DEF_CURRENCY')) );
		} catch (ModelNotFoundException $ex) {
			// If Currency does not found. Not any good here...
			$wc_currency = \App\Context::getContext()->currency;	// Or fallback to Configuration::get('DEF_CURRENCY')
		}

		$params = [
		    'dp'   => $wc_currency->decimalPlaces,
		];

		$order = WooCommerce::get('orders/'.$id, $params);	// Array
*/
		
		// Real Mambo here:
		$order = WooOrder::fetch( $id );

		// Catch error:

//            if (!$this->data) {
//                $this->error[] = 'Se ha intentado recuperar el Pedido número <b>"'.$order_id.'"</b> y no existe.';
//                $this->run_status = false;
//            }

		// I am thirsty. Let's get hydrated!
		$customer = \App\Customer::where('webshop_id', $order['customer_id'])->first();
//		$customer = null;

		$vatNumber = WooOrder::getVatNumber( $order );
		$order['billing']['vat_number'] = $vatNumber;

		$order["date_downloaded"] = WooOrder::getAbiExportedDate( $order );


		// Billing
		$country = \App\Country::findByIsoCode( $order['billing']['country'] );
		$order['billing']['country_name'] = $country ? $country->name : $order['billing']['country'];

		$state = \App\State::findByIsoCode( $order['billing']['state'], $order['billing']['country'] );
		$order['billing']['state_name'] = $state ? $state->name : $order['billing']['state'];


		// Shipping
		$country = \App\Country::findByIsoCode( $order['shipping']['country'] );
		$order['shipping']['country_name'] = $country ? $country->name : $order['shipping']['country'];

		$state = \App\State::findByIsoCode( $order['shipping']['state'], $order['shipping']['country'] );
		$order['shipping']['state_name'] = $state ? $state->name : $order['shipping']['state'];

		// Carrier
		$order['shipping']['shipping_method'] = '('.$order['shipping_lines'][0]['method_id'].') '.$order['shipping_lines'][0]['method_title'];
		$shipping_method = \App\ShippingMethod::with('carrier')->find( WooOrder::getShippingMethodId( $order['shipping_lines'][0]['method_id'] ) );

		if ( $shipping_method ) {
			$order['shipping']['shipping_method'] = $shipping_method->name;
			
			$order['shipping']['carrier'] = '';
			if ($shipping_method->carrier) {
				$order['shipping']['carrier'] = $shipping_method->carrier->name;
			}
		}

		return view('woo_connect::woo_orders.show', compact('order', 'customer'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /worders/{id}/edit
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
	 * PUT /worders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$query    = $request->query();
		$status   = $request->input('order_status');
		$set_paid = $request->input('order_set_paid', 0);	// set_paid 	boolean 	Define if the order is paid. It will set the status to processing and reduce stock items. Default is false. 

//		abi_r((bool) $set_paid, true);

		$data = [
		    'status'   => $status,
		    'set_paid' => (boolean) $set_paid,
		    'meta_data' => [[									// Meta para saber cuándo fue descargada
		    	'key'   => 'date_abi_exported',
                'value' => (string) \Carbon\Carbon::now(),
             ]],
		];

		// To do: catch errore¡s
		WooCommerce::put('orders/'.$id, $data);

		return redirect()->route('worders.index', $query)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . ' ['.$status.']');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /worders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}


/* ********************************************************************************************* */   


	public function importOrders( Request $request )
	{
        // ProductionSheetsController
        if ( count($request->input('worders', [])) == 0 ) 
            return redirect()->route('worders.index')
                ->with('warning', l('No se ha seleccionado ningún Pedido, y no se ha realizado ninguna acción.'));


        // Prepare Logger
        $logger = \aBillander\WooConnect\WooOrderImporter::logger();

        $logger->empty();
        $logger->start();

        // Do the Mambo!
        foreach ( $request->input('worders', []) as $oID ) 
        {
        	$logger->log("INFO", 'Se descargará el Pedido: <span class="log-showoff-format">{oid}</span> .', ['oid' => $oID]);

        	$importer = \aBillander\WooConnect\WooOrderImporter::processOrder( $oID );
        }

        $logger->stop();

        return redirect('activityloggers/'.$logger->id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $logger->id], 'layouts'));

/* 
        // Mambo!
		$importer = \aBillander\WooConnect\WooOrderImporter::makeOrder( $id );

		if ( $importer->tell_run_status() )
			
			return redirect()->route('worders.show', $id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
		else
			
			return redirect()->route('worders.show', $id)
				->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts') . $importer->error);
*/
	} 


	public function import($id)
	{

        return redirect()->route('worders.index')
				->with('error', l('Por favor, marque el pedido y clique sobre "Importar", en la caja "Importar pedidos".'));

		// return "Order: $id";

        // Prepare Logger
        // $logger = \App\ActivityLogger::setup( 
        //    'Import WooCommerce Orders :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s'), \aBillander\WooConnect\WooOrderImporter::loggerSignature() );

        // Mambo!
		$importer = \aBillander\WooConnect\WooOrderImporter::processOrder( $id );

		if ( $importer->tell_run_status() )
			
			return redirect()->route('worders.show', $id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
		else
			
			return redirect()->route('worders.show', $id)
				->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts') . $importer->error);
	}

	public function fetch($id)
	{
		$order = WooOrder::fetch( $id );

		abi_r($order, true);
	}

	public function invoice($id)
	{
		$importer = \aBillander\WooConnect\WooOrderImporter::makeInvoice( $id );

		if ( $importer->tell_run_status() )
			
			return redirect()->route('worders.show', $id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
		else
			
			return redirect()->route('worders.show', $id)
				->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts') . $importer->error);
	}


/* ********************************************************************************************* */   


	/**
	 * Show the form for creating a new resource.
	 * GET /worders/create
	 *
	 * @return Response
	 */
	public function getStatuses()
	{
		// abi_r('x', true);
        try {
        	$results = WooCommerce::get('orders/statuses');
		}



		catch(HttpClientException $e) {

			abi_r($e->getMessage(), true);

			$e->getMessage(); // Error message.

			$e->getRequest(); // Last request data.

			$e->getResponse(); // Last response data.

			abi_r($e->getMessage(), true);

		}

		return $results;
	} 

}


/* ********************************************************************************************* */   


function getSpanish( $string )
{
	$sa = explode('[:es]', $string);
	$s = $sa[1];

	$i = strpos($s, '[');
	$s = substr($s, 0, $i);

	return $s;
}