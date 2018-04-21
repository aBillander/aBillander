<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderLine;

use App\Configuration;
use App\Sequence;

use App\Traits\BillableTrait;

class CustomerOrdersController extends Controller
{

   use BillableTrait;

   protected $customer, $customerOrder, $customerOrderLine;

   public function __construct(Customer $customer, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine)
   {
        $this->customer = $customer;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_orders = $this->customerOrder
                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->orderBy('id', 'desc')->get();

        return view('customer_orders.index', compact('customer_orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Some checks to start with:

        $sequenceList = Sequence::listFor( CustomerOrder::class );
        if ( !count($sequenceList) )
            return redirect('customerorders')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
        
        return view('customer_orders.create', compact('sequenceList'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithCustomer(Customer $customer)
    {
        /*  
                Crear Pedido desde la ficha del Cliente  =>  ya conozco el customer_id

                ruta:  customers/{id}/createorder

                esta ruta la ejecuta:  CustomerOrdersController@createWithCustomer
                enviando un objeto CustomerOrder "vacío"

                y vuelve a /customerorders
        */

        return 'customer.createorder';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = CustomerOrder::$rules;

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

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

        $customerOrder = $this->customerOrder->create($request->all());

        return redirect('customerorders/'.$customerOrder->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerOrder->id], 'layouts'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerOrder $customerorder)
    {
        return $this->edit( $customerorder );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerOrder $customerorder)
    {
        $order = $customerorder;

        $customer = \App\Customer::find( $order->customer_id );

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
        $this->addFormDates( ['document_date', 'delivery_date'], $order );

        return view('customer_orders.edit', compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerOrder $customerorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerOrder $customerorder)
    {
        //
    }


/* ********************************************************************************************* */    


    public function move(Request $request, $id)
    {
        $order = \App\CustomerOrder::findOrFail($id);

        $order->update(['production_sheet_id' => $request->input('production_sheet_id')]);

        if ( $request->input('stay_current_sheet', 0) )
            $sheet_id = $request->input('current_production_sheet_id');
        else
            $sheet_id = $request->input('production_sheet_id');

        return redirect('productionsheets/'.$sheet_id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet_id], 'layouts') . $request->input('name', ''));
    }

    public function unlink(Request $request, $id)
    {
        $order = \App\CustomerOrder::findOrFail($id);

        // Destroy Order Lines
        foreach( $order->customerorderlines as $line ) {
            $line->delete();
        }

        // Destroy Order
        $order->delete();

        $sheet_id = $request->input('current_production_sheet_id');

        return redirect('productionsheets/'.$sheet_id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }



/* ********************************************************************************************* */    




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

        if ($request->has('term'))
        {
            $search = $request->term;

            $customers = \App\Customer::select('id', 'name_fiscal', 'identification', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'carrier_id', 'sales_rep_id')
                                    ->where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
                                    ->with('currency')
                                    ->with('addresses')
                                    ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );

//            return $customers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $customers );
//            return json_encode( $customers );
            return response()->json( $customers );
        } else {
            // die silently
            return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        }
    }

    public function customerAdressBookLookup($id)
    {
        if (intval($id)>0)
        {
            $customer = \App\Customer::findorfail($id);

            return response()->json( $customer->getAddressList() );
        } else {
            // die silently
            return json_encode( [] );
        }
    }


    public function getOrderLines($id)
    {
        $order = $this->customerOrder
                        ->with('customerorderlines')
                        ->with('customerorderlines.product')
                        ->findOrFail($id);

        return view('customer_orders._panel_customer_order_lines', compact('order'));
    }

    public function getOrderTotal($id)
    {
        $order = $this->customerOrder
//                        ->with('customerorderlines')
//                        ->with('customerorderlines.product')
                        ->findOrFail($id);

        return view('customer_orders._panel_customer_order_total', compact('order'));
    }

}
