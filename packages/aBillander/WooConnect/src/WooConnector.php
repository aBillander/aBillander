<?php 

namespace aBillander\WooConnect;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Configuration;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;
use WooCommerce;

class WooConnector {    // extends Model {

    public static $woo_keys = [
            'woocommerce_default_country', 'woocommerce_currency', 'woocommerce_weight_unit', 'woocommerce_dimension_unit', 'woocommerce_prices_include_tax', 'woocommerce_tax_based_on', 'woocommerce_shipping_tax_class', 'woocommerce_tax_round_at_subtotal', 'woocommerce_tax_display_shop', 'woocommerce_enable_guest_checkout',
    ];

    public static $statuses = array(
            'pending', 
            'processing', 
            'on-hold',
            'completed',
            'cancelled',
            'refunded',
            'failed',
        );

    public static  $woo_settings = NULL;

//    protected $dates = ['deleted_at'];



    public static function getOrderStatusList()
    {
            $list = [];
            foreach (self::$statuses as $status) {
                $list[$status] = l($status, [], 'woocommerce');
            }

            return $list;
    }

    public static function getOrderStatusName($status)
    {
            return l($status, [], 'woocommerce');
    }

    public static function getTaxKey( $slug = '' )
    {
            return 'WOOC_TAX_'.strtoupper($slug);
    }

    public static function getPaymentGatewayKey( $id = '' )
    {
            return 'WOOC_PAYMENT_GATEWAY_'.strtoupper($id);
    }

    public static function getShippingMethodKey( $id = '' )
    {
            return 'WOOC_SHIPPING_METHOD_'.strtoupper($id);
    }


/* ********************************************************************************************* */   


    public static function getAllWooSettings()
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

        return $settings;
    }


    public static function getWooConfigurations()
    {
        if ( !isset(self::$woo_settings) )
            self::$woo_settings = json_decode(Configuration::get('WOOC_CONFIGURATIONS_CACHE'), true);

        return self::$woo_settings;
    }
    
    public static function getWooSetting( $setting_id = '', $settings = [] )
    {
        if ( empty( (array) $settings) )
            $settings = self::getWooConfigurations();

//        abi_r($settings);

        // abi_r($settings);

        if ( !$setting_id || !$settings ) return null;

        $filtered = array_filter($settings, function($v) use ($setting_id) {
            return $v['id'] == $setting_id;
        });

//        abi_r($filtered, true);

        // Return: string or array
        return reset($filtered)['value'];
    }

    
    public static function getWooOrder( $order_id = 0 )
    {
        $oID = intval($order_id);

        if ( !($oID>0) ) {
            return [];
        }

        // Do the Mambo!!!
        $params = [
//          'dp'   => 6,        // WooCommerce serve store some values rounded. Not useful this option. Use WooCommerce API default instead: 2 decimal places
            'dp'   => Configuration::get('WOOC_DECIMAL_PLACES'),
        ];

        // Get Order fromm WooCommerce Shop
        try {

            $order = WooCommerce::get('orders/'.$oID, $params); // Array
        }

        catch( WooHttpClientException $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            $order = [];
            // So far, we do not know if order_id does not exist, or connection fails. 
            // does it matter? -> Anyway, no order is issued

        }

//      abi_r($order, true);

        return $order;
    }

    
    public static function getWooTaxes()
    {
        // Do the Mambo!!!
        try {

            $taxes = WooCommerce::get('taxes/classes'); // Array
        }

        catch( WooHttpClientException $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            $taxes = [];

        }

        return $taxes;
    }

    
    public static function getWooPaymentGateways()
    {
        // Do the Mambo!!!
        try {

            $gateways = WooCommerce::get('payment_gateways');   // Array
        }

        catch( WooHttpClientException $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            $gateways = [];

        }

//      abi_r($gateways, true);

        return $gateways;
    }

    
    public static function getWooShippingMethods()
    {
        // Do the Mambo!!!
        try {

            $methods = WooCommerce::get('shipping_methods');   // Array
        }

        catch( WooHttpClientException $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            $methods = [];

        }

        return $methods;
    }
}