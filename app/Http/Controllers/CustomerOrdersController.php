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
    public function createWithCustomer($customer_id)
    {
        /*  
                Crear Pedido desde la ficha del Cliente  =>  ya conozco el customer_id

                ruta:  customers/{id}/createorder

                esta ruta la ejecuta:  CustomerOrdersController@createWithCustomer
                enviando un objeto CustomerOrder "vacío"

                y vuelve a /customerorders
        */
        // Some checks to start with:

        $sequenceList = Sequence::listFor( CustomerOrder::class );
        if ( !count($sequenceList) )
            return redirect('customerorders')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
        
        return view('customer_orders.create', compact('sequenceList', 'customer_id'));
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


// https://stackoverflow.com/questions/39812203/cloning-model-with-hasmany-related-models-in-laravel-5-3


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

            $customers = \App\Customer::select('id', 'name_fiscal', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'carrier_id', 'sales_rep_id')
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

            $customers = \App\Customer::select('id', 'name_fiscal', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'carrier_id', 'sales_rep_id')
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
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
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



    public function FormForProduct( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view('customer_orders._form_for_product_edit');
                break;
            
            case 'create':
                # code...
                return view('customer_orders._form_for_product_create');
                break;
            
            default:
                # code...
                // Form for action not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $action
                    ] );
                break;
        }
        
    }


    public function searchProduct(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->isManufactured()
                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }

    public function getProduct(Request $request)
    {
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $customer_id     = $request->input('customer_id');
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $customer_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->findOrFail(intval($product_id));
        }

        // Customer
        $customer = \App\Customer::findOrFail(intval($customer_id));
        
        // Currency
        $currency = \App\Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Tax
        $tax = $product->tax;
        $taxing_address = \App\Address::findOrFail($request->input('taxing_address_id'));
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $price = $product->getPrice();
        if ( $price->currency->id != $currency->id ) {
            $price = $price->convert( $currency );
        }

        // Calculate price per $customer_id now!
        $customer_price = $product->getPriceByCustomer( $customer, $currency );
//        $tax_percent = $tax->percent;               // Accessor: $tax->getPercentAttribute()
//        $price->applyTaxPercent( $tax_percent );

        if ($customer_price) 
        {
            $customer_price->applyTaxPercentToPrice($tax_percent);        
    
            $data = [
                'product_id' => $product->id,
                'combination_id' => $combination_id,
                'reference' => $product->reference,
                'name' => $product->name,
                'cost_price' => $product->cost_price,
                'unit_price' => [ 
                            'tax_exc' => $price->getPrice(), 
                            'tax_inc' => $price->getPriceWithTax(),
                            'display' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $price->getPriceWithTax() : $price->getPrice() ],
    
                'unit_customer_price' => [ 
                            'tax_exc' => $customer_price->getPrice(), 
                            'tax_inc' => $customer_price->getPriceWithTax(),
                            'display' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $customer_price->getPriceWithTax() : $customer_price->getPrice() ],
    
                'tax_percent' => $tax_percent,
                'tax_id' => $product->tax_id,
                'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)",
                'customer_id' => $customer_id,
                'currency' => $currency,
    
                'measure_unit_id' => $product->measure_unit_id,
                'reorder_point'      => $product->reorder_point, 
                'quantity_onhand'    => $product->quantity_onhand, 
                'quantity_onorder'   => $product->quantity_onorder, 
                'quantity_allocated' => $product->quantity_allocated, 
                'blocked' => $product->blocked, 
                'active'  => $product->active, 
            ];
        } else
            $data = [];

        return response()->json( $data );
    }

    public function getOrderLine($order_id, $line_id)
    {
        $order_line = $this->customerOrderLine
                        ->with('product')
                        ->with('product.tax')
                        ->with('measureunit')
                        ->findOrFail($line_id);

        $unit_customer_final_price = \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $order_line->unit_customer_final_price * ( 1.0 + $order_line->tax_percent / 100.0 ) : 
                                        $order_line->unit_customer_final_price ;

        $tax = $order_line->product->tax;

        return response()->json( $order_line->toArray() + [
            'unit_customer_final_price' => $unit_customer_final_price,
            'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)"
        ] );
    }

    public function updateOrderLine(Request $request, $line_id)
    {
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->updateOrderLineProduct($request, $line_id);
                break;
            
            case 'service':
                # code...
                return $this->updateOrderLineService($request, $line_id);
                break;
            
            default:
                # code...
                // Order Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function storeOrderLine(Request $request, $order_id)
    {
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->storeOrderLineProduct($request, $order_id);
                break;
            
            case 'service':
                # code...
                return $this->storeOrderLineService($request, $order_id);
                break;
            
            default:
                # code...
                // Order Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function storeOrderLineProduct(Request $request, $order_id)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->findOrFail(intval($product_id));
        }

        $reference  = $product->reference;
        $name       = $product->name;
        $cost_price = $product->cost_price;

        $quantity = $request->input('quantity');

        // ToDo: Don't trust values from browser. Do not be lazy and get them from database
        $tax_percent = $request->input('tax_percent');
        $unit_price  = $request->input('unit_price');
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $request->input('unit_customer_price');
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            $unit_customer_price = $unit_customer_price / (1.0 + $tax_percent/100.0);

        $discount_percent = $request->input('discount_percent', 0.0);

        $unit_customer_final_price = $request->input('unit_customer_final_price');

        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ) {

            $unit_final_price         = $unit_customer_final_price / (1.0 + $tax_percent/100.0);
            $unit_final_price_tax_inc = $unit_customer_final_price;

            $unit_customer_final_price = $unit_final_price;

        } else {

            $unit_final_price         = $unit_customer_final_price;
            $unit_final_price_tax_inc = $unit_customer_final_price * (1.0 + $tax_percent/100.0);

        }

        $unit_final_price         = $unit_final_price         * (1.0 - $discount_percent/100.0);
        $unit_final_price_tax_inc = $unit_final_price_tax_inc * (1.0 - $discount_percent/100.0);

        $total_tax_excl = $quantity * $unit_final_price;
        $total_tax_incl = $quantity * $unit_final_price_tax_inc;

        $tax_id = $product->tax_id;
        $sales_equalization = $request->input('sales_equalization', 0);

        // Build OrderLine Object
        $data = [
            'line_sort_order' => $request->input('line_sort_order'),
            'line_type' => $request->input('line_type'),
            'product_id' => $request->input('product_id', 0),
            'combination_id' => $request->input('combination_id', 0),
            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
            'measure_unit_id' => $request->input('measure_unit_id', $product->measure_unit_id),
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => floatval( $request->input('discount_amount_tax_excl', 0.0) ),

            'total_tax_incl' => $total_tax_incl,
            'total_tax_excl' => $total_tax_excl,

            'tax_percent' => $tax_percent,
            'commission_percent' => $request->input('commission_percent', 0.0),
            'notes' => $request->input('notes'),
            'locked' => 0,
    
    //        'customer_order_id',
            'tax_id' => $tax_id,
            'sales_rep_id' => $request->input('sales_rep_id'),
        ];


        // Finishing touches
        $order = $this->customerOrder->findOrFail($order_id);

        $order_line = $this->customerOrderLine->create( $data );

        $order->customerorderlines()->save($order_line);

        // Let's deal with taxes
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $order->taxingaddress,  $order->customer );

        $base_price = $order_line->quantity*$order_line->unit_final_price;
        // Rounded $base_price is the same, no matters the value of ROUND_PRICES_WITH_TAX

        $order_line->total_tax_incl = $order_line->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $line_tax = new \App\CustomerOrderLineTax();

                $line_tax->name = $rule->fullName;
                $line_tax->tax_rule_type = $rule->rule_type;

                $p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $order->currency, $order->currency_conversion_rate);

                $p->applyRounding( );

                $line_tax->taxable_base = $base_price;
                $line_tax->percent = $rule->percent;
                $line_tax->amount = $rule->amount;
                $line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

                $line_tax->position = $rule->position;

                $line_tax->tax_id = $tax_id;
                $line_tax->tax_rule_id = $rule->id;

                $line_tax->save();
                $order_line->total_tax_incl += $line_tax->total_line_tax;

                $order_line->CustomerOrderLineTaxes()->save($line_tax);
                $order_line->save();

        }

        // Now, update Order Totals
        $order->makeTotals();









        return response()->json( [
                'msg' => 'OK',
                'data' => $order_line->toArray()
        ] );
    }



    public function updateOrderLineProduct(Request $request, $line_id)
    {
        // return response()->json( ['msg' => 'OK',] );

        $order_line = $this->customerOrderLine
                        ->findOrFail($line_id);

        $order_id = $order_line->customer_order_id;

//        $order_line->update( $request->all() );

//        return response()->json( [
//                'msg' => 'OK',
//                'data' => $order_line->toArray()
//        ] );

        // return response()->json(['order_id' => $order_id] + $request->all());

        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->findOrFail(intval($product_id));
        }

        $reference  = $product->reference;
        $name       = $request->input('name', $product->name);
        $cost_price = $order_line->cost_price;                 // Use original cost price (avoid tamppering!) instead of: $request->input('cost_price', $product->cost_price);

        $quantity = $request->input('quantity');

        // ToDo: Don't trust values from browser. Do not be lazy and get them from database
        $tax_percent = $request->input('tax_percent');
        $unit_price  = $order_line->unit_price;                 // $request->input('unit_price');
//        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
//            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $order_line->unit_customer_price;     // $request->input('unit_customer_price');
//        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
//            $unit_customer_price = $unit_customer_price / (1.0 + $tax_percent/100.0);

        $discount_percent = $request->input('discount_percent', 0.0);

        $unit_customer_final_price = $request->input('unit_customer_final_price');

        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ) {

            $unit_final_price         = $unit_customer_final_price / (1.0 + $tax_percent/100.0);
            $unit_final_price_tax_inc = $unit_customer_final_price;

            $unit_customer_final_price = $unit_final_price;

        } else {

            $unit_final_price         = $unit_customer_final_price;
            $unit_final_price_tax_inc = $unit_customer_final_price * (1.0 + $tax_percent/100.0);

        }

        $unit_final_price         = $unit_final_price         * (1.0 - $discount_percent/100.0);
        $unit_final_price_tax_inc = $unit_final_price_tax_inc * (1.0 - $discount_percent/100.0);

        $total_tax_excl = $quantity * $unit_final_price;
        $total_tax_incl = $quantity * $unit_final_price_tax_inc;

        $tax_id = $product->tax_id;
        $sales_equalization = $request->input('sales_equalization', 0);

        // Build OrderLine Object
        $data = [
//            'line_sort_order' => $request->input('line_sort_order'),
//            'line_type' => $request->input('line_type'),
//            'product_id' => $request->input('product_id', 0),
//            'combination_id' => $request->input('combination_id', 0),
//            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
            'measure_unit_id' => $request->input('measure_unit_id', $product->measure_unit_id),
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => floatval( $request->input('discount_amount_tax_excl', 0.0) ),

            'total_tax_incl' => $total_tax_incl,
            'total_tax_excl' => $total_tax_excl,

            'tax_percent' => $tax_percent,
            'commission_percent' => $request->input('commission_percent', 0.0),
            'notes' => $request->input('notes'),
            'locked' => 0,
    
    //        'customer_order_id',
            'tax_id' => $tax_id,
            'sales_rep_id' => $request->input('sales_rep_id'),
        ];


        // Finishing touches
        $order = $this->customerOrder->findOrFail($order_id);

        $order_line->update( $data );

//        $order->customerorderlines()->save($order_line);

        // Let's deal with taxes
        $order_line->customerorderlinetaxes()->delete();

        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $order->taxingaddress,  $order->customer );

        $base_price = $order_line->quantity*$order_line->unit_final_price;

        $order_line->total_tax_incl = $order_line->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $line_tax = new \App\CustomerOrderLineTax();

                $line_tax->name = $rule->fullName;
                $line_tax->tax_rule_type = $rule->rule_type;

                $p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $order->currency, $order->currency_conversion_rate);

                $p->applyRounding( );

                $line_tax->taxable_base = $base_price;
                $line_tax->percent = $rule->percent;
                $line_tax->amount = $rule->amount;
                $line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

                $line_tax->position = $rule->position;

                $line_tax->tax_id = $tax_id;
                $line_tax->tax_rule_id = $rule->id;

                $line_tax->save();
                $order_line->total_tax_incl += $line_tax->total_line_tax;

                $order_line->CustomerOrderLineTaxes()->save($line_tax);
                $order_line->save();

        }

        // Now, update Order Totals
        $order->makeTotals();









        return response()->json( [
                'msg' => 'OK',
                'data' => $order_line->toArray()
        ] );
    }



    public function storeOrderLineService(Request $request, $order_id)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $product_id      = 0;
        $combination_id  = 0;

        // Do the Mambo!
        // Product
/*        
        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->findOrFail(intval($product_id));
        }
*/        
        // No database persistance, please!
        $product  = new \App\Product([
            'product_type' => 'simple', 
            'reference' => '',
            'name' => $request->input('name'), 
            'tax_id' => $request->input('tax_id'),
            'cost_price' => $request->input('cost_price'), 
        ]);

        $reference  = $product->reference;
        $name       = $product->name;
        $cost_price = $product->cost_price;

        $quantity = $request->input('quantity');

        $tax_percent = $request->input('tax_percent');
        $unit_price  = $request->input('price');
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $request->input('price');
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            $unit_customer_price = $price / (1.0 + $tax_percent/100.0);

        $discount_percent = $request->input('discount_percent', 0.0);

        $unit_customer_final_price = $request->input('price');

        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ) {

            $unit_final_price         = $unit_customer_final_price / (1.0 + $tax_percent/100.0);
            $unit_final_price_tax_inc = $unit_customer_final_price;

        } else {

            $unit_final_price         = $unit_customer_final_price;
            $unit_final_price_tax_inc = $unit_customer_final_price * (1.0 + $tax_percent/100.0);

        }

        $unit_final_price         = $unit_final_price         * (1.0 - $discount_percent/100.0);
        $unit_final_price_tax_inc = $unit_final_price_tax_inc * (1.0 - $discount_percent/100.0);

        $total_tax_excl = $quantity * $unit_final_price;
        $total_tax_incl = $quantity * $unit_final_price_tax_inc;

        $tax_id = $product->tax_id;

        // Build OrderLine Object
        $data = [
            'line_sort_order' => $request->input('line_sort_order'),
            'line_type' => $request->input('is_shipping') > 0 ? 'shipping' : $request->input('line_type'),
            'product_id' => $product_id,
            'combination_id' => $combination_id,
            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => 0,  // This is by default, because is a service. Otherwise: $request->input('sales_equalization', 0),
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => $request->input('discount_amount_tax_incl', 0.0),
            'discount_amount_tax_excl' => $request->input('discount_amount_tax_excl', 0.0),

            'total_tax_incl' => $total_tax_incl,
            'total_tax_excl' => $total_tax_excl,

            'tax_percent' => $tax_percent,
            'commission_percent' => $request->input('commission_percent', 0.0),
            'notes' => $request->input('notes', ''),
            'locked' => 0,
    
    //        'customer_order_id',
            'tax_id' => $tax_id,
            'sales_rep_id' => $request->input('sales_rep_id', 0),
        ];


        // Finishin touches
        $order = $this->customerOrder->findOrFail($order_id);

        $order_line = $this->customerOrderLine->create( $data );

        $order->customerorderlines()->save($order_line);

        // Let's deal with taxes
        $product->sales_equalization = 0;   // Sensible default for services!
        $rules = $product->getTaxRules( $order->taxingaddress,  $order->customer );

        $base_price = $order_line->quantity*$order_line->unit_final_price;

        $order_line->total_tax_incl = $order_line->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $line_tax = new \App\CustomerOrderLineTax();

                $line_tax->name = $rule->fullName;
                $line_tax->tax_rule_type = $rule->rule_type;

                $p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $order->currency, $order->currency_conversion_rate);

                $p->applyRounding( );

                $line_tax->taxable_base = $base_price;
                $line_tax->percent = $rule->percent;
                $line_tax->amount = $rule->amount;
                $line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

                $line_tax->position = $rule->position;

                $line_tax->tax_id = $tax_id;
                $line_tax->tax_rule_id = $rule->id;

                $line_tax->save();
                $order_line->total_tax_incl += $line_tax->total_line_tax;

                $order_line->CustomerOrderLineTaxes()->save($line_tax);
                $order_line->save();

        }

        // Now, update Order Totals
        $order->makeTotals();









        return response()->json( [
                'msg' => 'OK',
                'data' => $order_line->toArray()
        ] );
    }


    public function updateOrderLineService(Request $request, $line_id)
    {
        //
    }


    public function updateOrderTotal(Request $request, $order_id)
    {
        $order = $this->customerOrder
//                        ->with('customerorderlines')
//                        ->with('customerorderlines.product')
                        ->findOrFail($order_id);

        $discount_percent = $request->input('document_discount_percent', 0.0);

        // Now, update Order Totals
        $order->makeTotals( $discount_percent );

        return view('customer_orders._panel_customer_order_total', compact('order'));
    }

    public function deleteOrderLine($line_id)
    {

        $order_line = $this->customerOrderLine
                        ->findOrFail($line_id);

        $order = $this->customerOrder
                        ->findOrFail($order_line->customer_order_id);

        $order_line->delete();

        // Now, update Order Totals
        $order->makeTotals();

        return response()->json( [
                'msg' => 'OK',
                'data' => $line_id
        ] );
    }

    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                CustomerOrderLine::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }

}
