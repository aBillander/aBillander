<?php

namespace aBillander\WooConnect;

// use Illuminate\Database\Eloquent\Model;

use App\Models\Configuration;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\State;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;
use WooCommerce;
// use aBillander\WooConnect\FSxTools;

class WooOrder // extends Model
{
//    protected $dates = ['deleted_at', 'date_created', 'date_paid', 'date_abi_exported', 'date_invoiced'];
	
//    protected $fillable = [ ];

//    protected $guarded = [];

    public static  $smethods = NULL;
    public static  $gates = NULL;
    public static  $taxes = NULL;

    protected $data = [];               // WooC data + 'customer_reference_external' ( FSxTools::translate_customers_fsol() )
    protected $run_status = true;       // So far, so good. Can continue export
    protected $error = null;

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    
    // See: https://github.com/laravel-enso/DataImport

    public function __construct ($order_id = null)
    {
        // Get logger
        // $this->log = $rwsConnectorLog;

        $this->run_status = true;

        // Get order data (if order exists!)
        $oID = intval($order_id);

        if ( $oID > 0 ) {
            // 
/*
            // Too much work. Currency does not change often.
            try {
                $wc_currency = Currency::findOrFail( intval(Configuration::get('WOOC_DEF_CURRENCY')) );
            } catch (ModelNotFoundException $ex) {
                // If Currency does not found. Not any good here...
                $wc_currency = Context::getContext()->currency;    // Or fallback to Configuration::get('DEF_CURRENCY')
            }
*/

            // Do the Mambo!!!
            $params = [
    //          'dp'   => 6,        // WooCommerce serve store some values rounded. 
                                    // Not useful this option. Use WooCommerce API default instead: 2 decimal places
                'dp'   => Configuration::get('WOOC_DECIMAL_PLACES'),
 //               'dp'   => $wc_currency->decimalPlaces,
            ];

            // Get Order fromm WooCommerce Shop
            try {

                $this->data = WooCommerce::get('orders/'.$oID, $params); // Array
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $this->data = [];
                // So far, we do not know if order_id does not exist, or connection fails. 
                // does it matter? -> Anyway, no order is issued

            }

            if (!$this->data) {
                $this->error[] = 'Se ha intentado recuperar el Pedido número <b>"'.$order_id.'"</b> y no existe.';
                $this->run_status = false;
            }

            // Get Customer reference in FactuSOL (if exists)
            $woo_customer = intval($this->data['customer_id']);
            $this->data['customer_reference_external'] = '';        // FSxTools::translate_customers_fsol($woo_customer>0?$woo_customer:$this->data['billing']['email']);

        }

    }

    public static function fetch( $order_id = null )
    {
        $order = new static($order_id);
        // if (!$order->tell_run_status()) 
        return $order->data;
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->hasOne(Customer::class, 'webshop_id', 'customer_id');
    }

    /**
     * Get all of the invoices for the WC Order.
     */
    public function invoices()
    {
        return $this->belongsToMany(CustomerInvoice::class, 'parent_child', 'parentable_id', 'childable_id')
                ->wherePivot('parentable_type', 'aBillander\WooConnect\WooOrder')
                ->wherePivot('childable_type', CustomerInvoice::class)
                ->withTimestamps();
    }

    public function staple_invoice( $document = null )
    {
        if (!$document) return;

        $this->invoices()->attach($document->id, ['parentable_type' => 'aBillander\WooConnect\WooOrder', 'childable_type' => CustomerInvoice::class]);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function getRawData( )
    {
        return $order->data;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    
    // Custom function
    public static function getOrderReference( $order = [] )
    {
		$r = '';

		if ( isset( $order['id'] ) ) {
			$r = 'WooC #'.$order['id'];
		}
		
		return $r;
    }

    public static function getDocumentNotes( $order = [] )
    {
		$n = '';

		if ( isset($order['customer_note']) ) {
			$n .= $order['customer_note']."\n";
		}

		if ( isset( $order['coupon_lines'] ) ) {
	        // Coupons
	        $dp = array();
	        foreach ($order['coupon_lines'] as $coupon) {
	            $dp[] = '"'.$coupon['code'].'"';
	        }
	        $n .= ( count($dp) ? l('Coupons').': '.join(', ', $dp).'.'."\n" : '' );
		}
		
		return $n;
    }

    public static function getMetaByKey( $order = [], $key = '' )
    {
		$vn = '';

		if ( isset( $order['meta_data'] ) && $key ) {
			foreach($order['meta_data']  as $data ) {
				if( $data['key'] == $key ) {
					$vn = $data['value'];
					break;
				}
			}
		}
		
		return $vn;
    }

    public static function getCustomerId( $order = [] )
    {
        $cId = $order['customer_id'] > 0
                ? $order['customer_id']    // Regular Registered Customer
                : -$order['id'];           // Guest Customer. Identify with Order number prefixed with a minus sign

        return $cId;
    }

    public static function getVatNumber( $order = [] )
    {
        if ( isset( $order['billing']['vat_number'] ) && $order['billing']['vat_number'] ) 
             return $order['billing']['vat_number'];

        return self::getMetaByKey( $order, Configuration::get('WOOC_ORDER_NIF_META') );    // 'CIF/NIF' );
    }

    public static function getSalesEqualization( $order = [] )
    {
        // Code here...
        $sales_equalization = 0;

        return $sales_equalization;
    }

    public static function getNameFiscal( $order = [] )
    {
        $name = $order['billing']['company'] ? $order['billing']['company'] : 
                $order['billing']['first_name'].' '.$order['billing']['last_name'];

        return $name;
    }

    public static function getNameCommercial( $order = [] )
    {
        $name = $order['billing']['company'] ? $order['billing']['company'] : 
                $order['billing']['first_name'].' '.$order['billing']['last_name'];

        return $name;
    }

    public static function getAbiExportedDate( $order = [] )
    {
        return self::getMetaByKey( $order, 'date_abi_exported' );
    }

    public static function getAbiInvoicedDate( $order = [] )
    {
        return self::getMetaByKey( $order, 'date_abi_invoiced' );
    }

/*
            $date_downloaded = '';
            $collection = collect($order['meta_data']);

            $meta = $collection->first(function ($item, $key) {
                if ($item['key']=='date_abi_exported') return $item;
            });

            if ($meta) $date_downloaded = $meta['value'];
*/

    public static function getBillingAddressId( $order = [] )
    {
		return self::getAddressId( $order, 'billing' );
    }

    public static function getShippingAddressId( $order = [] )
    {
		return self::getAddressId( $order, 'shipping' );
    }

    public static function getAddressId( $order = [], $address = 'billing' )
    {
		$vn = '';

		if ( isset( $order[$address] ) ) {
			$str = $order[$address]['first_name'].
                   $order[$address]['last_name'].
                   $order[$address]['address_1'].
			       $order[$address]['address_2'].
			       $order[$address]['postcode'].
			       $order[$address]['city'];
			$vn = md5($str);
		}
		
		return $vn;
    }

    
    public static function getExportedAt( $meta_data = [], $meta_key = 'date_abi_exported' )
    {
        $date = null;
		$collection = collect($meta_data);

		$meta = 

		$collection->first(function ($item, $key) use ($meta_key) {
		    if ($item['key']==$meta_key) return $item;
		});

		if ($meta) $date = $meta['value'];

		return $date;
    }
    
    public static function getLineShippingMethodId( $shipping_lines = [] )
    {
        $smi = isset( $shipping_lines[0]['method_id'] ) ? $shipping_lines[0]['method_id'] : '';

        return $smi;
    }
    
    public static function getLineShippingMethodTitle( $shipping_lines = [] )
    {
        $smt = isset( $shipping_lines[0]['method_title'] ) ? $shipping_lines[0]['method_title'] : '';

        return $smt;
    }
    
    public static function getDate( $date = '' )
    {
        $d = str_replace(['T', 't'], ' ', $date);

        return $d;
    }
    
    public static function getDate_gmt( $date = '' )
    {
        if ( !$date ) return (string) \Carbon\Carbon::now();

        $d = str_replace(['T', 't'], ' ', $date);

        return $d;
    }
    
    public static function getDateShort( $date = '' )
    {
        $d = substr($date, 0, 10);

        return $d;
    }
    
    public static function getState( $state = '', $country = '' )
    {
        $s = State::findByIsoCode( (strpos($state, '-') ? '' : $country.'-').$state );

        return $s;
    }
    
    public static function getShippingMethodId( $method = '', $title = '' )
    {
        if (!$method) return null;

        // Mmmm!
        $tokens = explode(':', $method);
        $method = $tokens[0];


        // Dictionary
        if ( !isset(self::$smethods) )
            self::$smethods = json_decode(Configuration::get('WOOC_SHIPPING_METHODS_DICTIONARY_CACHE'), true);

        $smethods = self::$smethods;

        return isset($smethods[$method]) ? $smethods[$method] : null;

        // return Configuration::get('DEF_CUSTOMER_PAYMENT_METHOD');
    }
    
    public static function getPaymentMethodId( $method = '', $title = '' )
    {
        if (!$method) return null;

        // Dictionary
        if ( !isset(self::$gates) )
            self::$gates = json_decode(Configuration::get('WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE'), true);

        $gates = self::$gates;

        return isset($gates[$method]) ? $gates[$method] : null;

        // return Configuration::get('DEF_CUSTOMER_PAYMENT_METHOD');
    }
    
    public static function getTaxId( $slug = '' )
    {
        if (!$slug) 
            $slug = 'standard';

        // Dictionary
        if ( !isset(self::$taxes) )
            self::$taxes = json_decode(Configuration::get('WOOC_TAXES_DICTIONARY_CACHE'), true);

        $taxes = self::$taxes;

        return isset($taxes[$slug]) ? $taxes[$slug] : null;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Transformers
    |--------------------------------------------------------------------------
    */
    
    public static function viewIndexTransformer( $order = [] )
    {
        if ( !isset( $order['id'] ) ) return $order;

        list($order["date_created_date"], $order["date_created_time"]) = explode(' ', self::getDate( $order["date_created"] . ' ' ));

        $order["date_created"] = self::getDateShort( $order["date_created"] );

        $order["date_paid"]    = self::getDateShort( $order["date_paid"] );

        $order["date_abi_exported"] = self::getDateShort( self::getAbiExportedDate( $order ) );

        $order["date_abi_invoiced"] = self::getDateShort( self::getAbiInvoicedDate( $order ) );

        // To Do: Country & State & maybe more...

        return $order;
    }
}
