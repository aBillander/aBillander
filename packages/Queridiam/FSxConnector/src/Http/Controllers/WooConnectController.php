<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \aBillander\WooConnect\WooConnector;
use \aBillander\WooConnect\WooOrderImporter;

class WooConnectController extends Controller {


   protected $currency;

   public function hello()
   {
        // return \aBillander\WooConnect\WooConnector::getStatusList();

        try {



			$results = WooCommerce::get('orders');

			$products = WooCommerce::get('products');

			$customers = WooCommerce::get('customers');



			$result = count($results);

			$customer = count($customers);

			$product = count($products);



			//you can set any date which you want

			$query = ['date_min' => '2017-10-01', 'date_max' => '2017-10-30'];

			$sales = WooCommerce::get('reports/sales', $query);

			$sale = $sales[0]["total_sales"];

		}



			catch(HttpClientException $e) {

			$e->getMessage(); // Error message.

			$e->getRequest(); // Last request data.

			$e->getResponse(); // Last response data.

		}

 //       abi_r($endpoints, true);

		return view('woo_connect::woo_connect.hello', compact('results', 'result', 'customers', 'customer', 'products', 'product', 'sale'));

		// Order status. Options: pending, processing, on-hold, completed, cancelled, refunded and failed. Default is pending.

		// WooCommerce, en la instalación por defecto, incluye siete estados distintos en los que un pedido puede encontrarse:

    	// Completado,     Pendiente de pago,    En espera,    Procesando,    Cancelado,    Reembolsado,    Fallido

    	// https://www.enriquejros.com/estados-de-pedido-woocommerce/


   }

   public function __construct($currency = null)
   {
        $this->currency = $currency;
   }

	/**
	 * Display a listing of the resource.
	 * GET /currencies
	 *
	 * @return Response
	 */
	public function index()
	{
		$currencies = $this->currency->orderBy('id', 'asc')->get();

        return view('currencies.index', compact('currencies'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /currencies/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('currencies.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /currencies
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Currency::$rules);

		$currency = $this->currency->create($request->all());

		\App\CurrencyConversionRate::create([
				'date' => \Carbon\Carbon::now(), 
				'currency_id' => $currency->id, 
				'conversion_rate' => $currency->conversion_rate, 
				'user_id' => \Auth::id(),
			]);

		return redirect('currencies')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $currency->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->edit($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /currencies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$currency = $this->currency->findOrFail($id);
		
		return view('currencies.edit', compact('currency'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$currency = $this->currency->findOrFail($id);

		$this->validate($request, Currency::$rules);

		$currency->update($request->all());

		if ( $currency->id != \App\Context::getContext()->currency->id )
			\App\CurrencyConversionRate::create([
					'date' => \Carbon\Carbon::now(), 
					'currency_id' => $currency->id, 
					'conversion_rate' => $currency->conversion_rate, 
					'user_id' => \Auth::id(),
				]);

		return redirect('currencies')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->currency->findOrFail($id)->delete();

        // Delete currency conversion rate history

        return redirect('currencies')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}


/* ********************************************************************************************* */    


	/**
	 * Show the form for editing the specified resource.
	 * GET /currencies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function configurationsEdit()
	{
		$wooconfs = WooConnector::getWooConfigurations();
		// Save Configurations Cache
		// \App\Configuration::updateValue('WOOC_CONFIGURATIONS_CACHE', json_encode($wooconfs));
		// $wooconfs = ['woocommerce_default_country' => WooConnector::getWooSetting( 'woocommerce_default_country' )];

		return view('woo_connect::woo_connect.edit_configurations', compact('wooconfs'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function configurationsUpdate(Request $request)
	{
		$settings = [];

		// Get Configurations from WooCommerce Shop
        try {

			$groups = WooCommerce::get('settings');	// Array
		}

		catch( WooHttpClientException $e ) {

			/*
			$e->getMessage(); // Error message.

			$e->getRequest(); // Last request data.

			$e->getResponse(); // Last response data.
			*/

			return redirect()->route('worders.index')
					->with('error', $e->getMessage());

		}

		// abi_r($groups, true);

		foreach ($groups as $group) {

			try {

				$set = WooCommerce::get( 'settings/'.$group['id'] );	// Array

				$settings = array_merge($settings, $set);

				// abi_r($set);abi_r('* ***************************************** *');
			}

			catch( WooHttpClientException $e ) {

				// settings/integration : Error: Grupo de ajustes no válido. [rest_setting_setting_group_invalid]

//				return redirect()->route('worders.index')
//						->with('error', $e->getMessage());

			}
		}

		// abi_r($settings);

		// Save Settings Cache
		// Loose some fat first
		$settings = array_map(function($value){
			$v = [ 'id' => $value['id'], 'description' => $value['description'], 'value' => $value['value'], ];
		    return $v;
		}, $settings);
		\App\Configuration::updateValue('WOOC_CONFIGURATIONS_CACHE', json_encode($settings));

		// die();

		return redirect('wooc/worders')
				->with('success', l('This configuration has been successfully updated', [], 'layouts'));
	}


/* ********************************************************************************************* */    


	/**
	 * Show the form for editing the specified resource.
	 * GET /currencies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function configurationTaxesEdit()
	{
		$wootaxes = WooConnector::getWooTaxes();
		// Save Taxes Cache
		\App\Configuration::updateValue('WOOC_TAXES_CACHE', json_encode($wootaxes));
		// Woo Taxes Dictionary
		$dic = [];
		$dic_val = [];
		foreach ($wootaxes as $wootax) {
			$dic[$wootax['slug']] = WooConnector::getTaxKey( $wootax['slug'] );
			$dic_val[$wootax['slug']] = \App\Configuration::get($dic[$wootax['slug']]);
		}

		$taxList = \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

		return view('woo_connect::woo_connect.edit_taxes', compact('wootaxes', 'dic', 'dic_val', 'taxList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function configurationTaxesUpdate(Request $request)
	{
		// Validation rules
        $rules = [];
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            $rules[ 'dic.'.$key ] = 'exists:taxes,id';
        }

		$this->validate($request, $rules);
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            \App\Configuration::updateValue($key, $val);
        }
		// Save Taxes Dictionary Cache
		$dic_val = [];
		$wootaxes = json_decode(\App\Configuration::get('WOOC_TAXES_CACHE'), true);
		foreach ($wootaxes as $wootax) {
			$dic_val[$wootax['slug']] = \App\Configuration::get(WooConnector::getTaxKey($wootax['slug']));
		}

		\App\Configuration::updateValue('WOOC_TAXES_DICTIONARY_CACHE', json_encode($dic_val));

//		abi_r($request->input('dic'));
//		abi_r($dic_val, true);

		return redirect('wooc/worders')
				->with('success', l('This configuration has been successfully updated', [], 'layouts'));
	}


/* ********************************************************************************************* */    


	/**
	 * Show the form for editing the specified resource.
	 * GET /currencies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function configurationPaymentGatewaysEdit()
	{
		$woopgates = WooConnector::getWooPaymentGateways();
		// Save Payment Gateways Cache
		// Loose some fat first
		$woopgates = array_map(function($value){
			unset($value['settings']);
		    return $value;
		}, $woopgates);
		\App\Configuration::updateValue('WOOC_PAYMENT_GATEWAYS_CACHE', json_encode($woopgates));
		// Woo Payment Gateways Dictionary
		$dic = [];
		$dic_val = [];
		foreach ($woopgates as $woopgate) {
			$dic[$woopgate['id']] = WooConnector::getPaymentGatewayKey( $woopgate['id'] );
			$dic_val[$woopgate['id']] = \App\Configuration::get($dic[$woopgate['id']]);
		}

		$pgatesList = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

		return view('woo_connect::woo_connect.edit_payment_gateways', compact('woopgates', 'dic', 'dic_val', 'pgatesList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function configurationPaymentGatewaysUpdate(Request $request)
	{
		// Validation rules
        $rules = [];
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            $rules[ 'dic.'.$key ] = 'exists:payment_methods,id';
        }

		$this->validate($request, $rules);
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            \App\Configuration::updateValue($key, $val);
        }
		// Save Payment Gateways Dictionary Cache
		$dic_val = [];
		$woopgates = json_decode(\App\Configuration::get('WOOC_PAYMENT_GATEWAYS_CACHE'), true);
		foreach ($woopgates as $woopgate) {
			$dic_val[$woopgate['id']] = \App\Configuration::get(WooConnector::getPaymentGatewayKey($woopgate['id']));
		}

		\App\Configuration::updateValue('WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE', json_encode($dic_val));

//		abi_r($request->input('dic'));
//		abi_r($dic_val, true);

		return redirect('wooc/worders')
				->with('success', l('This configuration has been successfully updated', [], 'layouts'));
	}


/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxCurrencyRateSearch(Request $request)
    {
        // Request data
        $currency_id     = $request->input('currency_id');
        
        $currency = Currency::find(intval($currency_id));

        if ( !$currency ) {
            // Die silently
            return '';
        }

        return $currency->conversion_rate;
    }

}