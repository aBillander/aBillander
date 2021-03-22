<?php 

namespace App\Http\Controllers\SalesRepCenter;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Auth;
use App\SalesRepUser;
use App\SalesRep;

use App\Configuration;
use App\Currency;
use App\Todo;

use App\Customer;
use App\CustomerOrder as Document;
use App\CustomerOrderLine as DocumentLine;
use App\CustomerOrderLineTax as DocumentLineTax;

use Mail;

use App\Events\CustomerOrderConfirmed;

use App\Http\Controllers\BillableController;
use App\Traits\BillableGroupableControllerTrait;
use App\Traits\BillableShippingSlipableControllerTrait;

use App\Traits\DateFormFormatterTrait;

class AbsrcCustomerOrdersController extends BillableController
{
   use DateFormFormatterTrait;

   use BillableGroupableControllerTrait;
   use BillableShippingSlipableControllerTrait;

   protected $salesrep, $salesrep_user, $customerOrder, $customerOrderLine;

   public function __construct(SalesRep $salesrep, SalesRepUser $salesrep_user, Customer $customer, Document $document, DocumentLine $document_line)
   {
        parent::__construct();

        $this->model = 'CustomerOrder';	// \Str::singular($this->getParentClass());       // CustomerShippingSlip
        $this->model_snake_case = 'order';	// $this->getParentModelSnakeCase(); // customer_shipping_slip
        $this->model_path = 'absrc.orders';	// $this->getParentClassLowerCase();       // customershippingslips
        $this->model_url = 'absrc/orders';
        $this->view_path = 'absrc.orders';	// $this->getParentClassSnakeCase();            // customer_shipping_slips

        $this->model_class = Document::class;

        $this->salesrep = $salesrep;
        $this->salesrep_user = $salesrep_user;

        $this->customer = $customer;
        $this->document = $document;
        $this->document_line = $document_line;
   }

    /**
    * Gather handy Controller vars (useful when passing to views)
    */
    public function modelVars()
    {
        // 

        return parent::modelVars() + 
            [
                'model_url' => $this->model_url,
            ];
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $documents = $this->document
                            ->ofSalesRep()      // Of Logged in Sales Rep (see scope on Billable 
//                            ->where('salesrep_id', $salesrep->id)
                            ->filter( $request->all() )
                            ->with('customer')
                            ->withCount('lines')
//                            ->with('customer')
//                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( Configuration::get('ABSRC_ITEMS_PERPAGE') );

        $documents->setPath('orders');

        $statusList = Document::getStatusList();

        return view($this->view_path.'.index', $this->modelVars() +  compact('documents', 'statusList'));
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexByCustomer($id, Request $request)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $customer = $this->customer->ofSalesRep()->findOrFail($id);

        $documents = $this->document
                            ->ofSalesRep()
                            ->where('customer_id', $id)
//                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath($id);

        return view($this->view_path.'.index_by_customer', $this->modelVars() + compact('customer', 'documents', 'items_per_page'));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$salesrep = Auth::user()->salesrep;

        // abi_r($this->modelVars());die();

        $model_path = $this->model_path;
        $view_path = $this->view_path;
//        $model_snake_case = $this->model_snake_case;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();

        $templateList = $this->document->templateList();

        if ( !(count($sequenceList)>0) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));

        $payment_methodList = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();
        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $salesrepList = \App\SalesRep::where('id', optional($salesrep)->id)->pluck('alias', 'id')->toArray();
        $warehouseList =\App\Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();
        $shipping_methodList = \App\ShippingMethod::pluck('name', 'id')->toArray();
        
        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList', 'templateList', 'payment_methodList', 'currencyList', 'salesrepList', 'warehouseList', 'shipping_methodList'));
    
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithCustomer($customer_id)
    {
        $salesrep = Auth::user()->salesrep;

        $model_path = $this->model_path;
        $view_path = $this->view_path;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();

        $templateList = $this->document->templateList();

        if ( !count($sequenceList) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));


        // Do the Mambo!!!
        try {
            $customer = Customer::with('addresses')
                            ->ofSalesRep()
                            ->findOrFail( $customer_id );

        } catch(ModelNotFoundException $e) {
            // No Customer available, ask for one
            return redirect()->back()
                    ->with('error', l('The record with id=:id does not exist', ['id' => $customer_id], 'layouts'));
        }

        $payment_methodList = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();
        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $salesrepList = \App\SalesRep::where('id', optional($salesrep)->id)->pluck('alias', 'id')->toArray();
        $warehouseList =\App\Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();
        $shipping_methodList = \App\ShippingMethod::pluck('name', 'id')->toArray();
        
        return view($this->view_path.'.create', $this->modelVars() + compact('customer_id', 'sequenceList', 'templateList', 'payment_methodList', 'currencyList', 'salesrepList', 'warehouseList', 'shipping_methodList'));
    }

	/**
	 * Store a newly created resource in storage.
	 * Get Cart content & push a Customer Order
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $salesrep_user = Auth::user();
        $salesrep      = Auth::user()->salesrep;

        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        // Extra data
//        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
//        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => $salesrep_user->id,

                        'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_CUSTOMER_ORDER_SEQUENCE'),

                        'sales_rep_id'         => $salesrep->id,

                        'created_via'          => 'absrc',
                        'status'               =>  'draft',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );

        $document = $this->document->create($request->all());


        // Move on
		if (   Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') 
            || ( $request->input('nextAction', '') == 'saveAndConfirm' ) 
           ) 
		{
			$document->confirm();
		}


        // Good boy:


        // Notify Admin
        // 
        // Create Todo
        $data = [
            'name' => l('Preparar un Pedido a un Cliente'), 
            'description' => l('Un Agente ha realizado un Pedido desde el Centro de Agentes.'), 
            'url' => route('customerorders.edit', [$document->id]), 
            'due_date' => null, 
            'completed' => 0, 
            'user_id' => $salesrep_user->id,
        ];

//        $todo = Todo::create($data);


if (0) {
        // MAIL stuff
        try {

            $template_vars = array(
//                'company'       => $company,
                'document_num'   => $customerOrder->document_reference,
                'document_date'  => abi_date_short($customerOrder->document_date),
                'document_total' => $customerOrder->as_money('total_tax_excl'),
//                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => abi_mail_from_address(),            // config('mail.from.address'  ),
                'fromName' => abi_mail_from_name(),                // config('mail.from.name'    ),
                'to'       => abi_mail_from_address(),            // $cinvoice->customer->address->email,
                'toName'   => abi_mail_from_name(),                // $cinvoice->customer->name_fiscal,
                'subject'  => l(' :_> New Customer Order #:num', ['num' => $template_vars['document_num']]),
                );

            

            $send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.absrc.new_customer_order', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            });    

        } catch(\Exception $e) {

            return redirect()->route('absrc.orders.index')
                    ->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
                        $e->getMessage());
        }
        // MAIL stuff ENDS

}

        return redirect()->route($this->model_path.'.edit', [$document->id])
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return redirect()->route($this->model_path.'.edit', [$id]);

		//

        $customer      = Auth::user()->customer;

        $order = $this->document
                            ->with('customer')
        					->with('lines')
        					->with('currency')
        					->where('id', $id)
        					->where('customer_id', $customer->id)
        					->first();

        if (!$order) 
        	return redirect()->route('absrc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));

		return view('absrc.orders.show', compact('order'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
        // Little bit Gorrino style...
        // Find by document_reference (if supplied one)
        if ( $request->has('document_reference') )
        {
            $document = $this->document->where('document_reference', $request->input('document_reference'))->firstOrFail();

            // $request->request->remove('document_reference');
            // $this->edit($document->id, $request);

            return redirect($this->model_path.'/'.$document->id.'/edit');
        }
        else
        {
            $document = $this->document->ofSalesRep()->findOrFail($id);
        }


        $salesrep = Auth::user()->salesrep;

        // abi_r($this->modelVars());die();

        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $sequenceList = $this->document->sequenceList();

        $templateList = $this->document->templateList();

        $customer = Customer::find( $document->customer_id );

        $addressBook       = $customer->addresses;

        $theId = $customer->invoicing_address_id;
        $invoicing_address = $addressBook->filter(function($item) use ($theId) {    // Filter returns a collection!
            return $item->id == $theId;
        })->first();

        $addressbookList = array();
        foreach ($addressBook as $address) {
            $addressbookList[$address->id] = $address->alias;
        }

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'delivery_date', 'export_date'], $document );

        $payment_methodList = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();
        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $salesrepList = \App\SalesRep::where('id', optional($salesrep)->id)->pluck('alias', 'id')->toArray();
        $warehouseList =\App\Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();
        $shipping_methodList = \App\ShippingMethod::pluck('name', 'id')->toArray();

        return view($this->view_path.'.edit', $this->modelVars() + compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'document', 'sequenceList', 'templateList', 'payment_methodList', 'currencyList', 'salesrepList', 'warehouseList', 'shipping_methodList'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, Document $order)
	{
        // abi_r($order);die();

        $salesrep_user = Auth::user();
        $salesrep      = Auth::user()->salesrep;

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
        $document = $order;

        $document->fill($request->all());

        // Reset Export date
        // if ( $request->input('export_date_form') == '' ) $document->export_date = null;

        $document->save();

        // Move on
        if (   Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') 
            || ( $request->input('nextAction', '') == 'saveAndConfirm' ) 
           ) 
        {
            $document->confirm();
        }


        // Good boy:

        $nextAction = $request->input('nextAction', '');
        
        if ( $nextAction == 'saveAndContinue' ) 
            return redirect()->route($this->model_path.'.edit', [$document->id])
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        return redirect()->route($this->model_path.'.index')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

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

        $document = $this->document->ofSalesRep()->findOrFail($id);

        if( !$document->deletable )
            return redirect()->back()
                ->with('error', l('This record cannot be deleted because its Status &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        $document->delete();

        return redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}


    /**
     * Manage Status.
     *
     * ******************************************************************************************************************************* *
     * 
     */

    protected function confirm(Document $document)
    {
        // Sorry, Sales Rep
        // return redirect()->back()
        //        ->with('error', l('You are not allowed to do this', 'layouts'));
        

        // Can I?
        if ( $document->lines->count() == 0 )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document has no Lines', 'layouts'));
        }

        if ( $document->onhold )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is on-hold', 'layouts'));
        }

        // Confirm
        if ( $document->confirm() )
            return redirect()->back()       //  ->route($this->model_path.'.index')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }

    protected function unConfirm(Document $document)
    {
        // Can I?
        if ( $document->status != 'confirmed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: ');
        }

        // UnConfirm
        if ( $document->unConfirm() )
            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


    protected function onholdToggle(Document $document)
    {
        // Sorry, Sales Rep
        return redirect()->back()
                ->with('error', l('You are not allowed to do this', 'layouts'));
        

        // No checks. A closed document can be set to "onhold". Maybe usefull...

        // Toggle
        $toggle = $document->onhold > 0 ? 0 : 1;
        $document->onhold = $toggle;
        
        $document->save();

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
    }





    /**
     * More stuff.
     *
     * ******************************************************************************************************************************* *
     * 
     */

    public function deleteDocumentLine($line_id)
    {

        $document_line = $this->document_line
                        ->findOrFail($line_id);
/*
        $document = $this->customerOrder
                        ->findOrFail($order_line->customer_order_id);
*/

        // $theID = $this->model_snake_case.'_id';
        $theID = 'customer_'.$this->model_snake_case.'_id';
        $document = $this->document
                        ->findOrFail($document_line->{$theID});



        $document_line->delete();

        // Now, update Order Totals
        $document->makeTotals();

        return response()->json( [
                'msg' => 'OK',
                'data' => $line_id,
        ] );
    }

    public function duplicateOrder($id)
    {

        $customer      = Auth::user()->customer;

        $order = $this->document
        					->where('id', $id)
        					->where('customer_id', $customer->id)
                            ->withCount('lines')
        					->first();

        if (!$order) 
        	return redirect()->route('absrc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        
        $cart = \App\Context::getContext()->cart;

        foreach ($order->lines as $orderline) {
        	# code...
        	$line = $cart->addLine($orderline->product_id, $orderline->combination_id, $orderline->quantity);
        }

        return redirect()->route('absrc.cart')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $order->id], 'layouts'));
    }


	public function showPdf($id, Request $request)
	{
        // PDF stuff

        $document = $this->document
                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('customer.bankaccount')
                            ->with('template')
                            ->find($id);

        if (!$document) 
        	return redirect()->route('absrc.orders.index')
                	->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        

        $company = \App\Context::getContext()->company;

        // Get Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_CUSTOMER_ORDER_TEMPLATE') );

        if ( !$t )
        	return redirect()->route('absrc.orders.show', $id)
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');

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

		    	return redirect()->route('absrc.orders.index')
                	->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').$e->getMessage());
		}

        // PDF stuff ENDS

        $pdfName    = 'order_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');


        return redirect()->route('absrc.orders.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
	}


/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */

    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxCustomerSearch(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;

//        if ( $query )

        if ($request->has('customer_id'))
        {
            $search = $request->customer_id;

            $customers = \App\Customer::select('id', 'name_fiscal', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id')
                            		->ofSalesRep()
                                    ->with('currency')
                                    ->with('addresses')
                                    ->find( $search );

//            return $customers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $customers );
//            return json_encode( $customers );
            return response()->json( $customers );
        }

        if ($request->has('term'))
        {
            $search = $request->term;

            $customers = \App\Customer::select('id', 'name_fiscal', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id')
                            		->ofSalesRep()
                                    ->where( function($query) use ($search) {
                                            $query->where(   'name_fiscal',      'LIKE', '%'.$search.'%' );
                                            $query->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' );
                                            $query->orWhere( 'identification', 'LIKE', '%'.$search.'%' );
                                    } )
                                    ->with('currency')
                                    ->with('addresses')
                                    ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                    ->get();

//            return $customers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $customers );
//            return json_encode( $customers );
            return response()->json( $customers );
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
    }
}
