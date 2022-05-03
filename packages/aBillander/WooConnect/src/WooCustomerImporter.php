<?php 

namespace aBillander\WooConnect;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use App\Models\Context;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use App\Models\ActivityLogger;

// use \aBillander\WooConnect\WooCustomer;

class WooCustomerImporter {

	protected $wc_customer;
	protected $run_status = true;		// So far, so good. Can continue export
	protected $error = null;

	protected $raw_data = array();

	protected $currency;				// aBillander Object
	protected $customer;				// aBillander Object
	protected $invoicing_address;		// aBillander Object
	protected $shipping_address;		// aBillander Object
	protected $invoicing_address_id;
	protected $shipping_address_id;

	protected $next_sort_customer = 0;

	// Logger to send messages
	protected $log;
	protected $logger;
	protected $log_has_errors = false;

    public function __construct ($customer_id = null, Customer $customer = null)
    {
        // Get logger
        // $this->log = $rwsConnectorLog;

        $this->customer = $customer;

        $this->run_status = true;


        // Start Logger
//        $this->logger = ActivityLogger::setup( 
//            'Import WooCommerce Customers', self::loggerSignature() );			//  :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')

        $this->logger = self::loggerSetup();


        // Get product data (if product exists!)
        if ( intval($customer_id) ) {
            // set the product
            // $this->fill_in_data( intval($product_id) );

            // fill it, parse it and save it
            // $this->populate_data();
            
            // $this->import();  // The whole process

			$this->fill_in_data( intval($customer_id) );
            // return true

        } else {
            $this->logMessage( 'ERROR', 'El número de Cliente <b>"'.$customer_id.'"</b>no es válido.' );
            $this->run_status = false;
        }
    }


    public static function loggerName() 
    {
    	return 'Import WooCommerce Customers';
    }

    public static function loggerSignature() 
    {
    	return __CLASS__;
    }

    public static function loggerSetup() 
    {
    	return ActivityLogger::setup( 
                self::loggerName(), 							//  :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                self::loggerSignature() )
            ->backTo( route('wcustomers.index') );
    }

    public static function logger() 
    {
    	return self::loggerSetup();
    }
    
    /**
     *   Data retriever & Transformer
     */
    public function fill_in_data($customer_id = null)
    {
        
        // 
    	// Get $customer_id data...
//        $data = $this->raw_data = WooConnector::getWooProduct( intval($customer_id) );
        $data = $this->raw_data = WooCustomer::fetch( $customer_id );
        if (!$data) {
            $this->logMessage( 'ERROR', 'Se ha intentado recuperar el Cliente número <b>"'.$customer_id.'"</b> y no existe.' );
            $this->run_status = false;
        } else {

        	// Data transformers
        	;

        }

        return $this->run_status;
    }


    public static function processCustomer( $customer_id = null ) 
    {
        $importer = new static($customer_id);
        if ( !$importer->tell_run_status() ) 
        {
        	$importer->logMessage("ERROR", l('Customer number <span class="log-showoff-format">{$pid}</span> could not be loaded.'), ['$pid' => $customer_id]);

        	return $importer;
        }

//        abi_r($importer->raw_data, true);


        // Do stuff
        // Build Customer data
        $customer = $importer->raw_data;

        // Can download if there are valid data
        $can_download = 1;
        // Name fiscal
        if (!trim(WooOrder::getNameFiscal( $customer )))
            $can_download = 0;
        else
        // Address
        if (!$customer['billing']['address_1'])
            $can_download = 0;
        else
        // Country
        if (!Country::findByIsoCode( $customer['billing']['country'] ))
            $can_download = 0;
        else
        // State
        if (!WooOrder::getState( $customer['billing']['state'], $customer['billing']['country'] ))
            $can_download = 0;
        
        if ( !$can_download )
        {
            $importer->logMessage("ERROR", 'Se ha podido crear el Cliente: <span class="log-showoff-format">{pid}</span> porque faltan datos.', ['pid' => $customer_id]);

            return $importer;
        }


        $language_id = intval( Configuration::get('WOOC_DEF_LANGUAGE') );

        $language_id = $language_id > 0 ? $language_id : 
                        Configuration::get('DEF_LANGUAGE');

        $currency_id = intval( Configuration::get('WOOC_DEF_CURRENCY') );

        $currency_id = $currency_id > 0 ? $currency_id : 
                        Configuration::get('DEF_CURRENCY');

        $data = [
            'name_fiscal'     => WooOrder::getNameFiscal( $customer ),
            'name_commercial' => WooOrder::getNameCommercial( $customer ),

//          'website' => $customer[''],

//            'identification' => WooOrder::getVatNumber( $customer ),

            'webshop_id' => $customer['id'],
            'reference_external' => '',         // $customer['customer_reference_external'],
//          'accounting_id',

//          'payment_days' => $customer[''],
//          'no_payment_month' => $customer[''],

            'outstanding_amount_allowed' => Configuration::get('DEF_OUTSTANDING_AMOUNT'),
//          'outstanding_amount' => $customer[''],
//          'unresolved_amount',

//          'notes' => $customer['customer_note'],
//          'customer_logo',
            'sales_equalization' => WooOrder::getSalesEqualization( $customer ),
//          'allow_login' => $customer[''],

            'accept_einvoice' => 1,
            'blocked' => 0,
            'active'  => 1,

            'currency_id' => $currency_id,
            'language_id' => $language_id,

            'customer_group_id' => Configuration::get('WOOC_DEF_CUSTOMER_GROUP'),
//          'payment_method_id'
//          'carrier_id'
            'price_list_id' => Configuration::get('WOOC_DEF_CUSTOMER_PRICE_LIST'),
        ];

        $abi_customer = Customer::create($data);




        // Build Billing address
        $country    = $customer['billing']['country'];
        $country_id = null;
        $state      = $customer['billing']['state'];
        $state_id   = null;

        $bcountry = Country::findByIsoCode( $customer['billing']['country'] );
        if ($bcountry) {
            $country    = $bcountry->name;
            $country_id = $bcountry->id;
        }

        $bstate = WooOrder::getState( $customer['billing']['state'], $customer['billing']['country'] );
        if ($bstate) {
            $state    = $bstate->name;
            $state_id = $bstate->id;
        }

        $data = [
            'alias' => l('Main Address', [],'addresses'),
            'webshop_id' => WooOrder::getBillingAddressId( $customer ),

            'name_commercial' => WooOrder::getNameCommercial( $customer ),
            
            'address1' => $customer['billing']['address_1'],
            'address2' => $customer['billing']['address_2'],
            'postcode' => $customer['billing']['postcode'],
            'city'         => $customer['billing']['city'],
            'state_name'   => $state,
            'country_name' => $country,
            
            'firstname' => $customer['billing']['first_name'],
            'lastname'  => $customer['billing']['last_name'],
            'email'     => $customer['billing']['email'],

            'phone' => $customer['billing']['phone'],
//          'phone_mobile' => $customer[''],
//          'fax' => $customer[''],
            
            'notes' => null,
            'active' => 1,

//          'latitude' => $customer[''],
//          'longitude' => $customer[''],

            'state_id'   => $state_id,
            'country_id' => $country_id,
        ];

        // abi_r();
        // abi_r($data);die();

        $address = Address::create($data);
        $abi_customer->addresses()->save($address);

        $abi_customer->update(['invoicing_address_id' => $address->id]);


        // Build Shipping address
        if ( WooOrder::getShippingAddressId( $customer ) != $address->webshop_id ) {
                // Shipping is a new Address. Let's create it!

                // Build Shipping address
                $name = $customer['shipping']['company'] ? $customer['shipping']['company'] : 
                        $customer['shipping']['first_name'].' '.$customer['shipping']['last_name'];

                $country    = $customer['shipping']['country'];
                $country_id = null;
                $state      = $customer['shipping']['state'];
                $state_id   = null;

                $scountry = Country::findByIsoCode( $customer['shipping']['country'] );
                if ($scountry) {
                    $country    = $scountry->name;
                    $country_id = $scountry->id;
                }

                $sstate = WooOrder::getState( $customer['shipping']['state'], $customer['shipping']['country'] );
                if ($sstate) {
                    $state    = $sstate->name;
                    $state_id = $sstate->id;
                }

                $data = [
                    'alias' => l('Main Address', [],'addresses'),
                    'webshop_id' => WooOrder::getShippingAddressId( $customer ),

                    'name_commercial' => $name,
                    
                    'address1' => $customer['shipping']['address_1'],
                    'address2' => $customer['shipping']['address_2'],
                    'postcode' => $customer['shipping']['postcode'],
                    'city'         => $customer['shipping']['city'],
                    'state_name'   => $state,
                    'country_name' => $country,
                    
                    'firstname' => $customer['shipping']['first_name'],
                    'lastname'  => $customer['shipping']['last_name'],
                    'email'     => $customer['billing']['email'],           // Better off

                    'phone' => $customer['billing']['phone'],               // Better off
        //          'phone_mobile' => $customer[''],
        //          'fax' => $customer[''],
                    
                    'notes' => null,
                    'active' => 1,

        //          'latitude' => $customer[''],
        //          'longitude' => $customer[''],

                    'state_id'   => $state_id,
                    'country_id' => $country_id,
                ];

                $address = Address::create($data);
                $abi_customer->addresses()->save($address);
        }

        $abi_customer->update(['shipping_address_id' => $address->id]);






        // Build Shipping address
/*        if ( WooOrder::getShippingAddressId( $customer ) != $address->webshop_id ) {
                // Shipping is a new Address. Let's create it!

                $address = $this->createShippingAddress();
                $this->shipping_address_id = $address->id;
        } else {
                        
                $this->shipping_address_id = $this->invoicing_address_id;
        }
        $this->shipping_address = $address;
*/

        // Finish at last!

        $importer->logMessage("INFO", 'Se ha creado el Cliente: <span class="log-showoff-format">{pid}</span> <a class="btn btn-sm btn-warning" href="{route}" title="Ir a" target="_blank"><i class="fa fa-pencil"></i></a> .', ['pid' => $abi_customer->id, 'route' => route('customers.edit', $abi_customer->id)]);


		// Good boy: final touches here


        return $importer;
    }

    
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


/* ********************************************************************************************* */   


    public function tell_run_status() {
      return $this->run_status;
    }
    
    protected function logMessage($level = '', $message = '', $context = [])
    {
        $this->logger->log($level, $message, $context);

        if ( $level == 'ERROR' ) $this->log_has_errors = true;
    }
    
    protected function logInfo($message = '', $context = [])
    {
        $this->logMessage('INFO', $message, $context);
    }
    
    protected function logWarning($message = '', $context = [])
    {
        $this->logMessage('WARNING', $message, $context);
    }
    
    protected function logError($message = '', $context = [])
    {
        $this->logMessage('ERROR', $message, $context);
    }
    
    protected function logTimer($message = '', $context = [])
    {
        $this->logMessage('TIMER', $message, $context);
    }
    
    public function logHasErrors()
    {
        return $this->log_has_errors; 
    }
    
    public function logView()
    {
        return $this->log; 
    }
}