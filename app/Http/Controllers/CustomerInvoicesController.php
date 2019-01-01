<?php 

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Customer;
use App\CustomerInvoice;
use App\CustomerInvoiceLine;

use App\Configuration;
use App\Sequence;

class CustomerInvoicesController extends BillableController
{

   public function __construct(Customer $customer, CustomerInvoice $document, CustomerInvoiceLine $document_line)
   {
        parent::__construct();

        $this->model_class = CustomerInvoice::class;

        $this->customer = $customer;
        $this->document = $document;
        $this->document_line = $document_line;
   }

	/**
	 * Display a listing of customer_invoices
	 *
	 * @return Response
	 */
	public function index()
	{
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $documents = $this->document
							->with('customer')
							->with('currency')
							->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
							->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath($this->model_path);

		return view($this->view_path.'.index', $this->modelVars() + compact('documents'));
	}

	/**
	 * Show the form for creating a new customerinvoice
	 *
	 * @return Response
	 */
	public function create()
	{
        $model_path = $this->model_path;
        $view_path = $this->view_path;
//        $model_snake_case = $this->model_snake_case;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();
        
        if ( !(count($sequenceList)>0) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));

    	$payments = \App\PaymentMethod::count();
        if ( !$payments )
            return redirect($this->model_path)
                ->with('error', l('There is not any Payment Method &#58&#58 You must create one first', [], 'layouts'));


        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithCustomer($customer_id)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();
        
        if ( !(count($sequenceList)>0) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));


		// Do the Mambo!!!
        try {
			$customer = \App\Customer::with('addresses')->findOrFail( $customer_id );

        } catch(ModelNotFoundException $e) {
			// No Customer available, ask for one
			return redirect()->back()
                	->with('error', l('The record with id=:id does not exist', ['id' => $customer_id], 'layouts'));
        }
        
        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList', 'customer_id'));
	}

	/**
	 * Store a newly created customerinvoice in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        // Extra data
//        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
//        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

                        'created_via'          => 'manual',
                        'status'               =>  'draft',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );

        $document = $this->document->create($request->all());

		// Move on
		if ($request->has('nextAction'))
		{
			switch ( $request->input('nextAction') ) {
				case 'saveAndConfirm':
					# code...
					$document->confirm();
					// 
					// Vouchers stuff
					// 
					$document->makePaymentDeadlines();
					break;
				
				default:
					# code...
					break;
			}
		}

        // Maybe...
//        if (  Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') )
//            $customerOrder->confirm();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

		/* *********************************************************************** */

#		$document = $this->storeOrUpdate( $request );

		/* *********************************************************************** */
	}

	public function storeOrUpdate( Request $request, $id = null )
	{
		$customer_id = intval($request->input('customer_id', 0));

		/* *********************************************************************** */


		// Do the Mambo!
		$document = ( $id == null ) 
							? new CustomerInvoice() 
							: $this->document->findOrFail($id);

		// STEP 1 : validate data

		// (Basic) Check Shipping Address
		if ( $request->input('shipping_address_id') < 1 ) 
			$request->merge( array('shipping_address_id' => $request->input('invoicing_address_id')) );

		$document_date = $request->input('document_date_form') ?
						  \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('document_date_form') ) : 
						  \Carbon\Carbon::now();
		
		$delivery_date = $request->input('delivery_date_form') ?
						  \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('delivery_date_form') ) :
						  null;

		$dates = [
						'document_date' => $document_date,
						'delivery_date' => $delivery_date,
				 ];
		$request->merge( $dates );


		$rules = CustomerInvoice::$rules;
		// Complete rules for selected Customer
		foreach ($rules as $k => $v) {
	        $rules[$k] = str_replace('{customer_id}', $customer_id, $v);
	    }
	    $rules['nbrlines'] = 'required|numeric|min:' . count( $request->input('lines', []) );

	    if ( !$request->input('delivery_date') ) unset( $rules['delivery_date'] );


		$this->validate($request, $rules);

// abi_r($request->all(), true);

		// STEP 2 : build objects

		if ( !$document->document_reference )
		if ( $request->input('save_as', 'draft') == 'invoice' ) {
			$seq = \App\Sequence::find( $request->input('sequence_id') );
			$doc_id = $seq->getNextDocumentId();
			$extradata = [	'document_prefix'      => $seq->prefix,
							'document_id'          => $doc_id,
							'document_reference'   => $seq->getDocumentReference($doc_id),
							'status'               => 'pending',
						 ];
			$request->merge( $extradata );
		}

//		$document = $this->document->create($request->all());
		$document->fill($request->all());
		$document->save();


		// 
		// Lines stuff
		// 

		// STEP 3 : Delete current lines

		// $document->lines()->delete();
		foreach( $document->lines as $line)
		{
			if ( $line->locked ) continue;		// Skip locked lines
			
			$line->delete();					// Trigger ondelete events
		}

		// STEP 4 : Create new lines

		// Prepare Invoice totals
		$total_tax_incl = 0.0;
		$total_tax_excl = 0.0;

		$line = $this->document_line;

		// Loop...
//	    $address  = \App\Address::find( $request->input('invoicing_address_id'));
	    $customer = \App\Customer::with('address')->find( $document->customer_id );
	    $address  = $customer->address;
//		$n = intval($request->input('nbrlines', 0));
		$form_lines = $request->input('lines');

		// Locked lines :: Add ammounts to document total
		foreach( $document->lines as $line)		// only locked lines are left
		{
			$total_tax_incl += $line->total_tax_incl;
			$total_tax_excl += $line->total_tax_excl;

//				abi_r($total_tax_incl.' - '.$total_tax_excl.' - '.$line->total_tax_incl.' - '.$line->total_tax_excl);
		}

        // Regular lines
        for($i = 0; $i < $request->input('nbrlines'); $i++)
        {
			if ( !$request->has('lines.'.$i.'.lineid') ) continue;	// Line was deleted on View

			if ( $form_lines[$i]['locked'] ) {		// Skip locked lines
				continue;
			}	// Skip locked lines

			if ( !$request->has('lines.'.$i.'.sales_equalization') ) $form_lines[$i]['sales_equalization'] = 0;
        	// Controller: $request->merge(['sales_equalization' => $request->input('sales_equalization', 0)]);

			$line = $this->document_line->create( $form_lines[$i] );

		    // Calculate Taxes & Totals
			if ($form_lines[$i]['product_id']>0) {
				$product  = \App\Product::with('tax')->find( $form_lines[$i]['product_id']);
				$tax = $product->tax;
			} else  {
				// No database persistance, please!
				$product  = new \App\Product(['product_type' => 'simple', 'name' => $line->name, 'tax_id' => $line->tax_id]);
				$tax = $product->tax;
			}
			$customer->sales_equalization = $line->sales_equalization;
			$rules = $product->getTaxRules( $address,  $customer );

			$base_price = $line->quantity*$line->unit_final_price*(1.0-$line->discount_percent/100.0) - $line->discount_amount_tax_excl;				// unit_net_price = unit_final_price - discount_percent

			// Don't know a value for $line->total_tax_excl, since $base_price should be rounded
			$IKnowBase = false;
			$line->total_tax_excl = 0.0;
			$line->total_tax_incl = 0.0;	// After this, loop to add line taxes

			foreach ( $rules as $rule ) {
				$line_tax = new \App\CustomerInvoiceLineTax();

				$line_tax->name = $tax->name . ' | ' . $rule->name;
				$line_tax->tax_rule_type = $rule->rule_type;

				$p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $document->currency, $document->currency_conversion_rate);

				if ($IKnowBase == false) {
					$p->applyRounding( );

					$line->total_tax_incl = $line->total_tax_excl = $base_price = $p->getPrice();
					// Establish $base_price. We do not want different values in different lines due to rounding
					$IKnowBase = !$IKnowBase;
				} else {
					$p->applyRoundingOnlyTax( );
				}

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

//			$line->save();

			$document->CustomerInvoiceLines()->save($line);

			$total_tax_incl += $line->total_tax_incl;
			$total_tax_excl += $line->total_tax_excl;

		}

		$p = \App\Price::create([$total_tax_excl, $total_tax_incl], $document->currency, $document->currency_conversion_rate);
		$p->applyDiscountPercent( $document->document_discount );

		$document->total_tax_excl = $p->getPrice();
		$document->total_tax_incl = $p->getPriceWithTax();
		// Open balance: only payments can modify it
		$document->open_balance = $document->total_tax_incl;

		$document->save();

		return $document;
	}

	/**
	 * Display the specified customerinvoice.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$cinvoice = $this->document
							->with('customer')
							->with('invoicingAddress')
							->with('lines')
							->with('currency')
							->with('payments')
							->findOrFail($id);

		$company = \App\Context::getContext()->company;

//		abi_r($cinvoice, true);

		return view($this->view_path.'.show', compact('cinvoice', 'company'));
	}

	/**
	 * Show the form for editing the specified customerinvoice.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
//		try {
			$document = $this->document->findOrFail($id);
/*
			$invoice = $this->document
								->with('customer')
								->with('invoicingAddress')
								->with('lines')
								->with('currency')
								->findOrFail($id);
*/
//	        } catch(ModelNotFoundException $e) {
				// No Customer Invoice available, naughty boy...
//				return redirect($this->view_path);
//	        }

	    $customer = \App\Customer::find( $document->customer_id );

		$addressBook       = $customer->addresses;

		$theId = $customer->invoicing_address_id;
		$invoicing_address = $addressBook->filter(function($item) use ($theId) {	// Filter returns a collection!
		    return $item->id == $theId;
		})->first();

		$addressbookList = array();
		foreach ($addressBook as $address) {
			$addressbookList[$address->id] = $address->alias;
		}

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'delivery_date', 'export_date'], $document );

		return view($this->view_path.'.edit', $this->modelVars() + compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'document'));
	}

	/**
	 * Update the specified customerinvoice in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, CustomerInvoice $customerinvoice)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);
/*
        // Extra data
        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'document_prefix'      => $seq->prefix,
                        'document_id'          => $doc_id,
                        'document_reference'   => $seq->getDocumentReference($doc_id),

                        'user_id'              => \App\Context::getContext()->user->id,

                        'created_via'          => 'manual',
                        'status'               =>  \App\Configuration::get('CUSTOMER_ORDERS_NEED_VALIDATION') ? 'draft' : 'confirmed',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );
*/
        $document = $customerinvoice;

        $document->fill($request->all());

        // Reset Export date
        // if ( $request->input('export_date_form') == '' ) $document->export_date = null;

        $document->save();

		// Move on
		if ($request->has('nextAction'))
		{
			switch ( $request->input('nextAction') ) {
				case 'saveAndConfirm':
					# code...
					$document->confirm();
					// 
					// Vouchers stuff
					// 
					$document->makePaymentDeadlines();
					break;
				
				default:
					# code...
					break;
			}
		}

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));



		/* *********************************************************************** */

#		$document = $this->storeOrUpdate( $request, $id );

		/* *********************************************************************** */






		/* *********************************************************************** */

		$nextAction = $request->input('nextAction', '');

		if ( $nextAction == 'showInvoice' ) 
			return $this->show($document->id);
		
		if ( $nextAction == 'completeInvoice' ) 
			return redirect('customerinvoices/' . $document->id . '/edit')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

		return redirect('customerinvoices')
				->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
	}

	/**
	 * Remove the specified customerinvoice from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->document->findOrFail($id)->delete();

        return redirect('customerinvoices')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}


    protected function confirm(CustomerInvoice $document)
    {
        $customerinvoice = $document;

        $customerinvoice->confirm();

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customerinvoice->id], 'layouts').' ['.$customerinvoice->document_reference.']');
    }




    /*
    |--------------------------------------------------------------------------
    | Not CRUD stuff here
    |--------------------------------------------------------------------------
    */


/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxLineSearch(Request $request)
    {
        // Request data
        $line_id         = $request->input('line_id');
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id', 0);
        $customer_id     = $request->input('customer_id');
        $sales_rep_id    = $request->input('sales_rep_id', 0);
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $customer_id, $currency_id";

        if ($combination_id>0) {
        	$combination = \App\Combination::with('product')->with('product.tax')->find(intval($combination_id));
        	$product = $combination->product;
        	$product->reference = $combination->reference;
        	$product->name = $product->name.' | '.$combination->name;
        } else {
        	$product = \App\Product::with('tax')->find(intval($product_id));
        }

        $customer = \App\Customer::find(intval($customer_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
        	$sales_rep = \App\SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
        	$sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$customer || !$currency ) {
            // Die silently
            return '';
        }

        $tax = $product->tax;

        // Calculate price per $customer_id now!
        $price = $product->getPriceByCustomer( $customer, $currency );
        $tax_percent = $tax->getFirstRule()->percent;
        $price->applyTaxPercent( $tax_percent );

        $data = [
//			'id' => '',
			'line_sort_order' => '',
			'line_type' => 'product',
			'product_id' => $product->id,
			'combination_id' => $combination_id,
			'reference' => $product->reference,
			'name' => $product->name,
			'quantity' => 1,
			'cost_price' => $product->cost_price,
			'unit_price' => $product->price,
			'unit_customer_price' => $price->getPrice(),
			'unit_final_price' => $price->getPrice(),
			'unit_final_price_tax_inc' => $price->getPriceWithTax(),
			'unit_net_price' => $price->getPrice(),
			'sales_equalization' => $customer->sales_equalization,
			'discount_percent' => 0.0,
			'discount_amount_tax_incl' => 0.0,
			'discount_amount_tax_excl' => 0.0,
			'total_tax_incl' => 0.0,
			'total_tax_excl' => 0.0,
			'tax_percent' => $product->as_percentable($tax_percent),
			'commission_percent' => $sales_rep->commission_percent,
			'notes' => '',
			'locked' => 0,
//			'customer_invoice_id' => '',
			'tax_id' => $product->tax_id,
			'sales_rep_id' => $sales_rep->id,
        ];

        $line = new CustomerInvoiceLine( $data );

        return view('customer_invoices._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
    }


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxLineOtherSearch(Request $request)
    {
        // Request data
        $line_id         = $request->input('line_id');
        $other_json      = $request->input('other_json');
        $customer_id     = $request->input('customer_id');
        $sales_rep_id    = $request->input('sales_rep_id', 0);
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $customer_id, $currency_id";

        if ($other_json) {
        	$product = (object) json_decode( $other_json, true);
        } else {
        	$product = $other_json;
        }

        $customer = \App\Customer::find(intval($customer_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
        	$sales_rep = \App\SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
        	$sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$customer || !$currency ) {
            // Die silently
            return '';
        }

        $tax = \App\Tax::find($product->tax_id);

        // Calculate price per $customer_id now!
        $amount_is_tax_inc = \App\Configuration::get('PRICES_ENTERED_WITH_TAX');
        $amount = $amount_is_tax_inc ? $product->price_tax_inc : $product->price;
        $price = new \App\Price( $amount, $amount_is_tax_inc, $currency );
        $tax_percent = $tax->getFirstRule()->percent;
        $price->applyTaxPercent( $tax_percent );

        $data = [
//			'id' => '',
			'line_sort_order' => '',
			'line_type' => $product->line_type,
			'product_id' => 0,
			'combination_id' => 0,
			'reference' => CustomerInvoiceLine::getTypeList()[$product->line_type],
			'name' => $product->name,
			'quantity' => 1,
			'cost_price' => $product->cost_price,
			'unit_price' => $product->price,
			'unit_customer_price' => $price->getPrice(),
			'unit_final_price' => $price->getPrice(),
			'unit_final_price_tax_inc' => $price->getPriceWithTax(),
			'unit_net_price' => $price->getPrice(),
			'sales_equalization' => $customer->sales_equalization,
			'discount_percent' => 0.0,
			'discount_amount_tax_incl' => 0.0,
			'discount_amount_tax_excl' => 0.0,
			'total_tax_incl' => 0.0,
			'total_tax_excl' => 0.0,
			'tax_percent' => $price->as_percentable($tax_percent),
			'commission_percent' => $sales_rep->commission_percent,
			'notes' => '',
			'locked' => 0,
//			'customer_invoice_id' => '',
			'tax_id' => $product->tax_id,
			'sales_rep_id' => $sales_rep->id,
        ];

        $line = new CustomerInvoiceLine( $data );

        return view('customer_invoices._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
    }

}
