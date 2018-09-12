<?php 

namespace aBillander\WooConnect;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use App\Customer as Customer;
use App\Address as Address;
use App\CustomerOrder as Order;
use App\CustomerOrderLine as OrderLine;
use App\CustomerOrderLineTax as OrderLineTax;
use App\Product;
use App\Combination;
use App\Tax;

use \aBillander\WooConnect\WooOrder;

// use \aBillander\WooConnect\WooOrder;

class WooOrderImporter {

	protected $wc_order;
	protected $run_status = true;		// So far, so good. Can continue export
	protected $error = null;

	protected $raw_data = array();

	protected $currency;				// aBillander Object
	protected $customer;				// aBillander Object
	protected $order;					// aBillander Object
	protected $invoicing_address;		// aBillander Object
	protected $shipping_address;		// aBillander Object
	protected $invoicing_address_id;
	protected $shipping_address_id;

	protected $next_sort_order = 0;

	// Logger to send messages
	protected $log;
	protected $logger;
	protected $log_has_errors = false;

    public function __construct ($order_id = null, Order $order = null)
    {
        // Get logger
        // $this->log = $rwsConnectorLog;

        $this->order = $order;

        $this->run_status = true;


        // Start Logger
//        $this->logger = \App\ActivityLogger::setup( 
//            'Import WooCommerce Orders', self::loggerSignature() );			//  :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')

        $this->logger = self::loggerSetup();


        // Get order data (if order exists!)
        if ( intval($order_id) ) {
            // set the product
            // $this->fill_in_data( intval($order_id) );

            // fill it, parse it and save it
            // $this->populate_data();
            
            // $this->import();  // The whole process

			$this->fill_in_data( intval($order_id) );
            // return true

        } else {
            $this->logMessage( 'ERROR', 'El número de Pedido <b>"'.$order_id.'"</b>no es válido.' );
            $this->run_status = false;
        }
    }


    public static function loggerName() 
    {
    	return 'Import WooCommerce Orders';
    }

    public static function loggerSignature() 
    {
    	return __CLASS__;
    }

    public static function loggerSetup() 
    {
    	return \App\ActivityLogger::setup( 
            self::loggerName(), 							//  :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            self::loggerSignature() );
    }

    public static function logger() 
    {
    	return self::loggerSetup();
    }
    
    /**
     *   Data retriever & Transformer
     */
    public function fill_in_data($order_id = null)
    {
    	// Already downloaded?
    	$abi_order = Order::where('reference_external', $order_id)->first();
    	if ( $abi_order ) {
            $this->logMessage( 'ERROR', 'El Pedido número <b>"'.$order_id.'"</b> ya se descargó: '.$abi_order->id.' ('.$abi_order->document_reference.').' );
            $this->run_status = false;
        }
        
        // 
    	// Get $order_id data...
//        $data = $this->raw_data = WooConnector::getWooOrder( intval($order_id) );
        $data = $this->raw_data = WooOrder::fetch( $order_id );
        if (!$data) {
            $this->logMessage( 'ERROR', 'Se ha intentado recuperar el Pedido número <b>"'.$order_id.'"</b> y no existe.' );
            $this->run_status = false;
        } else {
        	// Data transformers
        	;

        }

        return $this->run_status;
    }
    
    public function getNextLineSortOrder()
    {
        $this->next_sort_order += 10;;

        return $this->next_sort_order;
    }


    public static function processOrder( $order_id = null ) 
    {
        $importer = new static($order_id);
        if ( !$importer->tell_run_status() ) 
        {
        	$importer->logMessage("ERROR", l('Order number <span class="log-showoff-format">{oid}</span> could not be loaded.'), ['oid' => $order_id]);

        	return $importer;
        }

//        abi_r($importer->raw_data, true);

        // Do stuff

        // Some preparatory checks
        $importer->setCurrency();

        // Mambo!
        $importer->setCustomer();
        if ( !$importer->tell_run_status() ) 
        {
        	$importer->logMessage("ERROR", l('Order number <span class="log-showoff-format">{oid}</span> could not be loaded.'), ['oid' => $order_id]);

        	return $importer;
        }

        $importer->setOrderDocument();
        if ( !$importer->tell_run_status() ) 
        {
        	$importer->logMessage("ERROR", l('Order number <span class="log-showoff-format">{oid}</span> could not be loaded.'), ['oid' => $order_id]);

        	$importer->order->delete();

        	// Delete Customer as well -> No! Customer order is in place no matter import errors...

        	return $importer;
        }


		// Good boy:
		$importer->order->confirm();

		// $importer->order->makeTotals();


		// Check totals
		$diffTaxExc = ($importer->raw_data['total'] - $importer->raw_data['total_tax']) - $importer->order->total_tax_excl;
		$diffTaxInc =  $importer->raw_data['total']                                     - $importer->order->total_tax_incl;

		if ( $diffTaxExc != 0 )
			$importer->logWarning( l('Order number <span class="log-showoff-format">{oid}</span> Total Tax Excluded difference: {diffTaxExc}.'), ['oid' => $order_id, 'diffTaxExc' => $diffTaxExc]);

		if ( $diffTaxInc != 0 )
			$importer->logWarning( l('Order number <span class="log-showoff-format">{oid}</span> Total Tax Included difference: {diffTaxInc}.'), ['oid' => $order_id, 'diffTaxInc' => $diffTaxInc]);


        return $importer;
    }
        
    public function setCurrency()
    {
        $order = $this->raw_data;

        $currency = \App\Currency::findByIsoCode( $order['currency'] );
        if (!$currency) {
        	$currency = \App\Currency::find( \App\Configuration::get('WOOC_DEF_CURRENCY') );

        	$this->logMessage("WARNING", l('Order number <span class="log-showoff-format">{oid}</span> Currency not found. Using: <span class="log-showoff-format">{name}</span>.'), ['oid' => $order['id'], 'name' => $currency->name]);
        }

        $this->currency = $currency;
        // To Do: retrieve conversion rate according to order date <= Kind of advanced...
    }

    public function setCustomer()
    {
        // Check if Customer exists; Othrewise, import it
        $customer_webshop_id = $this->raw_data['customer_id'];

        $customer_reference_external = $this->raw_data['customer_reference_external'];

        $this->customer = Customer::where('webshop_id', $customer_webshop_id )->first();

        // Customer already in FactuSOL?
        // ^-- lo sé mirando el diccionario de clientes wp_fsx_dic


//        $this->customer = Customer::where('webshop_id', $customer_webshop_id )->first();

//        abi_r(Customer::all(), true);

//        $this->logMessage('INFO', $this->raw_data['customer_id'].' - '.($this->customer ? $this->customer->id : ''));

        if ($this->customer) {

        	if ( !$customer_reference_external && $this->customer->reference_external ) {

        		// Update diccionario de fsxweb
        		\aBillander\WooConnect\FSxTools::new_customers_entry($customer_webshop_id, $customer_reference_external);
        		
        	}

        	if ( $customer_reference_external && !$this->customer->reference_external ) {

        		// Update customer
        		$this->customer->update(['reference_external' => $customer_reference_external]);
        		
        	}

        	if ( $customer_reference_external && $this->customer->reference_external && ( $customer_reference_external != $this->customer->reference_external ) ) {

        		// Error gordo!
				$this->run_status = false;

				$this->logError( l('Los campos que relacionan el Cliente entre la Tienda web, aBillander y FactuSOL no coinciden.') );

				// Rock n Roll is over! 
				return ;
        	}

        	// Good boy!
        	$this->checkAddresses();

        } else {

        	// Let's try this way:
        	if ( $customer_reference_external && ($this->customer = Customer::where('reference_external', $customer_reference_external )->first()) )
        	{

        		// Unrealistic:
        		if ( $this->customer->webshop_id ) {

        			// Error gordo!
					$this->run_status = false;

					$this->logError( l('Los campos que relacionan el Cliente entre la Tienda web, aBillander y FactuSOL no coinciden.') );

					// Rock n Roll is over! 
					return ;
	        	}

    			$this->customer->update(['webshop_id' => $customer_webshop_id]);

	        	// Good boy!
	        	$this->checkAddresses();

        	} else {
	        	
	        	$this->importCustomer();

        	}
        }

//        if ($this->customer) $this->checkAddresses();

//        else                 $this->importCustomer();
    }

    public function setOrderDocument()
    {
        $order = $this->raw_data;

        // Header stuff here in

		$data = [
			'customer_id' => $this->customer->id,
			'user_id' => \App\Context::getContext()->user->id,
        	'sequence_id' => \App\Configuration::get('WOOC_DEF_ORDERS_SEQUENCE'),
//			'document_prefix' => $order[''],
//			'document_id' => $order[''],
//			'document_reference' => $order[''],
			'reference' => WooOrder::getOrderReference( $order ),
//			'reference_customer', 
			'reference_external' => $order['id'],

			'created_via' => 'webshop',

			'document_date' => WooOrder::getDate( $order['date_created'] ),
			'payment_date'  => WooOrder::getDate( $order['date_paid'] ),
//			'valid_until_date' => $order[''],
//			'delivery_date' => $order[''],
//			'delivery_date_real' => $order[''],
//			'close_date' => $order[''],

//			'document_discount_percent',
//			'document_discount_amount_tax_incl' => $order['discount_total'],
//			'document_discount_amount_tax_excl' => $order['discount_total'] - $order['discount_tax'],

			'number_of_packages' => 1,
//			'shipping_conditions' => $order[''],

//			'prices_entered_with_tax' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX'),
//			'round_prices_with_tax'   => \App\Configuration::get('ROUND_PRICES_WITH_TAX'),

			'currency_conversion_rate' => $this->currency->conversion_rate,
//			'down_payment' => $order[''],

//			'total_discounts_tax_incl' => $order[''],
//			'total_discounts_tax_excl' => $order[''],
//			'total_products_tax_incl' => $order[''],
//			'total_products_tax_excl' => $order[''],
//			'total_shipping_tax_incl' => $order[''],
//			'total_shipping_tax_excl' => $order[''],
//			'total_other_tax_incl' => $order[''],
//			'total_other_tax_excl' => $order[''],
			
//			'total_lines_tax_incl' => $order['total'],
//			'total_lines_tax_excl' => $order['total'] - $order['total_tax'],
			
			'total_tax_incl' => $order['total'],
			'total_tax_excl' => $order['total'] - $order['total_tax'],

//			'commission_amount' => $order[''],

			'notes_from_customer' => WooOrder::getDocumentNotes( $order ),
//			'notes' => $order[''],
//			'notes_to_customer' => $order[''],

			'status' => 'draft', 	// Sorry: cannot be 'closed' because it is not delivered (even if $order['date_paid'] != 0)
			'locked' => 1,

			'invoicing_address_id' => $this->invoicing_address_id,
			'shipping_address_id'  => $this->shipping_address_id,

			'warehouse_id' => \App\Configuration::get('DEF_WAREHOUSE'),
			'shipping_method_id' => WooOrder::getShippingMethodId( $order['shipping_lines'][0]['method_id'] ),
//			'sales_rep_id' => $order[''],
			'currency_id' => $this->currency->id,
			'payment_method_id' => WooOrder::getPaymentMethodId( $order['payment_method'], $order['payment_method_title'] ),
//			'template_id' => \App\Configuration::get('DEF_CUSTOMER_INVOICE_TEMPLATE'),
		];

        	
		$seq = \App\Sequence::find( \App\Configuration::get('WOOC_DEF_ORDERS_SEQUENCE') );

		if ( !$seq ) 
		{
			$this->run_status = false;

			$this->logError( l('No existe una Serie de Documentos para descargar los Pedidos. Asigne una en en el menú de Configuración.') );

			// Rock n Roll is over! 
			return ;
		}

		// Not so fast, dudde:
/*
		$doc_id = $seq->getNextDocumentId();

		$extradata = [	'document_prefix'      => $seq->prefix,
						'document_id'          => $doc_id,
						'document_reference'   => $seq->getDocumentReference($doc_id),
					 ];

		$data = array_merge($data, $extradata);
*/
//		abi_r($data, true);

		$order = Order::create($data);
/*
		$order->total_tax_incl = $order['total'];
		$order->total_tax_excl = $order['total'] - $order['total_tax'];
		$order->save();
*/
        $this->order = $order;


        // Lines stuff here in

        // Product
        $this->setOrderDocumentProductLines();
        if ( !$this->tell_run_status() ) 
        {
        	return ;
        }


        // Shipping
        $this->setOrderDocumentShippingLines();
        if ( !$this->tell_run_status() ) 
        {
        	return ;
        }


        // Fee lines
        $this->setOrderDocumentServiceLines();
        if ( !$this->tell_run_status() ) 
        {
        	return ;
        }


        // Coupon lines
        $this->setOrderDocumentCouponLines();
        if ( !$this->tell_run_status() ) 
        {
        	return ;
        }
    }

    /**
     *   Set Document lines
     */
    public function setOrderDocumentProductLines()
    {
        // abi_r('**>>>>>>>   '.$this->order->getLastLineSortOrder());

        $order = $this->raw_data;

        // Lines stuff :: Product lines

foreach ( $order['line_items'] as $i => $item ) {

		// Get Product & Variation
		$sku  = $item['sku'];
		$product_id = null;
		$combination_id = null;
		$reference = '';
		$name = $item['name'];
//		$cost_price = $item['price'];
		$final_price = $item['price']; // ( $item['subtotal'] )/$item['quantity'];	// Approximation. Maybe better: $item['price']  (price before discount) ???
		$line_tax_id = WooOrder::getTaxId($item['tax_class']);
/*
		if ( !$tax_id ) {
			// Tax not found. Issue an ERROR.
			$tax_id = \App\Configuration::get('WOOC_DEF_TAX');	// To Do: Improbe this guess...

			$this->logMessage( 'WARNING', 'Pedido número <b>"'.$order["id"].'"</b>: NO de ha encontrado correspondencia del Impuesto <b>"'.$item['tax_class'].'"</b>.' );
		}

		$tax = Tax::find($tax_id);
*/
		/*

		*/

//		if ($sku=='LEGUM003') $item['variation_id'] = 1;

		/*

		*/
		$product = null;
		if ( intval($item['variation_id']) > 0 ) {
			// Search Combination
			$combination = Combination::with('product')->with('product.tax')->where('reference', $sku)->first();
			if ($combination) {
				$product = $combination->product;
				$product_id = $product->id;
				$combination_id = $combination->id;
				$reference = $sku;
				if ( \App\Configuration::get('WOOC_USE_LOCAL_PRODUCT_NAME') ) $name = $combination->name(' - ');
				$cost_price = $product->cost_price;
//				$tax_id = $product->tax_id;		// To Do: Match with WooCommerce & Tax Dictionary?
//				$tax = $product->tax;
			} else {
				$this->run_status = false;

				$this->logError( 'Pedido número <b>"'.$order["id"].'"</b>: NO de ha encontrado el Producto (Variación) con SKU <b>"'.$sku.'"</b>.' );

				// Rock n Roll is over! 
				return ;
			}
		} else {
			// Search Product
			// $combination = (object) ['id' => null];
			$product = Product::with('tax')->where('reference', $sku)->first();
			if ($product) {
				$product_id = $product->id;
				$combination_id = null;
				$reference = $product->reference;
				if ( \App\Configuration::get('WOOC_USE_LOCAL_PRODUCT_NAME') ) $name = $product->name;
				$cost_price = $product->cost_price;
//				$tax_id = $product->tax_id;		// To Do: Match with WooCommerce & Tax Dictionary?
//				$tax = $product->tax;
			} else {
				$this->run_status = false;

				$this->logError( 'Pedido número <b>"'.$order["id"].'"</b>: NO de ha encontrado el Producto con SKU <b>"'.$sku.'"</b>.' );

				// Rock n Roll is over! 
				return ;
			}
		}
/*
		//
		// Testing
			$product = Product::with('tax')->find(21);
				$product_id = $product->id;
				$combination_id = null;
				$reference = $product->reference;
				if ( \App\Configuration::get('WOOC_USE_LOCAL_PRODUCT_NAME') ) $name = $product->name;
				$cost_price = $product->cost_price;
		//
		//
*/

		// Let's get (really) dirty, you naughty!
		$cost_price = $product->cost_price;
		$unit_price = $product->price;

        $tax = $product->tax;
        $tax_id = $product->tax_id;
        $sales_equalization = $this->customer->sales_equalization;

        if ( $line_tax_id != $tax_id ) {
			$this->run_status = false;

        	// Taxes mismatch
			$this->logError( 'Pedido número <b>"'.$order["id"].'"</b>: NO hay correspondencia del Impuesto para el Producto con SKU <b>"'.$sku.'"</b>.' );

			// Rock n Roll is over! 
			return ;
        }


		$data = [
			'line_sort_order' => $this->getNextLineSortOrder(),		// 10*($i+1),
			'line_type' => 'product',		// product, service, shipping, discount, comment

			'product_id'     => $product_id,
			'combination_id' => $combination_id,
			'reference'      => $reference,
			'name'           => $name,

			'quantity' => $item['quantity'],
			'measure_unit_id' => $product->measure_unit_id,

			'cost_price' => $cost_price,
			'unit_price' => $unit_price,
            'unit_customer_price' => $unit_price,	// To Do: if Customer existed, can search for these prices
            'unit_customer_final_price' => $unit_price,

			'unit_final_price' => $final_price,
			'unit_final_price_tax_inc' => $final_price+$item['subtotal_tax']/$item['quantity'],	// Approximation

			'sales_equalization' => $sales_equalization,				// Charge Sales equalization tax? (only Spain)

//			'discount_percent' => $item[''],
			'discount_amount_tax_incl' => ( $item['subtotal'] + $item['subtotal_tax'] ) - ( $item['total'] + $item['total_tax'] ),
			'discount_amount_tax_excl' => $item['subtotal'] - $item['total'],

			'total_tax_incl' => $item['total'] + $item['total_tax'],
			'total_tax_excl' => $item['total'],

			'tax_percent' => ($item['total'] != 0.0) ? ($item['total_tax']/$item['total'])*100.0 : 0.0,
//			'commission_percent' => $item[''],

//			'notes' => $item[''],
			
			'locked' => 1,							// 0 -> NO; 1 -> Yes (line is after a shipping-slip => should not mofify quantity).

//			'customer_order_id' => $item[''],
			'tax_id' => $tax_id,
//			'sales_rep_id' => $item[''],
		];

		$line = OrderLine::create($data);

		$this->order->customerorderlines()->save($line);

		// abi_r($line, true);

		// Let's deal with taxes
		$product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->getAddressForTaxCalculation(),  $this->customer );

		$base_price = $line->total_tax_excl;

		$line->total_tax_excl = $base_price;
		$line->total_tax_incl = $base_price;	// After this, loop to add line taxes

		foreach ( $rules as $rule ) {
			$line_tax = new OrderLineTax();

			$line_tax->name = $rule->fullName;
			$line_tax->tax_rule_type = $rule->rule_type;

			$p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $this->currency, $this->currency->currency_conversion_rate);

//			abi_r($line, true);

			$p->applyRounding( );

			$line_tax->taxable_base = $base_price;
			$line_tax->percent = $rule->percent;
			$line_tax->amount = $rule->amount;
			$line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

			$line_tax->position = $rule->position;

			$line_tax->tax_id = $tax->id;
			$line_tax->tax_rule_id = $rule->id;

			$line_tax->save();
			$line->total_tax_incl += $line_tax->total_line_tax;

			$line->customerorderlinetaxes()->save($line_tax);
            $line->save();
		}

        // Now, update Order Totals
        $this->order->makeTotals();

}

		// So far, so good! Bye, then!

    }

    public function setOrderDocumentCouponLines()
    {
        $order = $this->raw_data;

        // Lines stuff :: Fee lines

		if ( count($order['coupon_lines']) ) 
		{
				$this->run_status = false;

				$this->logError( 'Order number <b>"'.$order["id"].'"</b>: cannot process Coupon Lines.' );

				// Rock n Roll is over! 
				return ;
		}

    }

    public function setOrderDocumentServiceLines()
    {
        $order = $this->raw_data;

        // Lines stuff :: Fee lines

		if ( count($order['fee_lines']) ) 
		{
				$this->run_status = false;

				$this->logError( 'Order number <b>"'.$order["id"].'"</b>: cannot process Fee Lines.' );

				// Rock n Roll is over! 
				return ;
		}

    }

    public function setOrderDocumentShippingLines()
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
		$unit_price = $item['total'];
		$final_price = $item['total'];
//		$line_tax_id = WooOrder::getTaxId($item['tax_class']);
		$tax_id = intval(\App\Configuration::get('WOOC_DEF_SHIPPING_TAX'));	// WooOrder::getTaxId('standard');
		if ( $tax_id == 0 ) {
			// Tax not found. Issue an ERROR.
			$tax_id = \App\Configuration::get('WOOC_DEF_TAX');	// To Do: Improbe this guess...
			// -- This key does not exist?? --^
		}
		$tax = Tax::find($tax_id);
		// Not a Product, hence:
        $sales_equalization = 0;	// $this->customer->sales_equalization;

		// No database persistance, please!
		$product  = new \App\Product(['product_type' => 'simple', 'name' => $name, 'tax_id' => $tax_id]);
		// $tax = $product->tax;

// abi_r('>> # >> '.$this->order->customerorderlines->count());

		$data = [
			'line_sort_order' => $this->getNextLineSortOrder(), // $this->order->getLastLineSortOrder(),
			'line_type' => 'shipping',		// product, service, shipping, discount, comment

			'product_id'     => $product_id,
			'combination_id' => $combination_id,
			'reference'      => $reference,
			'name'           => $name,

			'quantity' => 1,
			'measure_unit_id' => \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS'),

			'cost_price' => $cost_price,
			'unit_price' => $unit_price,
			'unit_customer_price' => $unit_price,
            'unit_customer_final_price' => $unit_price,

			'unit_final_price' => $final_price,
			'unit_final_price_tax_inc' => $final_price+$item['total_tax'],	// Approximation

			'sales_equalization' => $sales_equalization,				// Charge Sales equalization tax? (only Spain)

//			'discount_percent' => $item[''],
			'discount_amount_tax_incl' => 0.0,
			'discount_amount_tax_excl' => 0.0,

			'total_tax_incl' => $item['total'] + $item['total_tax'],
			'total_tax_excl' => $item['total'],

			'tax_percent' => ($item['total'] != 0.0) ? ($item['total_tax']/$item['total'])*100.0 : 0.0,
//			'commission_percent' => $item[''],

//			'notes' => $item[''],
			
			'locked' => 1,							// 0 -> NO; 1 -> Yes (line is after a shipping-slip => should not mofify quantity).

//			'customer_order_id' => $item[''],
			'tax_id' => $tax_id,
//			'sales_rep_id' => $item[''],
		];

		$line = OrderLine::create($data);

		$this->order->customerorderlines()->save($line);

		// abi_r($line, true);

		$product->sales_equalization = $sales_equalization;
		$rules = $product->getTaxRules( $this->getAddressForTaxCalculation(),  $this->customer );

		$base_price = $line->total_tax_excl;

		$line->total_tax_excl = $base_price;
		$line->total_tax_incl = $base_price;	// After this, loop to add line taxes

		foreach ( $rules as $rule ) {
			$line_tax = new OrderLineTax();

			$line_tax->name = $rule->fullName;
			$line_tax->tax_rule_type = $rule->rule_type;

			$p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $this->currency, $this->currency->currency_conversion_rate);

			$p->applyRounding( );

			$line_tax->taxable_base = $base_price;
			$line_tax->percent = $rule->percent;
			$line_tax->amount = $rule->amount;
			$line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

			$line_tax->position = $rule->position;

			$line_tax->tax_id = $tax->id;
			$line_tax->tax_rule_id = $rule->id;

			$line_tax->save();
			$line->total_tax_incl += $line_tax->total_line_tax;

			$line->customerorderlinetaxes()->save($line_tax);
            $line->save();
		}

        // Now, update Order Totals
        $this->order->makeTotals();

}

		// So far, so good! Bye, then!

    }

    
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function importCustomer()
    {
        // Data transformers

        // Spanish VAT Identification
        // Sometimes customer use "Company" field....
        if ( \App\Customer::check_spanish_nif_cif_nie( $this->raw_data['billing']['company'] ) > 0 )
        {
        	$this->raw_data['billing']['vat_number'] = \App\Customer::normalize_spanish_nif_cif_nie( $this->raw_data['billing']['company'] );
        	$this->raw_data['billing']['company']    = '';
        }
        
        if ( \App\Customer::check_spanish_nif_cif_nie( $this->raw_data['shipping']['company'] ) > 0 )
        {
        	$this->raw_data['shipping']['vat_number'] = \App\Customer::normalize_spanish_nif_cif_nie( $this->raw_data['shipping']['company'] );
        	$this->raw_data['shipping']['company']    = '';
        }



        // Build Customer data
        $order = $this->raw_data;

        $language_id = intval(\App\Configuration::get('WOOC_DEF_LANGUAGE'));

        $language_id = $language_id > 0 ? $language_id : 
						\App\Configuration::get('DEF_LANGUAGE');

		$data = [
        	'name_fiscal'     => WooOrder::getNameFiscal( $order ),
			'name_commercial' => WooOrder::getNameCommercial( $order ),

//			'website' => $order[''],

			'identification' => WooOrder::getVatNumber( $order ),

			'webshop_id' => $order['customer_id'],
			'reference_external' => $order['customer_reference_external'],
//			'accounting_id',

//			'payment_days' => $order[''],
//			'no_payment_month' => $order[''],

			'outstanding_amount_allowed' => \App\Configuration::get('DEF_OUTSTANDING_AMOUNT'),
//			'outstanding_amount' => $order[''],
//			'unresolved_amount',

//			'notes' => $order['customer_note'],
//			'customer_logo',
			'sales_equalization' => WooOrder::getSalesEqualization( $order ),
//			'allow_login' => $order[''],

			'accept_einvoice' => 1,
			'blocked' => 0,
			'active'  => 1,

			'currency_id' => $this->currency->id,
			'language_id' => $language_id,

			'customer_group_id' => \App\Configuration::get('WOOC_DEF_CUSTOMER_GROUP'),
//			'payment_method_id'
//			'carrier_id'
			'price_list_id' => \App\Configuration::get('WOOC_DEF_CUSTOMER_PRICE_LIST'),
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

		// Finish at last!
		$this->logMessage("INFO", l('A new Customer <span class="log-showoff-format">[{cid}] {name}</span> has been created for Order number <span class="log-showoff-format">{oid}</span>.'), ['cid' => $this->customer->id, 'oid' => $order['id'], 'name' => $this->customer->name_fiscal]);
   	}


    public function getAddressForTaxCalculation()
    {
    	return (\App\Configuration::get('WOOC_TAX_BASED_ON') == 'shipping') ? $this->shipping_address : $this->invoicing_address ;
    	// Don't care if \App\Configuration::get('WOOC_TAX_BASED_ON') == 'local'
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
//        $name = $order['billing']['company'] ? $order['billing']['company'] : 
//        		$order['billing']['first_name'].' '.$order['billing']['last_name'];

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
			'alias' => $order['id'].l('-Billing'),
			'webshop_id' => WooOrder::getBillingAddressId( $order ),

			'name_commercial' => WooOrder::getNameCommercial( $order ),
			
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
			'alias' => $order['id'].l('-Shipping'),
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