<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Configuration;

use App\CustomerUser;
use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderLine;

use Mail;

class AbccCustomerOrdersController extends Controller {


   protected $customer_user, $customerOrder, $customerOrderLine;

   public function __construct(CustomerUser $customer_user, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine)
   {
        $this->middleware('auth:customer');

        $this->customer_user = $customer_user;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
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
                            ->where('customer_id', $customer->id)
                            ->withCount('customerorderlines')
//                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $customer_orders = $customer_orders->paginate( 2 );	// \App\Configuration::get('DEF_ITEMS_PERPAGE') );

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
        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;
        $cart = \App\Context::getcontext()->cart;

		// Check
		$rules = CustomerOrder::$rules;
		$cartrules['shipping_address_id'] = str_replace('{customer_id}', $customer->id, $rules['shipping_address_id']);

        $this->validate($request, $cartrules);

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
//			'reference_customer', 
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
			'shipping_address_id'  => $request->input('shipping_address_id'),

			'warehouse_id' => Configuration::get('DEF_WAREHOUSE'),
//			'shipping_method_id' => WooOrder::getShippingMethodId( $order['shipping_lines'][0]['method_id'] ),
//			'sales_rep_id' => $order[''],
			'currency_id' => $cart->currency->id,
			'payment_method_id' => $customer->payment_method_id ?: Configuration::get('DEF_PAYMENT_METHOD'),
//			'template_id' => \App\Configuration::get('DEF_CUSTOMER_INVOICE_TEMPLATE'),
		];

//        return 'OK';

        $customerOrder = $this->customerOrder->create($data);

		// Good boy:
		if ( Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') ) $customerOrder->confirm();
		

        // Lines stuff here in

        foreach ($cart->cartlines as $cartline) {
        	# code...
        	//abi_r($line->quantity);
        	$line = $customerOrder->addProductLine( $cartline->product_id, $cartline->combination_id, $cartline->quantity, ['prices_entered_with_tax' => 0, 'unit_customer_final_price' => $cartline->unit_customer_price] );
        }
		
        // At last: empty cart ( delete lines & initialize )
        $cart->delete();


        // Notify Admin
        // 


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
				'from'     => config('mail.from.address'  ),
				'fromName' => config('mail.from.name'    ),
				'to'       => 'dcomobject@hotmail.com',	// $cinvoice->customer->address->email,
				'toName'   => 'JHircano',	// $cinvoice->customer->name_fiscal,
				'subject'  => l(' :_> New Customer Order #:num', ['num' => $template_vars['document_num']]),
				);

			

			$send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.abcc.new_customer_order', $template_vars, function($message) use ($data)
			{
				$message->from($data['from'], $data['fromName']);

				$message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );	// Will send blind copy to sender!

			});	

        } catch(\Exception $e) {

            return redirect()->route('abcc.orders.index')
                	->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
		    			$e->getMessage());
        }
		// MAIL stuff ENDS



        return redirect()->route('abcc.orders.index')
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
		//
	}




    public function duplicateOrder($id)
    {

        $customer      = Auth::user()->customer;

        $order = $this->customerOrder->where('id', $id)->where('customer_id', $customer->id)->first();

        if (!$order) 
        	return redirect()->route('abcc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        
        return redirect()->route('abcc.cart');



        $order = $this->customerOrder->findOrFail($id);

        // Duplicate BOM
        $clone = $order->replicate();

        // Extra data
        $seq = \App\Sequence::findOrFail( $order->sequence_id );

        // Not so fast, dudde:
/*
        $doc_id = $seq->getNextDocumentId();

        $clone->document_prefix      = $seq->prefix;
        $clone->document_id          = $doc_id;
        $clone->document_reference   = $seq->getDocumentReference($doc_id);
*/
        $clone->user_id              = \App\Context::getContext()->user->id;

        $clone->document_reference = null;
        $clone->reference = '';
        $clone->reference_customer = '';
        $clone->reference_external = '';

        $clone->created_via          = 'manual';
        $clone->status               = 'draft';
        $clone->locked               = 0;
        
        $clone->document_date = \Carbon\Carbon::now();
        $clone->payment_date = null;
        $clone->validation_date = null;
        $clone->delivery_date = null;
        $clone->delivery_date_real = null;
        $clone->close_date = null;
        
        $clone->tracking_number = null;

        $clone->parent_document_id = null;

        $clone->production_sheet_id = null;
        $clone->export_date = null;
        
        $clone->secure_key = null;
        $clone->import_key = '';


        $clone->save();

        // Duplicate BOM Lines
        if ( $order->customerorderlines()->count() )
            foreach ($order->customerorderlines as $line) {

                $clone_line = $line->replicate();

                $clone->customerorderlines()->save($clone_line);

                if ( $line->customerorderlinetaxes()->count() )
                    foreach ($line->customerorderlinetaxes as $linetax) {

                        $clone_line_tax = $linetax->replicate();

                        $clone_line->customerorderlinetaxes()->save($clone_line_tax);

                    }
            }

        // Save Customer order
        $clone->push();

        // Good boy:
        $clone->confirm();


        return redirect('customerorders/'.$clone->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $clone->id], 'layouts'));
    }

}
