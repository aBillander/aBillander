<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use aBillander\WooConnect\WooOrder;

use App\Configuration;
use App\Customer;
use App\CustomerOrder;
use App\Country;
use App\State;
use App\ShippingMethod;

use App\Traits\DateFormFormatterTrait;

class WooOrdersController extends Controller 
{
   
   use DateFormFormatterTrait;

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
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

		// https://www.youtube.com/watch?v=IcLaNHxGTrs
		// $user = User::query();
		$queries = [];
		$columns = ['after', 'before', 'status'];
//		$column_dates = ['after', 'before'];

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
		$perPage = Configuration::getInt('WOOC_ORDERS_PER_PAGE');
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
//	    	'orderby' => 'id',
//	    	'order'   => 'asc',
		];
		if ($request->input('date_from', '')) $params['after']  = $request->input('date_from').' 00:00:00';
		if ($request->input('date_to', ''))   $params['before'] = $request->input('date_to')  .' 23:59:59';

		foreach ($columns as $column) {
			if (request()->has($column) && request($column)) {
				// $users = $users->where($coulumn, request($column));
				$params[$column] = request($column);
			}
		}
/*
		foreach ($column_dates as $column) {
			if (isset($params[$column])) {
				$params[$column] .= ' 00:00:00';	// Convert date to ISO8601 compliant date
			}
		}
*/
		// abi_r($params, true);

        try {

			$results = WooCommerce::get('orders', $params);

		}

			catch(WooHttpClientException $e) {

//			$e->getMessage(); // Error message.

//			$e->getRequest(); // Last request data.

//			$e->getResponse(); // Last response data.
//			abi_r($e->getResponse);

			// $err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

			$err = '<ul><li><strong>'.nl2p($e).'</strong></li></ul>';

			return redirect('404')
				->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

		}
		
		// So far so good, then
		$total = WooCommerce::totalResults();

		$orders = collect($results);

		// Allready imported? Let's see deeply
		$first = $orders->first()["id"];
		$last  = $orders->last()["id"];

//		$abi_orders = CustomerOrder::where('reference', '>=', $last)->where('reference', '<=', $first)->get();
		$abi_orders = CustomerOrder::with('productionsheet')->whereBetween('reference_external', [$last, $first])->get();
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
				$order["abi_order_id"] = $needle->id;
				$order["imported_at"] = $needle->created_at->toDateString();
				$order["production_at"] = ''; // $needle->productionsheet->due_date;
				$order["production_sheet_id"] = ''; // $needle->productionsheet->id;
			} else {
				$order["abi_order_id"] = '';
				$order["imported_at"] = '';
				$order["production_at"] = '';
				$order["production_sheet_id"] = '';
			}

            $state = State::findByIsoCode( $order['shipping']['state'], $order['shipping']['country'] );

            $state_name = $state ? $state->name : $order['shipping']['state'];
            $order['shipping']['state_name'] = $state_name;

			return $order;
});
//		abi_r($orders, true);


		$orders = new LengthAwarePaginator($orders, $total, $perPage, $page, ['path' => $request->url(), 'query' => $query]);

		// $orders = collect($results);

//		$availableProductionSheets = ProductionSheet::isOpen()->orderBy('due_date', 'asc')->pluck('due_date', 'id')->toArray();

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
		$customer = Customer::where('webshop_id', WooOrder::getCustomerId( $order ))->first();

		$vatNumber = WooOrder::getVatNumber( $order );
		$order['billing']['vat_number'] = $vatNumber;

		$order["date_downloaded"] = WooOrder::getAbiExportedDate( $order );


		// Billing
		$country = Country::findByIsoCode( $order['billing']['country'] );
		$order['billing']['country_name'] = $country ? $country->name : $order['billing']['country'];

		$state = State::findByIsoCode( $order['billing']['state'], $order['billing']['country'] );
		$order['billing']['state_name'] = $state ? $state->name : $order['billing']['state'];


		// Shipping
		$country = Country::findByIsoCode( $order['shipping']['country'] );
		$order['shipping']['country_name'] = $country ? $country->name : $order['shipping']['country'];

		$state = State::findByIsoCode( $order['shipping']['state'], $order['shipping']['country'] );
		$order['shipping']['state_name'] = $state ? $state->name : $order['shipping']['state'];

		// Address check
		$order['has_shipping'] = WooOrder::getShippingAddressId( $order ) != WooOrder::getBillingAddressId( $order );

		// Carrier
		$order['shipping']['shipping_method'] = '('.$order['shipping_lines'][0]['method_id'].') '.$order['shipping_lines'][0]['method_title'];
		$shipping_method = ShippingMethod::with('carrier')->find( WooOrder::getShippingMethodId( $order['shipping_lines'][0]['method_id'] ) );

		// $order['shipping']['shipping_method'] = '';
		$order['shipping']['carrier'] = '';

		if ( $shipping_method ) {
			$order['shipping']['shipping_method'] = $shipping_method->name;
			
			if ($shipping_method->carrier) {
				$order['shipping']['carrier'] = $shipping_method->carrier->name;
			}
		}

		$abi_order = CustomerOrder::where('reference_external', $order["id"])->first();

			if ($abi_order) {
				$order["abi_order_id"] = $abi_order->id;
				$order["imported_at"] = $abi_order->created_at->toDateString();
				$order["production_at"] = ''; // $abi_order->productionsheet->due_date;
				$order["production_sheet_id"] = $abi_order->production_sheet_id;
			} else {
				$order["abi_order_id"] = '';
				$order["imported_at"] = '';
				$order["production_at"] = '';
				$order["production_sheet_id"] = '';
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
//		    'date_paid'     => '2017-07-21T13:58:25',
//    		'date_paid_gmt' => '2017-07-21T12:58:25',
/*
		    'set_paid' => (boolean) $set_paid,
		    'meta_data' => [[									// Meta para saber cuándo fue descargada
		    	'key'   => 'date_abi_exported',
                'value' => (string) \Carbon\Carbon::now(),
             ]],
*/
		];

		if ($set_paid) {

			$today = \Carbon\Carbon::now();

			$data += [
			    'status'   => $status,
			    'date_paid'     => (string) $today,
	    		'date_paid_gmt' => (string) $today->tz('UTC'),
	
			    'set_paid' => (boolean) $set_paid,
	
			];

		}

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


	
	public function importOrderList( $list )
	{
        // ProductionSheetsController
        if ( count( $list ) == 0 ) 
            return redirect()->route('worders.index')
                ->with('warning', l('No se ha seleccionado ningún Pedido, y no se ha realizado ninguna acción.'));


        // Prepare Logger
        $logger = \aBillander\WooConnect\WooOrderImporter::logger();

        $logger->empty();
        $logger->start();

        // Do the Mambo!
        foreach ( $list as $oID ) 
        {
        	$logger->log("INFO", 'Se descargará el Pedido: <span class="log-showoff-format">{oid}</span> .', ['oid' => $oID]);

        	$importer = \aBillander\WooConnect\WooOrderImporter::processOrder( $oID );
        }

        $logger->stop();

        return redirect('activityloggers/'.$logger->id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $logger->id], 'layouts'));
	}

	public function importOrders( Request $request )
	{

        return $this->importOrderList( $request->input('worders', []) );
	} 


	public function import($id)
	{
		
        return $this->importOrderList( [$id] );
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