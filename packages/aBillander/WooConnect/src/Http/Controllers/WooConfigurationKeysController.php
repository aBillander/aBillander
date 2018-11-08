<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \aBillander\WooConnect\WooConnector;
use \aBillander\WooConnect\WooOrderImporter;

use \App\Configuration as Configuration;


class WooConfigurationKeysController extends Controller {

   public $conf_keys = [];

   public function __construct()
   {

        $this->conf_keys = [

                1 => [

                        'WOOC_DECIMAL_PLACES',
//                        'WOOC_DEF_CURRENCY',
                        'WOOC_DEF_CUSTOMER_GROUP',
                        'WOOC_DEF_CUSTOMER_PRICE_LIST',

                        'WOOC_DEF_LANGUAGE',
                        'WOOC_DEF_ORDERS_SEQUENCE',
                        'WOOC_DEF_SHIPPING_TAX',

                        'WOOC_ORDER_NIF_META',
                        'WOOC_ORDERS_PER_PAGE',

                        'WOOC_USE_LOCAL_PRODUCT_NAME',

                    ],

                2 => [


                    ],

                3 => [


                    ],

                4 => [


                    ],
        ];

   }
   
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $conf_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        // Check tab_index
        $tab_view = 'woo_connect::woo_configuration_keys.'.'key_group_'.intval($tab_index);
        if (!\View::exists($tab_view)) 
            return \Redirect::to('404');

        $key_group = [];

        foreach ($this->conf_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);

        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $customer_groupList = \App\CustomerGroup::pluck('name', 'id')->toArray();
        $price_listList = \App\PriceList::pluck('name', 'id')->toArray();
        $languageList = \App\Language::pluck('name', 'id')->toArray();
        $orders_sequenceList = \App\Sequence::listFor( \App\CustomerOrder::class );
        $taxList = \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view( $tab_view, compact('tab_index', 'key_group', 'currencyList', 'customer_groupList', 'price_listList', 'languageList', 'orders_sequenceList', 'taxList') );

        // https://bootsnipp.com/snippets/M27e3
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
    public function store(Request $request)
    {
        // die($request->input('tab_index'));

        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : -1;
        
        $key_group = isset( $this->conf_keys[$tab_index] ) ?
                            $this->conf_keys[$tab_index]   :
                            null ;

        // Check tab_index
        if (!$key_group) 
            return redirect('404');

        foreach ($key_group as $key) 
        {
            if ($request->has($key)) {

                // abi_r("-$key-");
                // abi_r($request->input($key));

                // Prevent NULL values
                $value = is_null( $request->input($key) ) ? '' : $request->input($key);

                \App\Configuration::updateValue($key, $value);
            }
        }

        // die();

        return redirect('wooc/wooconnect/wooconfigurationkeys?tab_index='.$tab_index)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $tab_index], 'layouts') );
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
    public function edit($id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
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


/* ********************************************************************************************* */    


    /**
     * Show the form for editing the specified resource.
     * GET 
     *
     * @param  
     * @return Response
     */
    public function configurationsEdit()
    {
        $tab_index = 5;

        //$wooconfs = WooConnector::getWooConfigurations();
        // Save Configurations Cache
        // Configuration::updateValue('WOOC_CONFIGURATIONS_CACHE', json_encode($wooconfs));
        // $wooconfs = ['woocommerce_default_country' => WooConnector::getWooSetting( 'woocommerce_default_country' )];

        $wooconfs = [];

        $wooconfs[] = [ 'id' => 'WOOC_DEFAULT_COUNTRY', 'value' => Configuration::get('WOOC_DEFAULT_COUNTRY') ];
        $wooconfs[] = [ 'id' => 'WOOC_CURRENCY', 'value' => Configuration::get('WOOC_CURRENCY') ];
        $wooconfs[] = [ 'id' => 'WOOC_DEF_CURRENCY', 'value' => Configuration::get('WOOC_DEF_CURRENCY') ];
        $wooconfs[] = [ 'id' => 'WOOC_DECIMAL_PLACES', 'value' => Configuration::get('WOOC_DECIMAL_PLACES') ];
        $wooconfs[] = [ 'id' => 'WOOC_PRICES_INCLUDE_TAX', 'value' => Configuration::get('WOOC_PRICES_INCLUDE_TAX') ];
        $wooconfs[] = [ 'id' => 'WOOC_TAX_BASED_ON', 'value' => Configuration::get('WOOC_TAX_BASED_ON') ];
        $wooconfs[] = [ 'id' => 'WOOC_SHIPPING_TAX_CLASS', 'value' => Configuration::get('WOOC_SHIPPING_TAX_CLASS') ];
        $wooconfs[] = [ 'id' => 'WOOC_DEF_SHIPPING_TAX', 'value' => Configuration::get('WOOC_DEF_SHIPPING_TAX') ];
        $wooconfs[] = [ 'id' => 'WOOC_TAX_ROUND_AT_SUBTOTAL', 'value' => Configuration::get('WOOC_TAX_ROUND_AT_SUBTOTAL') ];
        $wooconfs[] = [ 'id' => 'WOOC_TAX_DISPLAY_SHOP', 'value' => Configuration::get('WOOC_TAX_DISPLAY_SHOP') ];
        $wooconfs[] = [ 'id' => 'WOOC_ENABLE_GUEST_CHECKOUT', 'value' => Configuration::get('WOOC_ENABLE_GUEST_CHECKOUT') ];

        return view('woo_connect::woo_configuration_keys.'.'key_group_'.$tab_index, compact('tab_index', 'wooconfs'));
    }

    /**
     * Update the specified resource in storage.
     * PUT 
     *
     * @param  
     * @return Response
     */
    public function configurationsUpdate(Request $request)
    {
        $settings = [];

        // Get Configurations from WooCommerce Shop
        try {

            $groups = WooCommerce::get('settings'); // Array
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

                $set = WooCommerce::get( 'settings/'.$group['id'] );    // Array

                $settings = array_merge($settings, $set);

                // abi_r($set);abi_r('* ***************************************** *');
            }

            catch( WooHttpClientException $e ) {

                // settings/integration : Error: Grupo de ajustes no válido. [rest_setting_setting_group_invalid]

//              return redirect()->route('worders.index')
//                      ->with('error', $e->getMessage());

            }
        }

//      abi_r($settings, true);

        // Save Settings Cache
        // Loose some fat first
        $settings = array_map(function($value){
            $v = [ 'id' => $value['id'], 'description' => $value['label'].' :: '.$value['description'], 'value' => $value['value'], ];
            return $v;
        }, $settings);
        Configuration::updateValue('WOOC_CONFIGURATIONS_CACHE', json_encode($settings));

        // Set some handy values

        Configuration::updateValue('WOOC_DEFAULT_COUNTRY', WooConnector::getWooSetting( 'woocommerce_default_country' ));   // ES:SE
        Configuration::updateValue('WOOC_CURRENCY', WooConnector::getWooSetting( 'woocommerce_currency' ));         // EUR


        // This is currency decimal places, NOT precision in amounts
        // Configuration::updateValue('WOOC_DECIMAL_PLACES', WooConnector::getWooSetting( 'woocommerce_price_num_decimals' )); // 2

//      Configuration::updateValue('WOOC_', WooConnector::getWooSetting( 'woocommerce_weight_unit' ));      // kg
//      Configuration::updateValue('WOOC_', WooConnector::getWooSetting( 'woocommerce_dimension_unit' ));   // cm
        Configuration::updateValue('WOOC_PRICES_INCLUDE_TAX', WooConnector::getWooSetting( 'woocommerce_prices_include_tax' )); // no
        Configuration::updateValue('WOOC_TAX_BASED_ON', WooConnector::getWooSetting( 'woocommerce_tax_based_on' )); // shipping**, billing, base
        

        Configuration::updateValue('WOOC_SHIPPING_TAX_CLASS', WooConnector::getWooSetting( 'woocommerce_shipping_tax_class' )); // ''


        Configuration::updateValue('WOOC_TAX_ROUND_AT_SUBTOTAL', WooConnector::getWooSetting( 'woocommerce_tax_round_at_subtotal' ));   // no   Redondeo de impuesto en el subtotal, en lugar de redondeo por cada línea
        Configuration::updateValue('WOOC_TAX_DISPLAY_SHOP', WooConnector::getWooSetting( 'woocommerce_tax_display_shop' )); // incl**, excl
        Configuration::updateValue('WOOC_ENABLE_GUEST_CHECKOUT', WooConnector::getWooSetting( 'woocommerce_enable_guest_checkout' ));   // no



        $currency = \App\Currency::findByIsoCode( Configuration::get('WOOC_CURRENCY') );
        if ( $currency ) {

            Configuration::updateValue('WOOC_DEF_CURRENCY', $currency->id);

        } else {

            Configuration::updateValue('WOOC_DEF_CURRENCY',  Configuration::get('DEF_CURRENCY'));

            return redirect('wooc/wooconnect/configuration')
                ->with('error', 'No se encontró correspondencia para la Divisa de la Tienda: '.Configuration::get('WOOC_CURRENCY'));

        }



        // Tax calculations
        $local = Configuration::get( 'TAX_BASED_ON_SHIPPING_ADDRESS' );
        $woot  = Configuration::get( 'WOOC_TAX_BASED_ON' );
        $match = 0;
        if ( ($woot == 'shipping') &&  $local ) $match = 1;
        if ( ($woot == 'billing' ) && !$local ) $match = 1;
//        if ( ($woot = 'base' ) &&  ) $match = 1;
        if ( !$match ) {

            return redirect('wooc/wooconnect/configuration')
                ->with('error', 'No se encontró correspondencia para el Método de cálculo del Impuesto en la Tienda: '.$woot.'. Se empleará la Configuración local: TAX_BASED_ON_SHIPPING_ADDRESS = '.$local);

        }



        $tax = \App\Tax::find( \aBillander\WooConnect\WooOrder::getTaxId( Configuration::get('WOOC_SHIPPING_TAX_CLASS') ) );
        if ( $tax ) {

            Configuration::updateValue('WOOC_DEF_SHIPPING_TAX', $tax->id);

        } else {

            Configuration::updateValue('WOOC_DEF_SHIPPING_TAX',  Configuration::get('DEF_TAX'));

            return redirect('wooc/wooconnect/configuration')
                ->with('error', 'No se encontró correspondencia para el Impuesto del Transporte de la Tienda: '.Configuration::get('WOOC_SHIPPING_TAX_CLASS'));

        }



        return redirect('wooc/wooconnect/configuration')
                ->with('success', l('This configuration has been successfully updated', [], 'layouts'));
    }


/* ********************************************************************************************* */    


    /**
     * Show the form for editing the specified resource.
     * GET 
     *
     * @param  
     * @return Response
     */
    public function configurationTaxesEdit()
    {
        $tab_index = 2;

        $wootaxes = WooConnector::getWooTaxes();

// abi_r($wootaxes, true);

        // Save Taxes Cache
        Configuration::updateValue('WOOC_TAXES_CACHE', json_encode($wootaxes));
        // Woo Taxes Dictionary
        $dic = [];
        $dic_val = [];
        foreach ($wootaxes as $wootax) {
            $dic[$wootax['slug']] = WooConnector::getTaxKey( $wootax['slug'] );
            $dic_val[$wootax['slug']] = Configuration::get($dic[$wootax['slug']]);
        }

        $taxList = \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('woo_connect::woo_configuration_keys.'.'key_group_'.$tab_index, compact('tab_index', 'wootaxes', 'dic', 'dic_val', 'taxList'));
    }

    /**
     * Update the specified resource in storage.
     * PUT 
     *
     * @param  
     * @return Response
     */
    public function configurationTaxesUpdate(Request $request)
    {
        // Validation rules
        $rules = [];
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            $rules[ 'dic.'.$key ] = 'sometimes|nullable|exists:taxes,id';
        }

        $this->validate($request, $rules);
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            Configuration::updateValue($key, $val);
        }
        // Save Taxes Dictionary Cache
        $dic_val = [];
        $wootaxes = json_decode(Configuration::get('WOOC_TAXES_CACHE'), true);
        foreach ($wootaxes as $wootax) {
            $dic_val[$wootax['slug']] = Configuration::get(WooConnector::getTaxKey($wootax['slug']));
        }

        Configuration::updateValue('WOOC_TAXES_DICTIONARY_CACHE', json_encode($dic_val));

//      abi_r($request->input('dic'));
//      abi_r($dic_val, true);

        return redirect()->route('wooconnect.configuration.taxes')
                ->with('success', l('This configuration has been successfully updated', [], 'layouts'));
    }


/* ********************************************************************************************* */


    /**
     * Show the form for editing the specified resource.
     * GET 
     *
     * @param  
     * @return Response
     */
    public function configurationPaymentGatewaysEdit()
    {
        $tab_index = 3;

        $woopgates = WooConnector::getWooPaymentGateways();

// abi_r($woopgates, true);

        // Save Payment Gateways Cache
        // Loose some fat first
        $woopgates = array_map(function($value){
            unset($value['settings']);
            return $value;
        }, $woopgates);
        Configuration::updateValue('WOOC_PAYMENT_GATEWAYS_CACHE', json_encode($woopgates));
        // Woo Payment Gateways Dictionary
        $dic = [];
        $dic_val = [];
        foreach ($woopgates as $woopgate) {
            $dic[$woopgate['id']] = WooConnector::getPaymentGatewayKey( $woopgate['id'] );
            $dic_val[$woopgate['id']] = Configuration::get($dic[$woopgate['id']]);
        }

        $pgatesList = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('woo_connect::woo_configuration_keys.'.'key_group_'.$tab_index, compact('tab_index', 'woopgates', 'dic', 'dic_val', 'pgatesList'));
    }

    /**
     * Update the specified resource in storage.
     * PUT 
     *
     * @param  
     * @return Response
     */
    public function configurationPaymentGatewaysUpdate(Request $request)
    {
        // Validation rules
        $rules = [];
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            $rules[ 'dic.'.$key ] = 'sometimes|nullable|exists:payment_methods,id';
        }

        $this->validate($request, $rules);
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            Configuration::updateValue($key, $val);
        }
        // Save Payment Gateways Dictionary Cache
        $dic_val = [];
        $woopgates = json_decode(Configuration::get('WOOC_PAYMENT_GATEWAYS_CACHE'), true);
        foreach ($woopgates as $woopgate) {
            $dic_val[$woopgate['id']] = Configuration::get(WooConnector::getPaymentGatewayKey($woopgate['id']));
        }

        Configuration::updateValue('WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE', json_encode($dic_val));

//      abi_r($request->input('dic'));
//      abi_r($dic_val, true);

        return redirect()->route('wooconnect.configuration.paymentgateways')
                ->with('success', l('This configuration has been successfully updated', [], 'layouts'));
    }


/* ********************************************************************************************* */


    /**
     * Show the form for editing the specified resource.
     * GET 
     *
     * @param  
     * @return Response
     */
    public function configurationShippingMethodsEdit()
    {
        $tab_index = 4;

        $woosmethods = WooConnector::getWooShippingMethods();

// abi_r($woosmethods, true);

        // Save Shipping Methods Cache
        // Loose some fat first
        $woosmethods = array_map(function($value){
            unset($value['_links']);
            return $value;
        }, $woosmethods);
        Configuration::updateValue('WOOC_SHIPPING_METHODS_CACHE', json_encode($woosmethods));
        // Woo Shipping Methods Dictionary
        $dic = [];
        $dic_val = [];
        foreach ($woosmethods as $woosmethod) {
            $dic[$woosmethod['id']] = WooConnector::getShippingMethodKey( $woosmethod['id'] );
            $dic_val[$woosmethod['id']] = Configuration::get($dic[$woosmethod['id']]);
        }

        $smethodsList = \App\ShippingMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('woo_connect::woo_configuration_keys.'.'key_group_'.$tab_index, compact('tab_index', 'woosmethods', 'dic', 'dic_val', 'smethodsList'));
    }

    /**
     * Update the specified resource in storage.
     * PUT 
     *
     * @param  
     * @return Response
     */
    public function configurationShippingMethodsUpdate(Request $request)
    {
        // Validation rules
        $rules = [];
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            $rules[ 'dic.'.$key ] = 'sometimes|nullable|exists:shipping_methods,id';
        }

        $this->validate($request, $rules);
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            Configuration::updateValue($key, $val);
        }
        // Save Payment Gateways Dictionary Cache
        $dic_val = [];
        $woosmethods = json_decode(Configuration::get('WOOC_SHIPPING_METHODS_CACHE'), true);
        foreach ($woosmethods as $woosmethod) {
            $dic_val[$woosmethod['id']] = Configuration::get(WooConnector::getShippingMethodKey($woosmethod['id']));
        }

        Configuration::updateValue('WOOC_SHIPPING_METHODS_DICTIONARY_CACHE', json_encode($dic_val));

//      abi_r($request->input('dic'));
//      abi_r($dic_val, true);

        return redirect()->route('wooconnect.configuration.shippingmethods')
                ->with('success', l('This configuration has been successfully updated', [], 'layouts'));
    }


/* ********************************************************************************************* */    


    /**
     * Say hello! to WooCommerce REST API.
     * GET 
     *
     * @param  
     * @return
     */
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

        return view('woo_connect::woo_configuration_keys.hello', compact('results', 'result', 'customers', 'customer', 'products', 'product', 'sale'));

        // Order status. Options: pending, processing, on-hold, completed, cancelled, refunded and failed. Default is pending.

        // WooCommerce, en la instalación por defecto, incluye siete estados distintos en los que un pedido puede encontrarse:

        // Completado,     Pendiente de pago,    En espera,    Procesando,    Cancelado,    Reembolsado,    Fallido

        // https://www.enriquejros.com/estados-de-pedido-woocommerce/


    }


/* ********************************************************************************************* */


}