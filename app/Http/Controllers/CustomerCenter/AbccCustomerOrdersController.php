<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Configuration;
use App\Currency;
use App\Todo;

use App\Cart;

use App\CustomerUser;
use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderLine;
use App\CustomerQuotation;
use App\CustomerQuotationLine;

use Illuminate\Support\Facades\Mail;

class AbccCustomerOrdersController extends Controller {


   protected $customer, $customer_user, $customerOrder, $customerOrderLine;

   public function __construct(Customer $customer, CustomerUser $customer_user, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine, CustomerQuotation $customerQuotation, CustomerQuotationLine $customerQuotationLine)
   {
        // $this->middleware('auth:customer');

        $this->customer = $customer;
        $this->customer_user = $customer_user;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
        $this->customerQuotation = $customerQuotation;
        $this->customerQuotationLine = $customerQuotationLine;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $customer      = Auth::user()->customer;

		$customer_orders = $this->customerOrder
                            ->ofLoggedCustomer()      // Of Logged in Customer (see scope on Billable 
//                            ->where('customer_id', $customer->id)
                            ->withCount('lines')
//                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $customer_orders = $customer_orders->paginate( \App\Configuration::get('ABCC_ITEMS_PERPAGE') );

        $customer_orders->setPath('orders');

        return view('abcc.orders.index', compact('customer_orders'));
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
	 * Get Cart content & push a Customer Order
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // Start Logger
        // $logger = \App\ActivityLogger::setup( 'ABCC Order Validation Profiling', __METHOD__ );

        // $logger->empty();
        // $logger->start();

        // $logger->log("INFO", 'Start the whole thing!');

		$process_as = $request->input('process_as', 'order');
		if ($process_as == 'quotation')
			return $this->storeAsQuotation( $request );
        

        $send_confirmation_email = $request->input('send_confirmation_email', false);

        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;
        // $cart = \App\Context::getcontext()->cart;
        $cart = Cart::getCustomerUserCart();

        if ( $cart->cartlines->where('line_type', 'product')->count() == 0 )
        	return redirect()->back()
                ->with('error', l('Document has no Lines', 'layouts'));


        // Cart Amount
        if( !$cart->isBillable() )
        {
	        	return redirect()->back()
	                ->with('error', l('Document amount should be more than: :amount', ['amount' => abi_money( Auth::user()->canMinOrderValue(), $cart->currency )], 'layouts'));
        }
		
        // $logger->log("INFO", 'Cart loaded');

		// Check. What check?
/*
		$rules = CustomerOrder::$rules;
		$cartrules['shipping_address_id'] = str_replace('{customer_id}', $customer->id, $rules['shipping_address_id']);

        $this->validate($request, $cartrules);
*/

		// Let's rock!
        // Header stuff here in

		$data = [
			'customer_id' => $customer->id,
			'user_id' => $customer_user->id,
        	'sequence_id' => Configuration::get('ABCC_ORDERS_SEQUENCE'),
//			'document_prefix' => $order[''],
//			'document_id' => $order[''],
//			'document_reference' => $order[''],
//			'reference' => WooOrder::getOrderReference( $order ),
			'reference_customer' => $request->input('reference'), 
//			'reference_external' => $order['id'],

			'created_via' => 'abcc',

			'document_date' => \Carbon\Carbon::now(),
//			'payment_date'  => WooOrder::getDate( $order['date_paid'] ),
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

			'currency_conversion_rate' => $cart->currency->conversion_rate,
//			'down_payment' => $order[''],

			'document_discount_percent' => $customer->discount_percent,
			'document_ppd_percent'      => $customer->discount_ppd_percent,

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
			
//			'total_tax_incl' => $order['total'],
//			'total_tax_excl' => $order['total'] - $order['total_tax'],

//			'commission_amount' => $order[''],

			'notes_from_customer' => $request->input('notes'),
//			'notes' => $order[''],
//			'notes_to_customer' => $order[''],

			'status' => 'draft', 	// Sorry: cannot be 'closed' because it is not delivered (even if $order['date_paid'] != 0)
			'locked' => 0,

			'invoicing_address_id' => $customer->invoicing_address_id,
			'shipping_address_id'  => $cart->shipping_address_id,

			'warehouse_id' => Configuration::get('DEF_WAREHOUSE'),
			'shipping_method_id' => $cart->shipping_method_id,	// Do not apply company default, since maybe a Shipping Method for Customer Center
			'sales_rep_id' => $customer->sales_rep_id,
			'currency_id' => $cart->currency->id,
			'payment_method_id' => $cart->payment_method_id,
			'template_id' => \App\Configuration::get('ABCC_DEFAULT_ORDER_TEMPLATE'),
		];


        $customerOrder = $this->customerOrder->create($data);
		
        // $logger->log("INFO", 'Order created (header)');

        // Lines stuff here in

        foreach ($cart->cartlines as $cartline) {
        	# code...
        	// $line = $customerOrder->addProductLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, ['prices_entered_with_tax' => 0, 'unit_customer_final_price' => $cartline->unit_customer_price] );
        	$line_data = [
        			'line_type' => $cartline->line_type,

        			'prices_entered_with_tax'   => 0, 
        			'unit_customer_final_price' => $cartline->unit_customer_final_price, 
        			'line_sort_order'           => $cartline->line_sort_order,

        			'extra_quantity'       => $cartline->extra_quantity, 
        			'extra_quantity_label' => $cartline->extra_quantity_label,

        			'package_measure_unit_id' => $cartline->package_measure_unit_id,
        			'pmu_conversion_rate'     => $cartline->pmu_conversion_rate,
        			'pmu_label'               => $cartline->pmu_label,

        			'sales_equalization' =>  $customer->sales_equalization,
        			'sales_rep_id' =>  $customer->sales_rep_id,
        			'commission_percent' =>  (float) optional($customer->salesrep)->commission_percent,	// Maybe no Sales Rep for this Customer!
        	];
        	
        	// Not all lines are the same type...
        	switch ($cartline->line_type) {
        		case 'shipping':
        			# code...
        			if ( $cartline->unit_customer_final_price > 0.0 )
        			{
        				$line_data['name'] = $cartline->name;
        				$line_data['line_sort_order'] = $cart->getNextLineSortOrder();	// Move at the end of the list
        				$line_data['sales_equalization'] = 0;
        				$line_data['tax_id'] = $cartline->tax_id;	// Otherwise default tax will be taken
	        			$line = $customerOrder->addServiceLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, $line_data );
	        		}
        			break;
        		
        		case 'product':
        		default:
        			# code...
        			$line = $customerOrder->addProductLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, $line_data );
        			break;
        	}

        // $logger->log("INFO", 'Order Line created');
        	
        }
/*
Bah!
        // OK. So far, so good. Lets end with shipping cost!
    	$shipping_data = [
    			'line_type' => 'shipping',

	            'cost_price' => 0.0,
	            'unit_price' => $cart->total_shipping_tax_excl,
	            'discount_percent' => 0.0,
	            'unit_customer_final_price' => $cart->total_shipping_tax_excl,
	            'tax_id' => Configuration::get('ABCC_SHIPPING_TAX'),

    			'prices_entered_with_tax'   => 0, 
    			'unit_customer_final_price' => $cart->total_shipping_tax_excl, 
    			'line_sort_order'           => $cart->getNextLineSortOrder(),	// Move at the end of the list

 //   			'extra_quantity'       => $cartline->extra_quantity, 
 //   			'extra_quantity_label' => $cartline->extra_quantity_label,

    			'sales_equalization' =>  0,
    			'sales_rep_id' =>  $customer->sales_rep_id,
    			'commission_percent' =>  $customer->salesrep->commission_percent,
    	];
*/

		// Good boy:
		if ( Configuration::isFalse('ABCC_ORDERS_NEED_VALIDATION') ) 
		{
			$customerOrder->confirm();
		}

        // $logger->log("INFO", 'Finished Order');
		
        // At last: empty cart ( delete lines & initialize )
        $cart->delete();


        // $logger->log("INFO", 'Cart deleted');


        // Notify Admin
        // 
        // Create Todo
        $data = [
            'name' => l('Preparar un Pedido a un Cliente'), 
            'description' => l('Un Cliente ha realizado un Pedido desde el Centro de Clientes.'), 
            'url' => route('customerorders.edit', [$customerOrder->id]), 
            'due_date' => null, 
            'completed' => 0, 
            'user_id' => $customer_user->id,
        ];

//        $todo = Todo::create($data);


		// MAIL stuff
		try {

			$template_vars = array(
//				'company'       => $company,
				'customer'       => $customer,
				'url' => route('customerorders.edit', [$customerOrder->id]),
				'document_num'   => $customerOrder->document_reference ?: $customerOrder->id,
				'document_date'  => abi_date_short($customerOrder->document_date),
				'document_total' => $customerOrder->as_money('total_tax_incl'),
//				'custom_body'   => $request->input('email_body'),
				);

			$data = array(
				'from'     => abi_mail_from_address(),			// config('mail.from.address'  ),
				'fromName' => abi_mail_from_name(),				// config('mail.from.name'    ),
				'to'       => abi_mail_from_address(),			// $cinvoice->customer->address->email,
				'toName'   => abi_mail_from_name(),				// $cinvoice->customer->name_fiscal,
				'subject'  => l(' :_> New Customer Order #:num', ['num' => $template_vars['document_num']]),

				'bcc'      => $customer_user->email,
				'iso_code' => $customer->language->iso_code ?? \App\Context::getContext()->language->iso_code,
				);

			
/*
			$send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.abcc.new_customer_order', $template_vars, function($message) use ($data)
			{
				$message->from($data['from'], $data['fromName']);

				// $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );	// Will send blind copy to sender!

				$message->to( $data['to'], $data['toName'] )->bcc( $data['bcc'] )->subject( $data['subject'] );
				// $message->to( $data['bcc'], $data['toName'] )->subject( $data['subject'] );

			});
*/
			$send = Mail::to( abi_mail_from_address() )->queue( new \App\Mail\AbccCustomerOrderMail( $data, $template_vars ) );

        // $logger->log("INFO", 'Email sent');

        // $logger->stop();

        } catch(\Exception $e) {

        // $logger->log("INFO", 'Email Error');

        // $logger->stop();

            return redirect()->route('abcc.orders.index')
                	->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
		    			$e->getMessage());
        }

        /* ****************************************************************************************** */

        if ($send_confirmation_email)
        {
        	//
			try {

				$template_vars = array(
	//				'company'       => $company,
					'customer'       => $customer,
					'url' => route('abcc.orders.show', [$customerOrder->id]),
					'document_num'   => $customerOrder->document_reference ?: $customerOrder->id,
					'document_date'  => abi_date_short($customerOrder->document_date),
					'document_total' => $customerOrder->as_money('total_tax_incl'),
	//				'custom_body'   => $request->input('email_body'),
					);

				$data = array(
					'from'     => abi_mail_from_address(),			// config('mail.from.address'  ),
					'fromName' => abi_mail_from_name(),				// config('mail.from.name'    ),
					'to'       => abi_mail_from_address(),			// $cinvoice->customer->address->email,
					'toName'   => abi_mail_from_name(),				// $cinvoice->customer->name_fiscal,
					'subject'  => l(' :_> New Customer Order #:num', ['num' => $template_vars['document_num']]),

					'bcc'      => $customer_user->email,
					'iso_code' => $customer->language->iso_code ?? \App\Context::getContext()->language->iso_code,
					);
				
				$send = Mail::to( $customer_user->email )->queue( new \App\Mail\AbccCustomerOrderMail( $data, $template_vars ) );

	        // $logger->log("INFO", 'Email sent');

	        // $logger->stop();

	        } catch(\Exception $e) {

	            return redirect()->route('abcc.orders.index')
	                	->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
			    			$e->getMessage());
	        }
        }
		// MAIL stuff ENDS



        return redirect()->route('abcc.orders.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerOrder->id], 'layouts'));
	}




	/**
	 * Store a newly created resource in storage.
	 * Get Cart content & push a Customer Order
	 *
	 * @return Response
	 */

	// Maybe in AbccCustomerQuotationsController? I think so.
	public function storeAsQuotation(Request $request)
	{
        $send_confirmation_email = $request->input('send_confirmation_email', false);

        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;
        // $cart = \App\Context::getcontext()->cart;
        $cart = Cart::getCustomerUserCart();

        if ( $cart->cartlines->where('line_type', 'product')->count() == 0 )
        	return redirect()->back()
                ->with('error', l('Document has no Lines', 'layouts'));

		$reference_customer = $request->input('process_as', 'order') == 'quotation' ? 'QUOTATION' : '';
		

		// Check. What check?
/*
		$rules = CustomerOrder::$rules;
		$cartrules['shipping_address_id'] = str_replace('{customer_id}', $customer->id, $rules['shipping_address_id']);

        $this->validate($request, $cartrules);
*/

		// Let's rock!

        // Header stuff here in

		$data = [
			'customer_id' => $customer->id,
			'user_id' => $customer_user->id,
        	'sequence_id' => Configuration::get('ABCC_QUOTATIONS_SEQUENCE'),
//			'document_prefix' => $order[''],
//			'document_id' => $order[''],
//			'document_reference' => $order[''],
//			'reference' => WooOrder::getOrderReference( $order ),
			'reference_customer' => '', 
//			'reference_external' => $order['id'],

			'created_via' => 'abcc',

			'document_date' => \Carbon\Carbon::now(),
//			'payment_date'  => WooOrder::getDate( $order['date_paid'] ),
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

			'currency_conversion_rate' => $cart->currency->conversion_rate,
//			'down_payment' => $order[''],

			'document_discount_percent' => $customer->discount_percent,
			'document_ppd_percent'      => $customer->discount_ppd_percent,

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
			
//			'total_tax_incl' => $order['total'],
//			'total_tax_excl' => $order['total'] - $order['total_tax'],

//			'commission_amount' => $order[''],

			'notes_from_customer' => $request->input('notes'),
//			'notes' => $order[''],
//			'notes_to_customer' => $order[''],

			'status' => 'draft', 	// Sorry: cannot be 'closed' because it is not delivered (even if $order['date_paid'] != 0)
			'locked' => 0,

			'invoicing_address_id' => $customer->invoicing_address_id,
			'shipping_address_id'  => $cart->shipping_address_id,

			'warehouse_id' => Configuration::get('DEF_WAREHOUSE'),
//			'shipping_method_id' => WooOrder::getShippingMethodId( $order['shipping_lines'][0]['method_id'] ),
			'sales_rep_id' => $customer->sales_rep_id,
			'currency_id' => $cart->currency->id,
			'payment_method_id' => $customer->payment_method_id ?: Configuration::get('DEF_PAYMENT_METHOD'),
			'template_id' => \App\Configuration::get('DEF_CUSTOMER_QUOTATION_TEMPLATE'),
		];

//        return 'OK';

        $customerOrder = $this->customerQuotation->create($data);
		

        // Lines stuff here in

        foreach ($cart->cartlines as $cartline) {
        	# code...
        	//abi_r($line->quantity);
        	// $line = $customerOrder->addProductLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, ['prices_entered_with_tax' => 0, 'unit_customer_final_price' => $cartline->unit_customer_price] );
        	$line_data = [
        			'line_type' => $cartline->line_type,

        			'prices_entered_with_tax'   => 0, 
        			'unit_customer_final_price' => $cartline->unit_customer_final_price, 
        			'line_sort_order'           => $cartline->line_sort_order,

        			'extra_quantity'       => $cartline->extra_quantity, 
        			'extra_quantity_label' => $cartline->extra_quantity_label,

        			'package_measure_unit_id' => $cartline->package_measure_unit_id,
        			'pmu_conversion_rate'     => $cartline->pmu_conversion_rate,
        			'pmu_label'               => $cartline->pmu_label,

        			'sales_equalization' =>  $customer->sales_equalization,
        			'sales_rep_id' =>  $customer->sales_rep_id,
        			'commission_percent' =>  (float) optional($customer->salesrep)->commission_percent,	// Maybe no Sales Rep for this Customer!
        	];
        	
        	// Not all lines are the same type...
        	switch ($cartline->line_type) {
        		case 'shipping':
        			# code...
        			if ( $cartline->unit_customer_final_price > 0.0 )
        			{
        				$line_data['name'] = $cartline->name;
        				$line_data['line_sort_order'] = $cart->getNextLineSortOrder();	// Move at the end of the list
        				$line_data['sales_equalization'] = 0;
        				$line_data['tax_id'] = $cartline->tax_id;	// Otherwise default tax will be taken
	        			$line = $customerOrder->addServiceLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, $line_data );
	        		}
        			break;
        		
        		case 'product':
        		default:
        			# code...
        			$line = $customerOrder->addProductLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, $line_data );
        			break;
        	}
        }
		
        // At last: empty cart ( delete lines & initialize )
        $cart->delete();


        // Notify Admin
        // 
        // Create Todo
        $data = [
            'name' => l('Preparar un Presupuesto a un Cliente'), 
            'description' => l('Un Cliente ha solicitado un PRESUPUESTO desde el Centro de Clientes.'), 
            'url' => route('customerquotations.edit', [$customerOrder->id]), 
            'due_date' => null, 
            'completed' => 0, 
            'user_id' => \App\Context::getContext()->user->id,
        ];

        $todo = Todo::create($data);


		// MAIL stuff
		try {

			$template_vars = array(
//				'company'       => $company,
				'document_num'   => $customerOrder->document_reference,
				'document_date'  => abi_date_short($customerOrder->document_date),
				'document_total' => $customerOrder->as_money('total_tax_excl'),
//				'custom_body'   => $request->input('email_body'),
				);

			$data = array(
				'from'     => abi_mail_from_address(),			// config('mail.from.address'  ),
				'fromName' => abi_mail_from_name(),				// config('mail.from.name'    ),
				'to'       => abi_mail_from_address(),			// $cinvoice->customer->address->email,
				'toName'   => abi_mail_from_name(),				// $cinvoice->customer->name_fiscal,
				'subject'  => l(' :_> New Customer Order #:num', ['num' => $template_vars['document_num']]),
				);

			

			$send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.abcc.new_customer_quotation', $template_vars, function($message) use ($data)
			{
				$message->from($data['from'], $data['fromName']);

				$message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );	// Will send blind copy to sender!

			});

        } catch(\Exception $e) {

            return redirect()->route('abcc.quotations.index')
                	->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
		    			$e->getMessage());
        }
		// MAIL stuff ENDS



        return redirect()->route('abcc.quotations.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerOrder->id], 'layouts'));
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

        $customer      = Auth::user()->customer;

        $order = $this->customerOrder
                            ->with('customer')
        					->with('lines')
        					->with('currency')
        					->where('id', $id)
        					->where('customer_id', $customer->id)
        					->first();

        if (!$order) 
        	return redirect()->route('abcc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));

		return view('abcc.orders.show', compact('order'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request)
	{
		// 
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
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
		// You naughty boy! 
	}




    public function duplicateOrder($id)
    {

        $customer      = Auth::user()->customer;

        $order = $this->customerOrder
        					->where('id', $id)
        					->where('customer_id', $customer->id)
                            ->withCount('lines')
        					->first();

        if (!$order) 
        	return redirect()->route('abcc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        
        $cart = \App\Context::getContext()->cart;

        foreach ($order->lines as $orderline) {
        	# code...
        	if ( $orderline->line_type        != 'product' ) continue;
        	if ( $orderline->unit_final_price == 0.0       ) continue;
        	$line = $cart->addLine($orderline->product_id, $orderline->combination_id, $orderline->quantity);
        }

        return redirect()->route('abcc.cart')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $order->id], 'layouts'));
    }


	public function showPdf($id, Request $request)
	{

        $customer      = Auth::user()->customer;

        $document = $this->customerOrder->where('id', $id)->where('customer_id', $customer->id)->first();

        if (!$document) 
        	return redirect()->route('abcc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        

        $company = \App\Context::getContext()->company;

        // Get Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('ABCC_DEFAULT_ORDER_TEMPLATE') );

        if ( !$t )
        	return redirect()->route('abcc.orders.show', $id)
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        // $document->template = $t;

        $template = $t->getPath( 'CustomerOrder' );

        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.
        
        // Catch for errors
		try{
        		$pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
		}
		catch(\Exception $e){

		    	return redirect()->route('abcc.orders.show', $id)
                	->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').$e->getMessage());
		}

        // PDF stuff ENDS

        $pdfName    = 'order_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');


        return redirect()->route('abcc.orders.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
	}
}
