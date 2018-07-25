<?php 

namespace Queridiam\FSxConnector;

use App\Customer as Customer;
use App\Address as Address;
use App\CustomerOrder as Order;
use App\CustomerOrderLine as OrderLine;
use App\CustomerOrderLineTax as OrderLineTax;
use App\Product;
use App\Combination;
use App\Tax;

use App\Configuration;

// use \aBillander\WooConnect\WooOrder;

use App\Traits\LoggableTrait;

class FSxOrder {

    public static  $smethods = NULL;
    public static  $gates = NULL;
    public static  $taxes = NULL;

    protected $data = [];
//    protected $run_status = true;       // So far, so good. Can continue export
//    protected $error = null;

    
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
                $wc_currency = \App\Currency::findOrFail( intval(\App\Configuration::get('WOOC_DEF_CURRENCY')) );
            } catch (ModelNotFoundException $ex) {
                // If Currency does not found. Not any good here...
                $wc_currency = \App\Context::getContext()->currency;    // Or fallback to Configuration::get('DEF_CURRENCY')
            }
*/

            // Do the Mambo!!!
            $params = [
    //          'dp'   => 6,        // WooCommerce serve store some values rounded. 
                                    // Not useful this option. Use WooCommerce API default instead: 2 decimal places
                'dp'   => \App\Configuration::get('WOOC_DECIMAL_PLACES'),
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
        return $this->hasOne('App\Customer', 'webshop_id', 'customer_id');
    }

    /**
     * Get all of the invoices for the WC Order.
     */
    public function invoices()
    {
        return $this->belongsToMany('App\CustomerInvoice', 'parent_child', 'parentable_id', 'childable_id')
                ->wherePivot('parentable_type', 'aBillander\WooConnect\WooOrder')
                ->wherePivot('childable_type', 'App\CustomerInvoice')
                ->withTimestamps();
    }

    public function staple_invoice( $document = null )
    {
        if (!$document) return;

        $this->invoices()->attach($document->id, ['parentable_type' => 'aBillander\WooConnect\WooOrder', 'childable_type' => 'App\CustomerInvoice']);
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


/* ********************************************************************************************* */   


    public function tell_run_status() {
      // return $this->run_status;
    }


}