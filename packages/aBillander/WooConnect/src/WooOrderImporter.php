<?php 

namespace aBillander\WooConnect;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use App\Customer as Customer;
use App\Address as Address;
use App\CustomerInvoice as Invoice;
use App\CustomerInvoiceLine as InvoiceLine;
use App\CustomerInvoiceLineTax as InvoiceLineTax;
use App\Product;
use App\Combination;
use App\Tax;

// use \aBillander\WooConnect\WooOrder;

class WooOrderImporter {

	protected $wc_order;
	protected $run_status = true;		// So far, so good. Can continue export
	protected $error = null;

	protected $raw_data = array();

	protected $currency;				// aBillander Object
	protected $customer;				// aBillander Object
	protected $invoice;					// aBillander Object
	protected $invoicing_address;		// aBillander Object
	protected $shipping_address;		// aBillander Object
	protected $invoicing_address_id;
	protected $shipping_address_id;

	// Logger to send messages
	protected $log;

    public function __construct ($order_id = null)
    {
        // Get logger
        // $this->log = $rwsConnectorLog;

        $this->run_status = true;

        // Get order data (if order exists!)
        if ( intval($order_id) ) {
            // set the product
            // $this->fill_in_data( intval($order_id) );

            // fill it, parse it and save it
            // $this->populate_data();
            
            // $this->import();  // The whole process

			$this->fill_in_data( intval($order_id) );
            // return true

        } else
        	;
    }
    
    /**
     *   Data retriever & Transformer
     */
    public function fill_in_data($order_id = null)
    {
        // 
    	// Get $order_id data...
        $data = $this->raw_data = WooConnector::getWooOrder( intval($order_id) );
        if (!$data) {
            $this->logMessage( 'ERROR', 'Se ha intentado recuperar el Pedido número <b>"'.$order_id.'"</b> y no existe.' );
            $this->run_status = false;
        }

        return $this->run_status;
    }


    public static function makeOrder( $order_id = null ) {
        // See: https://stackoverflow.com/questions/1699796/best-way-to-do-multiple-constructors-in-php
        $importer = new static($order_id);
        if (!$importer->tell_run_status()) return $importer;

        // Do stuff

        $order = $importer->raw_data;

		// Save
		$data = [
            'id' => $order['id'],

            'number'    => $order['number'],
            'order_key' => $order['order_key'],
            'currency'  => $order['currency'],

            'date_created'      => WooOrder::getDate( $order['date_created'] ),
            'date_abi_exported' => WooOrder::getExportedAt($order['meta_data']),

            'total'     => $order['total'],
            'total_tax' => $order['total_tax'],
            
            'customer_id'   => $order['customer_id'],
            'customer_note' => $order['customer_note'],

            'payment_method'        => $order['payment_method'],
            'payment_method_title'  => $order['payment_method_title'],
            'shipping_method_id'    => WooOrder::getShippingMethodId($order['shipping_lines']),
            'shipping_method_title' => WooOrder::getShippingMethodTitle($order['shipping_lines']),
		];


        try {

        	$wc_order = WooOrder::updateOrCreate($data);
		}

		catch( \Exception $e ) {
			$importer->run_status = false;
			$importer->error = $e->getMessage();
		}


        return $importer;

    }


    public static function makeInvoice( $order_id = null ) {
        
        $importer = self::makeOrder($order_id);
        if (!$importer->tell_run_status()) return $importer;

        // Do stuff

        $importer->setCurrency();

        $importer->setCustomer();

        $importer->setInvoiceDocument();

        $importer->setInvoiceVouchers();

        $importer->setInvoicePayments();

        abi_r($importer->invoice->id);

        return $importer;
    }
        
    public function setCurrency()
    {
        $order = $this->raw_data;

        $currency = \App\Currency::findByIsoCode( $order['currency'] );
        if (!$currency) {
        	$currency = \App\Currency::findByIsoCode( \App\Configuration::get('WOOC_DEF_CURRENCY') );
        }
        // To Do: throw error if currency is not found
        $this->currency = $currency;
        // To Do: retrieve conversion rate according to order date
    }

    public function setCustomer()
    {
        // Check if Customer exists; Othrewise, import it
        $customer_webshop_id = $this->raw_data['customer_id'];

        $this->customer = Customer::where('webshop_id', $customer_webshop_id )->first();

        if ($this->customer) $this->checkAddresses();

        else                 $this->importCustomer();
    }

    public function setInvoiceDocument()
    {
        $order = $this->raw_data;

        // Header stuff here in

        $invoice_date = $order['date_paid_gmt'] ? $order['date_paid_gmt'] : $order['date_created_gmt'];

		$data = [
        	'sequence_id' => \App\Configuration::get('WOOC_DEF_INVOICE_SEQUENCE'),
			'customer_id' => $this->customer->id,
//			'document_prefix' => $order[''],
//			'document_id' => $order[''],
//			'document_reference' => $order[''],
			'reference' => WooOrder::getOrderReference( $order ),
//			'document_discount' => $order[''],

			'document_date' => WooOrder::getDate_gmt($invoice_date),
//			'valid_until_date' => $order[''],
//			'delivery_date' => $order[''],
//			'delivery_date_real' => $order[''],
//			'next_due_date' => $order[''],

//			'printed_at' => $order[''],
//			'edocument_sent_at' => $order[''],
//			'customer_viewed_at' => $order[''],
//			'posted_at' => $order[''],

//			'number_of_packages' => $order[''],
//			'shipping_conditions' => $order[''],
//			'tracking_number' => $order[''],

			'prices_entered_with_tax' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX'),
			'round_prices_with_tax'   => \App\Configuration::get('ROUND_PRICES_WITH_TAX'),

			'currency_conversion_rate' => $this->currency->conversion_rate,
//			'down_payment' => $order[''],
			'open_balance' => $order['total'],

//			'total_discounts_tax_incl' => $order[''],
//			'total_discounts_tax_excl' => $order[''],
//			'total_products_tax_incl' => $order[''],
//			'total_products_tax_excl' => $order[''],
//			'total_shipping_tax_incl' => $order[''],
//			'total_shipping_tax_excl' => $order[''],
//			'total_other_tax_incl' => $order[''],
//			'total_other_tax_excl' => $order[''],
			
			'total_tax_incl' => $order['total'],
			'total_tax_excl' => $order['total'] - $order['total_tax'],

//			'commission_amount' => $order[''],

			'notes' => WooOrder::getDocumentNotes( $order ),
//			'notes_to_customer' => $order[''],

			'status' => 'draft',

			'invoicing_address_id' => $this->invoicing_address_id,
			'shipping_address_id'  => $this->shipping_address_id,

//			'warehouse_id' => $order[''],
//			'carrier_id' => $order[''],
//			'sales_rep_id' => $order[''],
			'currency_id' => $this->currency->id,
			'payment_method_id' => WooOrder::getPaymentMethodId( $order['payment_method'], $order['payment_method_title'] ),
			'template_id' => \App\Configuration::get('DEF_CUSTOMER_INVOICE_TEMPLATE'),
		];

        if ( ! \App\Configuration::get('WOOC_SAVE_INVOICE_AS_DRAFT') ) {
			
			$seq = \App\Sequence::find( \App\Configuration::get('WOOC_DEF_INVOICE_SEQUENCE') );
			$doc_id = $seq->getNextDocumentId();
			$extradata = [	'document_prefix'      => $seq->prefix,
							'document_id'          => $doc_id,
							'document_reference'   => $seq->getDocumentReference($doc_id),
							'status'               => 'pending',

							// Need more defaults (i.e., warehouse, carrier, sales rep, template)?
						 ];

			$data = array_merge($data, $extradata);
		}

		$invoice = Invoice::create($data);
        $this->invoice = $invoice;


        // Lines stuff here in

        // Product
        $this->setInvoiceDocumentProductLines();

        // Shipping
        $this->setInvoiceDocumentShippingLines();

        // Fee lines
        $this->setInvoiceDocumentServiceLines();
    }

    /**
     *   Set Document lines
     */
    public function setInvoiceDocumentProductLines()
    {
        $order = $this->raw_data;

        // Lines stuff :: Product lines

foreach ( $order['line_items'] as $item ) {

		// Get Product & Variation
		$sku  = $item['sku'];
		$product_id = null;
		$combination_id = null;
		$reference = '';
		$name = $item['name'];
		$cost_price = $item['price'];
		$final_price = ( $item['subtotal'] )/$item['quantity'];	// Approximation
		$tax_id = WooOrder::getTaxId($item['tax_class']);
		if ( $tax_id == 0 ) {
			// Tax not found. Issue an ERROR.
			$tax_id = \App\Configuration::get('WOOC_DEF_TAX');	// To Do: Improbe this guess...
		}
		$tax = Tax::find($tax_id);
		/*

		*/

		if ($sku=='LEGUM003') $item['variation_id'] = 1;

		/*

		*/
		if ( intval($item['variation_id']) > 0 ) {
			// Search Combination
			$combination = Combination::with('product')->with('product.tax')->where('reference', $sku)->first();
			if ($product) {
				$product = $combination->product;
				$product_id = $product->id;
				$combination_id = $combination->id;
				$reference = $sku;
				if ( \App\Configuration::get('WOOC_USE_LOCAL_PRODUCT_NAME') ) $name = $combination->name(' - ');
				$cost_price = $product->cost_price;
//				$tax_id = $product->tax_id;		// To Do: Match with WooCommerce & Tax Dictionary?
//				$tax = $product->tax;
			} else {
				$this->logMessage( 'WARNING', 'Pedido número <b>"'.$order["id"].'"</b>: NO de ha encontrado el Producto con SKU <b>"'.$sku.'"</b>.' );
			}
		} else {
			// Search Product
			// $combination = (object) ['id' => null];
			$product = Product::with('tax')->where('reference', $sku)->first();
			if ($product) {
				$product_id = $product->id;
				$combination_id = null;
				$reference = $sku;
				if ( \App\Configuration::get('WOOC_USE_LOCAL_PRODUCT_NAME') ) $name = $product->name;
				$cost_price = $product->cost_price;
//				$tax_id = $product->tax_id;		// To Do: Match with WooCommerce & Tax Dictionary?
//				$tax = $product->tax;
			} else {
				$this->logMessage( 'WARNING', 'Pedido número <b>"'.$order["id"].'"</b>: NO de ha encontrado el Producto con SKU <b>"'.$sku.'"</b>.' );
			}
		}

		// If no Product found...
		if (!$product) {
			// No database persistance, please!
			$product  = new \App\Product(['product_type' => 'simple', 'name' => $name, 'tax_id' => $tax_id]);
//			$tax = $product->tax;
		}


		$data = [
//			'line_sort_order' => $item[''],
			'line_type' => 'product',		// product, service, shipping, discount, comment

			'product_id'     => $product_id,
			'combination_id' => $combination_id,
			'reference'      => $reference,
			'name'           => $name,

			'quantity' => $item['quantity'],

			'cost_price' => $cost_price,
			'unit_price' => $final_price,
			'unit_customer_price' => $final_price,	// To Do: if Customer existed, can search for these prices
			'unit_final_price' => $final_price,
			'unit_final_price_tax_inc' => $final_price+$item['subtotal_tax']/$item['quantity'],	// Approximation

//			'sales_equalization' => $item[''],				// Charge Sales equalization tax? (only Spain)

//			'discount_percent' => $item[''],
			'discount_amount_tax_incl' => ( $item['subtotal'] + $item['subtotal_tax'] ) - ( $item['total'] + $item['total_tax'] ),
			'discount_amount_tax_excl' => $item['subtotal'] - $item['total'],

			'total_tax_incl' => $item['total'] + $item['total_tax'],
			'total_tax_excl' => $item['total'],

			'tax_percent' => ($item['total_tax']/$item['total'])*100.0,
//			'commission_percent' => $item[''],

//			'notes' => $item[''],
			
			'locked' => 1,							// 0 -> NO; 1 -> Yes (line is after a shipping-slip => should not mofify quantity).

//			'customer_invoice_id' => $item[''],
			'tax_id' => $tax_id,
//			'sales_rep_id' => $item[''],
		];

		$line = InvoiceLine::create($data);

		// abi_r($line, true);

		$product->tax_id = $tax_id;		// Better clone...
		$rules = $product->getTaxRules( $this->getAddressForTaxCalculation(),  $this->customer );

		$base_price = $line->total_tax_excl;

		$line->total_tax_excl = $base_price;
		$line->total_tax_incl = $base_price;	// After this, loop to add line taxes

		foreach ( $rules as $rule ) {
			$line_tax = new InvoiceLineTax();

			$line_tax->name = $tax->name . ' | ' . $rule->name;
			$line_tax->tax_rule_type = $rule->rule_type;

			$p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $this->currency, $this->currency->currency_conversion_rate);

			$p->applyRoundingOnlyTax( );

			$line_tax->taxable_base = $base_price;
			$line_tax->percent = $rule->percent;
			$line_tax->amount = $rule->amount;
			$line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

			$line_tax->position = $rule->position;

			$line_tax->tax_id = $tax->id;
			$line_tax->tax_rule_id = $rule->id;

			$line_tax->save();
			$line->total_tax_incl += $line_tax->total_line_tax;

			$line->CustomerInvoiceLineTaxes()->save($line_tax);
		}

		$this->invoice->CustomerInvoiceLines()->save($line);

//		abi_r($this->invoice->id.' Done!');

		// NEXT: Issue payment

		// NEXT: Stok movements

}

    }

    public function setInvoiceDocumentServiceLines()
    {
        $order = $this->raw_data;

        // Lines stuff :: Fee lines

		$data = [
		];

    }

    public function setInvoiceDocumentShippingLines()
    {
        $order = $this->raw_data;

        // Lines stuff :: Shipping

foreach ( $order['shipping_lines'] as $item ) {

		// Get Product & Variation
		$product_id = null;
		$combination_id = null;
		$reference = '';
		$name = $item['method_title'];	// .' ('.$item['method_id'].')';
		$cost_price = $item['total'];
		$final_price = $item['total'];
		$tax_id = \App\Configuration::get('WOOC_DEF_SHIPPING_TAX');	// WooOrder::getTaxId('standard');
		if ( $tax_id == 0 ) {
			// Tax not found. Issue an ERROR.
			$tax_id = \App\Configuration::get('WOOC_DEF_TAX');	// To Do: Improbe this guess...
		}
		$tax = Tax::find($tax_id);

		// No database persistance, please!
		$product  = new \App\Product(['product_type' => 'simple', 'name' => $name, 'tax_id' => $tax_id]);
		// $tax = $product->tax;


		$data = [
//			'line_sort_order' => $item[''],
			'line_type' => 'shipping',		// product, service, shipping, discount, comment

			'product_id'     => $product_id,
			'combination_id' => $combination_id,
			'reference'      => $reference,
			'name'           => $name,

			'quantity' => 1,

			'cost_price' => $cost_price,
			'unit_price' => $final_price,
			'unit_customer_price' => $final_price,	// To Do: if Customer existed, can search for these prices
			'unit_final_price' => $final_price,
			'unit_final_price_tax_inc' => $final_price+$item['total_tax'],	// Approximation

//			'sales_equalization' => $item[''],				// Charge Sales equalization tax? (only Spain)

//			'discount_percent' => $item[''],
			'discount_amount_tax_incl' => 0.0,
			'discount_amount_tax_excl' => 0.0,

			'total_tax_incl' => $item['total'] + $item['total_tax'],
			'total_tax_excl' => $item['total'],

			'tax_percent' => ($item['total_tax']/$item['total'])*100.0,
//			'commission_percent' => $item[''],

//			'notes' => $item[''],
			
			'locked' => 1,							// 0 -> NO; 1 -> Yes (line is after a shipping-slip => should not mofify quantity).

//			'customer_invoice_id' => $item[''],
			'tax_id' => $tax_id,
//			'sales_rep_id' => $item[''],
		];

		$line = InvoiceLine::create($data);

		// abi_r($line, true);

		$product->tax_id = $tax_id;		// Better clone...
		$rules = $product->getTaxRules( $this->getAddressForTaxCalculation(),  $this->customer );

		$base_price = $line->total_tax_excl;

		$line->total_tax_excl = $base_price;
		$line->total_tax_incl = $base_price;	// After this, loop to add line taxes

		foreach ( $rules as $rule ) {
			$line_tax = new InvoiceLineTax();

			$line_tax->name = $tax->name . ' | ' . $rule->name;
			$line_tax->tax_rule_type = $rule->rule_type;

			$p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $this->currency, $this->currency->currency_conversion_rate);

			$p->applyRoundingOnlyTax( );

			$line_tax->taxable_base = $base_price;
			$line_tax->percent = $rule->percent;
			$line_tax->amount = $rule->amount;
			$line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

			$line_tax->position = $rule->position;

			$line_tax->tax_id = $tax->id;
			$line_tax->tax_rule_id = $rule->id;

			$line_tax->save();
			$line->total_tax_incl += $line_tax->total_line_tax;

			$line->CustomerInvoiceLineTaxes()->save($line_tax);
		}

		$this->invoice->CustomerInvoiceLines()->save($line);

}

    }

    
    public function setInvoiceVouchers()
    {
    	$invoice = $this->invoice;

    	$ototal = $invoice->total_tax_incl;
		$ptotal = 0;
		$pmethod = $invoice->paymentmethod;
		$dlines = $pmethod->deadlines;
		$pdays = $invoice->customer->paymentDays();
		// $base_date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $customerInvoice->document_date );
		$base_date = $invoice->document_date;

		for($i = 0; $i < count($pmethod->deadlines); $i++)
    	{
    		$next_date = $base_date->copy()->addDays($dlines[$i]['slot']);

    		// Calculate installment due date
    		$due_date = $invoice->customer->paymentDate( $next_date );

    		if ( $i != (count($pmethod->deadlines)-1) ) {
    			$installment = $invoice->as_priceable( $ototal * $dlines[$i]['percentage'] / 100.0, $invoice->currency, true );
    			$ptotal += $installment;
    		} else {
    			// Last Installment
    			$installment = $ototal - $ptotal;
    		}

    		// Create Voucher
    		$data = [	'payment_type' => 'receivable', 
    					'reference' => null, 
                        'name' => ($i+1) . ' / ' . count($pmethod->deadlines), 
//        					'due_date' => \App\FP::date_short( \Carbon\Carbon::parse( $due_date ), \App\Context::getContext()->language->date_format_lite ), 
    					'due_date' => abi_date_short( \Carbon\Carbon::parse( $due_date ) ), 
    					'payment_date' => null, 
                        'amount' => $installment, 
                        'currency_id' => $invoice->currency_id,
                        'currency_conversion_rate' => $invoice->currency_conversion_rate, 
                        'status' => 'pending', 
                        'notes' => $invoice->reference,
                        'document_reference' => $invoice->document_reference,
                    ];

            $payment = \App\Payment::create( $data );
            $invoice->payments()->save($payment);
            $invoice->customer->payments()->save($payment);
    	}
    }

    
    public function setInvoicePayments()
    {
    	// Get vouchers and set them as "paid"
    	$vouchers = $this->invoice->payments;
//    	abi_r($vouchers, true);

    	$payment_date = abi_date_short($this->invoice->document_date);

//    	abi_r($payment_date, true);

    	foreach ($vouchers as $payment) {
    		$payment->payment_date = $payment_date;
    		$payment->status = 'paid';
    		$payment->save();
    	}
    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function importCustomer()
    {
        // Build Customer data
        $order = $this->raw_data;

        $name = $order['billing']['company'] ? $order['billing']['company'] : 
        		$order['billing']['first_name'].' '.$order['billing']['last_name'];

        $language_id = \App\Configuration::get('WOOC_DEF_LANGUAGE') ? \App\Configuration::get('WOOC_DEF_LANGUAGE') : 
							 \App\Configuration::get('DEF_LANGUAGE');

		$data = [
        	'name_fiscal'     => $name,
			'name_commercial' => $name,

//			'website' => $order[''],

			'identification' => WooOrder::getVatNumber( $order ),

			'webshop_id' => $order['customer_id'],

//			'payment_days' => $order[''],
//			'no_payment_month' => $order[''],

			'outstanding_amount_allowed' => \App\Configuration::get('DEF_OUTSTANDING_AMOUNT'),
//			'outstanding_amount' => $order[''],

//			'notes' => $order['customer_note'],
//			'sales_equalization' => $order[''],
//			'allow_login' => $order[''],

			'accept_einvoice' => 1,
			'blocked' => 0,
			'active'  => 1,

			'currency_id' => $this->currency->id,
			'language_id' => $language_id,
		];

		$customer = Customer::create($data);
        $this->customer = $customer;

		// Build Billing address
		$this->invoicing_address = $address = $this->createInvoicingAddress();
		$this->invoicing_address_id = $address->id;


		// Build Shipping address
		if ( WooOrder::getShippingAddressId( $order ) != $address->webshop_id ) {
				// Shipping is a new Address. Let's create it!

				$address = $this->createShippingAddress();
				$this->shipping_address_id = $address->id;
		} else {
				        
				$this->shipping_address_id = $this->invoicing_address_id;
		}
		$this->shipping_address = $address;
   	}


    public function getAddressForTaxCalculation()
    {
    	return \App\Configuration::get('TAX_BASED_ON_SHIPPING_ADDRESS') ? $this->shipping_address : $this->invoicing_address ;
   	}


    public function checkAddresses()
    {
        // Build Customer data
        $order = $this->raw_data;

        // Build Billing address
		$needle = WooOrder::getBillingAddressId( $order );
		$addr = $this->customer->addresses()->where('webshop_id', $needle )->first();
        if ( $addr ) {

	        $this->invoicing_address_id = $addr->id;
	        $this->invoicing_address = $addr;

        } else {
        	
        	// Create Address
        	$address = $this->createInvoicingAddress();

        	$this->invoicing_address_id = $address->id;
        	$this->invoicing_address = $address;

        }

        // Need to update Customer Invoicing Address?
        if ($this->customer->invoicing_address_id != $this->invoicing_address_id) {
        	$this->customer->update( [ 'invoicing_address_id' => $this->invoicing_address_id ] );
        }


		// Build Shipping address
		$needle = WooOrder::getShippingAddressId( $order );
		$addr = $this->customer->addresses()->where('webshop_id', $needle )->first();
        if ( $addr ) {

        	$this->shipping_address_id = $addr->id;
        	$this->shipping_address = $addr;

        } else {
        	
        	// Create Address
        	$address = $this->createShippingAddress();

        	$this->shipping_address_id = $address->id;
        	$this->shipping_address = $address;

        }
    }
    
    public function createInvoicingAddress()
    {
        // Build Customer data
        $order = $this->raw_data;
        $customer = $this->customer;

		// Build Billing address
        $name = $order['billing']['company'] ? $order['billing']['company'] : 
        		$order['billing']['first_name'].' '.$order['billing']['last_name'];

		$country    = $order['billing']['country'];
		$country_id = null;
		$state      = $order['billing']['state'];
		$state_id   = null;

		$bcountry = \App\Country::findByIsoCode( $order['billing']['country'] );
		if ($bcountry) {
			$country    = $bcountry->name;
			$country_id = $bcountry->id;
		}

		$bstate = WooOrder::getState( $order['billing']['state'], $order['billing']['country'] );
		if ($bstate) {
			$state    = $bstate->name;
			$state_id = $bstate->id;
		}

		$data = [
			'alias' => $order['id'].'-Billing',
			'webshop_id' => WooOrder::getBillingAddressId( $order ),

			'name_commercial' => $name,
			
			'address1' => $order['billing']['address_1'],
			'address2' => $order['billing']['address_2'],
			'postcode' => $order['billing']['postcode'],
			'city'         => $order['billing']['city'],
			'state_name'   => $state,
			'country_name' => $country,
			
			'firstname' => $order['billing']['first_name'],
			'lastname'  => $order['billing']['last_name'],
			'email'     => $order['billing']['email'],

			'phone' => $order['billing']['phone'],
//			'phone_mobile' => $order[''],
//			'fax' => $order[''],
			
			'notes' => null,
			'active' => 1,

//			'latitude' => $order[''],
//			'longitude' => $order[''],

			'state_id'   => $state_id,
			'country_id' => $country_id,
		];

        $address = Address::create($data);
        $customer->addresses()->save($address);

        $customer->update(['invoicing_address_id' => $address->id]);
        
        return $address;
    }
    
    public function createShippingAddress()
    {
        // Build Customer data
        $order = $this->raw_data;
        $customer = $this->customer;

		// Build Shipping address
        $name = $order['shipping']['company'] ? $order['shipping']['company'] : 
        		$order['shipping']['first_name'].' '.$order['shipping']['last_name'];

		$country    = $order['shipping']['country'];
		$country_id = null;
		$state      = $order['shipping']['state'];
		$state_id   = null;

		$scountry = \App\Country::findByIsoCode( $order['shipping']['country'] );
		if ($scountry) {
			$country    = $scountry->name;
			$country_id = $scountry->id;
		}

		$sstate = WooOrder::getState( $order['shipping']['state'], $order['shipping']['country'] );
		if ($sstate) {
			$state    = $sstate->name;
			$state_id = $sstate->id;
		}

		$data = [
			'alias' => $order['id'].'-Shipping',
			'webshop_id' => WooOrder::getShippingAddressId( $order ),

			'name_commercial' => $name,
			
			'address1' => $order['shipping']['address_1'],
			'address2' => $order['shipping']['address_2'],
			'postcode' => $order['shipping']['postcode'],
			'city'         => $order['shipping']['city'],
			'state_name'   => $state,
			'country_name' => $country,
			
			'firstname' => $order['shipping']['first_name'],
			'lastname'  => $order['shipping']['last_name'],
//			'email'     => $order['shipping']['email'],

//			'phone' => $order['shipping']['phone'],
//			'phone_mobile' => $order[''],
//			'fax' => $order[''],
			
			'notes' => null,
			'active' => 1,

//			'latitude' => $order[''],
//			'longitude' => $order[''],

			'state_id'   => $state_id,
			'country_id' => $country_id,
		];

        $address = Address::create($data);
        $customer->addresses()->save($address);

        $customer->update(['shipping_address_id' => $address->id]);
        
        return $address;
    }


/* ********************************************************************************************* */   


    public function tell_run_status() {
      return $this->run_status;
    }
    
    protected function logMessage($type, $msg)
    {
        $this->log[] = [$type, $msg];
    }
    
    public function logView()
    {
        return $this->log; 
    }
}