<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Customer as Customer;
use App\CustomerInvoice as CustomerInvoice;
use App\CustomerInvoiceLine as CustomerInvoiceLine;

use App\Configuration as Configuration;

class CustomerInvoicesController extends Controller {


   protected $customer, $customerInvoice, $customerInvoiceLine;

   public function __construct(Customer $customer, CustomerInvoice $customerInvoice, CustomerInvoiceLine $customerInvoiceLine)
   {
        $this->customer = $customer;
        $this->customerInvoice = $customerInvoice;
        $this->customerInvoiceLine = $customerInvoiceLine;
   }

	/**
	 * Display a listing of customer_invoices
	 *
	 * @return Response
	 */
	public function index()
	{
        $customer_invoices = $this->customerInvoice
							->with('customer')
							->with('currency')
							->with('paymentmethod')
							->orderBy('id', 'desc')->get();

		return view('customer_invoices.index', compact('customer_invoices'));
	}

	/**
	 * Show the form for creating a new customerinvoice
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		// Some checks to start with:

    	$sequenceList = \App\Sequence::listFor('CustomerInvoice');
        if ( !$sequenceList )
            return redirect('customerinvoices')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));

    	$payments = \App\PaymentMethod::count();
        if ( !$payments )
            return redirect('customerinvoices')
                ->with('error', l('There is not any Payment Method &#58&#58 You must create one first', [], 'layouts'));


        if ( !$request->has('customer_id') ) { 
			// No Customer available, ask for one
			return view('customer_invoices.create');
		}

		// Do the Mambo!!!
        try {
			$customer = \App\Customer::with('addresses')->findOrFail( $request->input('customer_id') );

        } catch(ModelNotFoundException $e) {
			// No Customer available, ask for one
			return view('customer_invoices.create');
        }
		
		// Prepare & retrieve customer data to fill in the form
		// Should check Invoicing Address (at least)
		$addressBook = $customer->addresses;
/*
		$theId = $customer->invoicing_address_id;
		$invoicing_address = $addressBook->filter(function($item) use ($theId) {	// Filter returns a collection!
		    return $item->id == $theId;
		})->first();

		$addressbookList = array();
		foreach ($addressBook as $address) {
			$addressbookList[$address->id] = $address->alias;
		}
*/
		$invoicing_address = $customer->invoicing_address();
//		$addressbookList   = $customer->getAddressList();
		$addressbookList   = $addressBook->pluck( 'alias', 'id' )->toArray();

		$currency_id = $customer->currency_id > 0 ? $customer->currency_id : \App\Context::getContext()->currency->id;
        try {
            if ($currency_id == \App\Context::getContext()->currency->id) 
            	$currency = \App\Context::getContext()->currency;
           	else 
           		$currency = \App\Currency::findOrFail($currency_id);

        } catch(ModelNotFoundException $e) {

            $currency = \App\Context::getContext()->currency;		// Really???
        }

		// Prepare Customer Invoice default data
		$invoice = $this->customerInvoice;

		$invoice->customer_id          = $customer->id;

		$invoice->sequence_id          = $customer->sequence_id > 0 ? $customer->sequence_id : Configuration::get('DEF_CUSTOMER_INVOICE_SEQUENCE');
		$invoice->document_reference   = '';

		$invoice->reference            = '';
		$invoice->document_discount    = 0.0;

		$invoice->document_date        = \Carbon\Carbon::now();
		$invoice->document_date_form   = abi_date_short( \Carbon\Carbon::now() );		// Formatted date for view
		
		$invoice->delivery_date        = $invoice->document_date;
		$invoice->delivery_date_form   = $invoice->document_date_form;

		$invoice->printed_at         = null;
		$invoice->edocument_sent_at  = null;
		$invoice->customer_viewed_at = null;
		$invoice->posted_at          = null;

		$invoice->number_of_packages   = 1;
		$invoice->shipping_conditions  = '';
		$invoice->tracking_number      = '';

		$invoice->prices_entered_with_tax  = Configuration::get('PRICES_ENTERED_WITH_TAX');
		$invoice->round_prices_with_tax    = Configuration::get('ROUND_PRICES_WITH_TAX');

		$invoice->currency_conversion_rate = $currency->conversion_rate;
		$invoice->down_payment         = 0.0;
		$invoice->open_balance         = 0.0;

		$invoice->total_tax_incl     = 0.0;
		$invoice->total_tax_excl     = 0.0;

		$invoice->commission_amount  = 0.0;

		$invoice->notes         = '';
		$invoice->status        = 'draft';
//			$invoice->einvoice      = $customer->accept_einvoice;

		$invoice->invoicing_address_id = $customer->invoicing_address_id;
		$invoice->shipping_address_id  = $customer->shipping_address_id > 0 ? $customer->shipping_address_id : $customer->invoicing_address_id;
		$invoice->warehouse_id         = Configuration::get('DEF_WAREHOUSE');
		$invoice->carrier_id           = $customer->carrier_id  > 0 ? $customer->carrier_id  : Configuration::get('DEF_CARRIER');
		$invoice->sales_rep_id         = $customer->sales_rep_id > 0 ? $customer->sales_rep_id : 0;
		$invoice->currency_id          = $currency->id;
		$invoice->payment_method_id    = $customer->payment_method_id > 0 ? $customer->payment_method_id : Configuration::get('DEF_CUSTOMER_PAYMENT_METHOD');
		$invoice->template_id          = $customer->template_id > 0 ? $customer->template_id : Configuration::get('DEF_CUSTOMER_INVOICE_TEMPLATE');
//			$invoice->parent_document_id   = null;

		$invoice->customerInvoiceLines = collect([]);

		return view('customer_invoices.create', compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'invoice', 'sequenceList'));

	}

	/**
	 * Store a newly created customerinvoice in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$customer_id = intval($request->input('customer_id', 0));

		if ( $request->has('submitCustomer_id')) 
		{
	        try {
				$customer = \App\Customer::findOrFail( $customer_id );

	        } catch(ModelNotFoundException $e) {
				// No Customer available, ask for one
				return view('customer_invoices.create');
	        }
			
			// Valid Customer found: Redirect and collect invoice data
			return redirect('customerinvoices/create?customer_id='.$customer_id);
			// return $this->create($request);
		}

		/* *********************************************************************** */

		$customerInvoice = $this->storeOrUpdate( $request );

		/* *********************************************************************** */

		$nextAction = $request->input('nextAction', '');

		if ( $nextAction == 'showInvoice' ) 
			return $this->show($customerInvoice->id);
		
		if ( $nextAction == 'completeInvoice' ) 
			return redirect('customerinvoices/' . $customerInvoice->id . '/edit')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerInvoice->id], 'layouts'));

		return redirect('customerinvoices')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerInvoice->id], 'layouts'));

	}

	public function storeOrUpdate( Request $request, $id = null )
	{
		$customer_id = intval($request->input('customer_id', 0));

		/* *********************************************************************** */


		// Do the Mambo!
		$customerInvoice = ( $id == null ) 
							? new CustomerInvoice() 
							: $this->customerInvoice->findOrFail($id);

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

		if ( !$customerInvoice->document_reference )
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

//		$customerInvoice = $this->customerInvoice->create($request->all());
		$customerInvoice->fill($request->all());
		$customerInvoice->save();


		// 
		// Lines stuff
		// 

		// STEP 3 : Delete current lines

		// $customerInvoice->customerInvoiceLines()->delete();
		foreach( $customerInvoice->customerInvoiceLines as $line)
		{
			if ( $line->locked ) continue;		// Skip locked lines
			
			$line->delete();					// Trigger ondelete events
		}

		// STEP 4 : Create new lines

		// Prepare Invoice totals
		$total_tax_incl = 0.0;
		$total_tax_excl = 0.0;

		$line = $this->customerInvoiceLine;

		// Loop...
//	    $address  = \App\Address::find( $request->input('invoicing_address_id'));
	    $customer = \App\Customer::with('address')->find( $customerInvoice->customer_id );
	    $address  = $customer->address;
//		$n = intval($request->input('nbrlines', 0));
		$form_lines = $request->input('lines');

		// Locked lines :: Add ammounts to document total
		foreach( $customerInvoice->customerInvoiceLines as $line)		// only locked lines are left
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

			$line = $this->customerInvoiceLine->create( $form_lines[$i] );

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

				$p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $customerInvoice->currency, $customerInvoice->currency_conversion_rate);

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

			$customerInvoice->CustomerInvoiceLines()->save($line);

			$total_tax_incl += $line->total_tax_incl;
			$total_tax_excl += $line->total_tax_excl;

		}

		$p = \App\Price::create([$total_tax_excl, $total_tax_incl], $customerInvoice->currency, $customerInvoice->currency_conversion_rate);
		$p->applyDiscountPercent( $customerInvoice->document_discount );

		$customerInvoice->total_tax_excl = $p->getPrice();
		$customerInvoice->total_tax_incl = $p->getPriceWithTax();
		// Open balance: only payments can modify it
		$customerInvoice->open_balance = $customerInvoice->total_tax_incl;

		$customerInvoice->save();

		return $customerInvoice;
	}

	/**
	 * Display the specified customerinvoice.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$cinvoice = $this->customerInvoice
							->with('customer')
							->with('invoicingAddress')
							->with('customerInvoiceLines')
							->with('currency')
							->with('payments')
							->findOrFail($id);

		$company = \App\Context::getContext()->company;

//		abi_r($cinvoice, true);

		return view('customer_invoices.show', compact('cinvoice', 'company'));
	}

	/**
	 * Show the form for editing the specified customerinvoice.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		try {
			$invoice = $this->customerInvoice
								->with('customer')
								->with('invoicingAddress')
								->with('customerInvoiceLines')
								->with('currency')
								->findOrFail($id);

	        } catch(ModelNotFoundException $e) {
				// No Customer Invoice available, naughty boy...
				return redirect('customerinvoices');
	        }

	    $sequenceList =  ($invoice->status == 'draft') ?
	    					\App\Sequence::listFor('CustomerInvoice') : [] ;

		$customer = \App\Customer::find( $invoice->customer_id );

		$addressBook       = $customer->addresses;

		$theId = $customer->invoicing_address_id;
		$invoicing_address = $addressBook->filter(function($item) use ($theId) {	// Filter returns a collection!
		    return $item->id == $theId;
		})->first();

		$addressbookList = array();
		foreach ($addressBook as $address) {
			$addressbookList[$address->id] = $address->alias;
		}

		$invoice->document_date_form   = abi_date_short( $invoice->document_date );
		$invoice->delivery_date_form   = abi_date_short( $invoice->delivery_date );

		return view('customer_invoices.edit', compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'invoice', 'sequenceList'));
	}

	/**
	 * Update the specified customerinvoice in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{

		/* *********************************************************************** */

		$customerInvoice = $this->storeOrUpdate( $request, $id );

		/* *********************************************************************** */



		// 
		// Vouchers stuff
		// 
		if ( !$customerInvoice->draft && 1) {

			$customerInvoice->payments()->delete();

			$ototal = $customerInvoice->total_tax_incl - $customerInvoice->down_payment;
			$ptotal = 0;
			$pmethod = $customerInvoice->paymentmethod;
			$dlines = $pmethod->deadlines;
			$pdays = $customerInvoice->customer->paymentDays();
			// $base_date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $customerInvoice->document_date );
			$base_date = $customerInvoice->document_date;

			for($i = 0; $i < count($pmethod->deadlines); $i++)
        	{
        		$next_date = $base_date->copy()->addDays($dlines[$i]['slot']);

        		// Calculate installment due date
        		$due_date = $customerInvoice->customer->paymentDate( $next_date );

        		if ( $i != (count($pmethod->deadlines)-1) ) {
        			$installment = $customerInvoice->as_priceable( $ototal * $dlines[$i]['percentage'] / 100.0, $customerInvoice->currency, true );
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
                            'currency_id' => $customerInvoice->currency_id,
                            'currency_conversion_rate' => $customerInvoice->currency_conversion_rate, 
                            'status' => 'pending', 
                            'notes' => null,
                            'document_reference' => $customerInvoice->document_reference,
                        ];

                $payment = \App\Payment::create( $data );
                $customerInvoice->payments()->save($payment);
                $customerInvoice->customer->payments()->save($payment);
/*
                $payment->invoice_id = $customerInvoice->id;
                $payment->model_name = 'CustomerInvoice';
                $payment->owner_id = $customerInvoice->customer->id;
                $payment->owner_model_name = 'Customer';

                $payment->save();
*/
                // ToDo: update Invoice next due date
        	}
			
		}





		/* *********************************************************************** */

		$nextAction = $request->input('nextAction', '');

		if ( $nextAction == 'showInvoice' ) 
			return $this->show($customerInvoice->id);
		
		if ( $nextAction == 'completeInvoice' ) 
			return redirect('customerinvoices/' . $customerInvoice->id . '/edit')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerInvoice->id], 'layouts'));

		return redirect('customerinvoices')
				->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customerInvoice->id], 'layouts'));
	}

	/**
	 * Remove the specified customerinvoice from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->customerInvoice->findOrFail($id)->delete();

        return redirect('customerinvoices')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}




    /*
    |--------------------------------------------------------------------------
    | Not CRUD stuff here
    |--------------------------------------------------------------------------
    */

	protected function invoice2pdf($id)
	{
		// PDF stuff
		try {
			$cinvoice = CustomerInvoice::
							  with('customer')
							->with('invoicingAddress')
							->with('customerInvoiceLines')
							->with('currency')
							->with('template')
							->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return Redirect::route('customers.index')
                     ->with('error', 'La Factura de Cliente id='.$id.' no existe.');
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

		$company = Company::find( intval(Configuration::get('DEF_COMPANY')) );

		$template = 'customer_invoices.templates.' . $cinvoice->template->file_name;
		$paper = $cinvoice->template->paper;	// A4, letter
		$orientation = $cinvoice->template->orientation;	// 'portrait' or 'landscape'.
		
		$pdf 		= PDF::loadView( $template, compact('cinvoice', 'company') )
							->setPaper( $paper )
							->setOrientation( $orientation );
		// PDF stuff ENDS
		
		return 	$pdf;
	}

	public function showpdf($id, Request $request)
	{
		// PDF stuff
		try {
			$cinvoice = CustomerInvoice::
							  with('customer')
							->with('invoicingAddress')
							->with('customerInvoiceLines')
							->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
							->with('currency')
							->with('paymentmethod')
							->with('template')
							->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('customerinvoices')
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

        // abi_r($cinvoice->hasManyThrough('App\CustomerInvoiceLineTax', 'App\CustomerInvoiceLine'), true);

		// $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
		$company = \App\Context::getContext()->company;

		$template = 'customer_invoices.templates.' . $cinvoice->template->file_name;  // . '_dist';
		$paper = $cinvoice->template->paper;	// A4, letter
		$orientation = $cinvoice->template->orientation;	// 'portrait' or 'landscape'.
		
		$pdf 		= \PDF::loadView( $template, compact('cinvoice', 'company') )
							->setPaper( $paper, $orientation );
//		$pdf = \PDF::loadView('customer_invoices.templates.test', $data)->setPaper('a4', 'landscape');

		// PDF stuff ENDS

		$pdfName	= 'invoice_' . $cinvoice->secure_key . '_' . $cinvoice->document_date->format('Y-m-d');

		if ($request->has('screen')) return view($template, compact('cinvoice', 'company'));
		
		return 	$pdf->stream();
		return 	$pdf->download( $pdfName . '.pdf');
	}

	public function sendemail( Request $request )
	{
		$id = $request->input('invoice_id');

		// PDF stuff
		try {
			$cinvoice = CustomerInvoice::
							  with('customer')
							->with('invoicingAddress')
							->with('customerInvoiceLines')
							->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
							->with('currency')
							->with('paymentmethod')
							->with('template')
							->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('customers.index')
                     ->with('error', 'La Factura de Cliente id='.$id.' no existe.');
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

		// $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
		$company = \App\Context::getContext()->company;

		$template = 'customer_invoices.templates.' . $cinvoice->template->file_name;
		$paper = $cinvoice->template->paper;	// A4, letter
		$orientation = $cinvoice->template->orientation;	// 'portrait' or 'landscape'.
		
		$pdf 		= \PDF::loadView( $template, compact('cinvoice', 'company') )
//							->setPaper( $paper )
//							->setOrientation( $orientation );
							->setPaper( $paper, $orientation );
		// PDF stuff ENDS

		// MAIL stuff
		try {

			$pdfName	= 'invoice_' . $cinvoice->secure_key . '_' . $cinvoice->document_date->format('Y-m-d');

			$pathToFile 	= storage_path() . '/pdf/' . $pdfName .'.pdf';
			$pdf->save($pathToFile);

			$template_vars = array(
				'company'       => $company,
				'invoice_num'   => $cinvoice->number,
				'invoice_date'  => abi_date_short($cinvoice->document_date),
				'invoice_total' => $cinvoice->as_money('total_tax_incl'),
				'custom_body'   => $request->input('email_body'),
				);

			$data = array(
				'from'     => $company->address->email,
				'fromName' => $company->name_fiscal,
				'to'       => $cinvoice->customer->address->email,
				'toName'   => $cinvoice->customer->name_fiscal,
				'subject'  => $request->input('email_subject'),
				);

			

			// http://belardesign.com/2013/09/11/how-to-smtp-for-mailing-in-laravel/
			\Mail::send('emails.customerinvoice.default', $template_vars, function($message) use ($data, $pathToFile)
			{
				$message->from($data['from'], $data['fromName']);

				$message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );	// Will send blind copy to sender!
				
				$message->attach($pathToFile);

			});	
			
			unlink($pathToFile);

        } catch(\Exception $e) {

            return redirect()->back()->with('error', 'La Factura '.$cinvoice->number.' no se pudo enviar al Cliente');
        }
		// MAIL stuff ENDS
		

		return redirect()->back()->with('success', 'La Factura '.$cinvoice->number.' se envió correctamente al Cliente');
	}


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
