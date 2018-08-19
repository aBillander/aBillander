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
                            ->orderBy('document_date', 'desc');        // ->get();

        $customer_orders = $customer_orders->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $customer_orders->setPath('customerorders');
        
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
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = CustomerOrder::$rules;

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
        $customerorder->fill($request->all());
        $customerorder->save();

        // abi_r($request->all(), true);

        return redirect('customerorders/'.$customerorder->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerorder->id], 'layouts'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerOrder $customerorder)
    {
        $id = $customerorder->id;

        $customerorder->delete();

        return redirect('customerorders')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


/* ********************************************************************************************* */    


    public function move(Request $request, $id)
    {
        $order = \App\CustomerOrder::findOrFail($id);

        $order->update(['production_sheet_id' => $request->input('production_sheet_id')]);

        // Update Sheets!
        $sheet = ProductionSheet::findOrFail( $request->input('current_production_sheet_id') );
        $sheet->calculateProductionOrders();

        $sheet = ProductionSheet::findOrFail( $request->input('production_sheet_id') );
        $sheet->calculateProductionOrders();

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

        $sheet_id = $order->production_sheet_id;

        $order->update(['production_sheet_id' => null]);

        // $sheet_id = $request->input('current_production_sheet_id');

        return redirect()->route('productionsheet.calculate', [$sheet_id]);
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

            $customers = \App\Customer::select('id', 'name_fiscal', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id')
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

    public function FormForService( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view('customer_orders._form_for_service_edit');
                break;
            
            case 'create':
                # code...
                return view('customer_orders._form_for_service_create');
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

    public function searchService(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->isService()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
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
                                        $price->getPriceWithTax() : $price->getPrice(),
                            'price_is_tax_inc' => $price->price_is_tax_inc,  ],
    
                'unit_customer_price' => [ 
                            'tax_exc' => $customer_price->getPrice(), 
                            'tax_inc' => $customer_price->getPriceWithTax(),
                            'display' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $customer_price->getPriceWithTax() : $customer_price->getPrice(),
                            'price_is_tax_inc' => $customer_price->price_is_tax_inc,  ],
    
                'tax_percent' => $tax_percent,
                'tax_id' => $product->tax_id,
                'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)",
                'customer_id' => $customer_id,
                'currency' => $currency,
    
                'measure_unit_id' => $product->measure_unit_id,
                'quantity_decimal_places' => $product->quantity_decimal_places,
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
                        ->with('tax')
                        ->findOrFail($line_id);
/*
        $unit_customer_final_price = \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $order_line->unit_customer_final_price * ( 1.0 + $order_line->tax_percent / 100.0 ) : 
                                        $order_line->unit_customer_final_price ;
*/
        $tax = $order_line->tax;

        return response()->json( $order_line->toArray() + [
//            'unit_customer_final_price' => $unit_customer_final_price,
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
            case 'shipping':
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

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', \App\Configuration::get('PRICES_ENTERED_WITH_TAX')) );
        if ( $pricetaxPolicy > 0 )
            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $request->input('unit_customer_price');
        if ( $pricetaxPolicy > 0 )
            $unit_customer_price = $unit_customer_price / (1.0 + $tax_percent/100.0);

        $discount_percent = $request->input('discount_percent', 0.0);

        $unit_customer_final_price_tax_inc = $unit_customer_final_price = $request->input('unit_customer_final_price');

        if ( $pricetaxPolicy > 0 ) {

            $unit_final_price         = $unit_customer_final_price / (1.0 + $tax_percent/100.0);
            $unit_final_price_tax_inc = $unit_customer_final_price;

            $unit_customer_final_price = $unit_final_price;

        } else {

            $unit_final_price         = $unit_customer_final_price;
            $unit_final_price_tax_inc = $unit_customer_final_price * (1.0 + $tax_percent/100.0);

            $unit_customer_final_price_tax_inc = $unit_final_price_tax_inc;

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

            'prices_entered_with_tax' => $pricetaxPolicy,
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price_tax_inc,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

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

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', \App\Configuration::get('PRICES_ENTERED_WITH_TAX')) );
//        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
//            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $order_line->unit_customer_price;     // $request->input('unit_customer_price');
//        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
//            $unit_customer_price = $unit_customer_price / (1.0 + $tax_percent/100.0);

        $discount_percent = $request->input('discount_percent', 0.0);

        $unit_customer_final_price_tax_inc = $unit_customer_final_price = $request->input('unit_customer_final_price');

        if ( $pricetaxPolicy > 0 ) {

            $unit_final_price         = $unit_customer_final_price / (1.0 + $tax_percent/100.0);
            $unit_final_price_tax_inc = $unit_customer_final_price;

            $unit_customer_final_price = $unit_final_price;

        } else {

            $unit_final_price         = $unit_customer_final_price;
            $unit_final_price_tax_inc = $unit_customer_final_price * (1.0 + $tax_percent/100.0);

            $unit_customer_final_price_tax_inc = $unit_final_price_tax_inc;

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

            'prices_entered_with_tax' => $pricetaxPolicy,
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price_tax_inc,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

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

        $product_id      = $request->input('product_id', 0);
        $combination_id  = $request->input('combination_id', 0);

        // Do the Mambo!
        // Product
        if ($product_id>0)
        {
                if ($combination_id>0) {
                    $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
                    $product = $combination->product;
                    $product->reference = $combination->reference;
                    $product->name = $product->name.' | '.$combination->name;
                } else {
                    $product = \App\Product::with('tax')->findOrFail(intval($product_id));
                }
        
        } else {
        
                // No database persistance, please!
                $product  = new \App\Product([
                    'product_type' => 'simple', 
                    'reference' => $request->input('reference', ''),
                    'name' => $request->input('name'), 
                    'tax_id' => $request->input('tax_id'),
                    'cost_price' => $request->input('cost_price'), 
                ]);
        
        }

        $reference  = $product->reference;
        $name       = $product->name;
        $cost_price = $product->cost_price;

        $quantity = $request->input('quantity');

        // ToDo: Don't trust values from browser. Do not be lazy and get them from database
        $tax_percent = $request->input('tax_percent');
        $unit_price  = $request->input('unit_price');

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', \App\Configuration::get('PRICES_ENTERED_WITH_TAX')) );
        if ( $pricetaxPolicy > 0 )
            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $request->input('unit_customer_price');
        if ( $pricetaxPolicy > 0 )
            $unit_customer_price = $unit_customer_price / (1.0 + $tax_percent/100.0);

        $discount_percent = $request->input('discount_percent', 0.0);

        $unit_customer_final_price_tax_inc = $unit_customer_final_price = $request->input('unit_customer_final_price');

        if ( $pricetaxPolicy > 0 ) {

            $unit_final_price         = $unit_customer_final_price / (1.0 + $tax_percent/100.0);
            $unit_final_price_tax_inc = $unit_customer_final_price;

            $unit_customer_final_price = $unit_final_price;

        } else {

            $unit_final_price         = $unit_customer_final_price;
            $unit_final_price_tax_inc = $unit_customer_final_price * (1.0 + $tax_percent/100.0);

            $unit_customer_final_price_tax_inc = $unit_final_price_tax_inc;

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
            'line_type' => $request->input('is_shipping') > 0 ? 'shipping' : $request->input('line_type'),
            'product_id' => $product_id,
            'combination_id' => $combination_id,
            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
            'measure_unit_id' => $request->input('measure_unit_id', \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS')),
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price_tax_inc,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => $sales_equalization,  // By default, sales_equalization = 0 , because is a service. Otherwise: $request->input('sales_equalization', 0),
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

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
        $product->sales_equalization = $sales_equalization;   // Sensible default for services!
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
                'data' => $order_line->toArray(),
//                'extra' => [$p->getPriceWithTax() , $p->getPrice() , $p->as_priceable($rule->amount)],
        ] );
    }


    public function updateOrderLineService(Request $request, $line_id)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $order_line = $this->customerOrderLine
                        ->findOrFail($line_id);

        $order_id = $order_line->customer_order_id;

 //       $product_id      = $request->input('product_id');
 //       $combination_id  = $request->input('combination_id');

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
            'reference' => $request->input('reference', ''),
            'name' => $request->input('name'), 
            'tax_id' => $request->input('tax_id'),
            'cost_price' => $request->input('cost_price'), 
        ]);

        $reference  = $product->reference;
        $name       = $product->name;
        $cost_price = $product->cost_price;

        $quantity = $request->input('quantity');

        $tax_percent = $request->input('tax_percent');
//        $tax_percent = $product->tax->percent;
/*
        $unit_price  = $request->input('price');
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            $unit_price = $unit_price / (1.0 + $tax_percent/100.0);

        $unit_customer_price = $request->input('price');
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            $unit_customer_price = $price / (1.0 + $tax_percent/100.0);
*/


        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', \App\Configuration::get('PRICES_ENTERED_WITH_TAX')) );

        $discount_percent = $request->input('discount_percent', 0.0);

        if ( $pricetaxPolicy > 0 ) {

            $price          = $request->input('unit_customer_final_price') / (1.0 + $tax_percent/100.0);
            $price_with_tax = $request->input('unit_customer_final_price');

        } else {

            $price          = $request->input('unit_customer_final_price');
            $price_with_tax = $request->input('unit_customer_final_price') * (1.0 + $tax_percent/100.0);

        }

        // Service
        $unit_price = $unit_customer_price = $unit_customer_final_price = $unit_final_price = $price;
        $unit_customer_final_price_tax_inc = $unit_final_price_tax_inc = $price_with_tax;

        $unit_final_price         = $unit_final_price         * (1.0 - $discount_percent/100.0);
        $unit_final_price_tax_inc = $unit_final_price_tax_inc * (1.0 - $discount_percent/100.0);

        $total_tax_excl = $quantity * $unit_final_price;
        $total_tax_incl = $quantity * $unit_final_price_tax_inc;

        $tax_id = $product->tax_id;
        $sales_equalization = $request->input('sales_equalization', 0);

        // Build OrderLine Object
        $data = [
//            'line_sort_order' => $request->input('line_sort_order'),
            'line_type' => $request->input('is_shipping') > 0 ? 'shipping' : 'service',
//            'product_id' => $product_id,
//            'combination_id' => $combination_id,
//            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
//            'measure_unit_id' => $request->input('measure_unit_id', \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS')),
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price_tax_inc,
            'unit_final_price' => $unit_final_price,
            'unit_final_price_tax_inc' => $unit_final_price_tax_inc, 
            'sales_equalization' => $sales_equalization,  // By default, sales_equalization = 0 , because is a service. Otherwise: $request->input('sales_equalization', 0),
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

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

        $order_line->update( $data );

//        $order->customerorderlines()->save($order_line);

        // Let's deal with taxes
        $order_line->customerorderlinetaxes()->delete();

        $product->sales_equalization = $sales_equalization;   // Sensible default for services!
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


    public function duplicateOrder($id)
    {
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

        $clone->created_via          = 'manual';
        $clone->status               = 'draft';
        $clone->locked               = 0;
        
        $clone->document_date = \Carbon\Carbon::now();
        $clone->delivery_date = null;


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


    public function getOrderProfit($id)
    {
        $order = $this->customerOrder
                        ->with('customerorderlines')
                        ->with('customerorderlines.product')
                        ->findOrFail($id);

        return view('customer_orders._panel_customer_order_profitability', compact('order'));

        $order = \App\CustomerOrder::findOrFail($id);

        $sheet_id = $order->production_sheet_id;

        $order->update(['production_sheet_id' => null]);

        // $sheet_id = $request->input('current_production_sheet_id');

        return redirect()->route('productionsheet.calculate', [$sheet_id]);
    }


    public function getOrderAvailability($id)
    {
        $order = $this->customerOrder
                        ->with('customerorderlines')
                        ->with('customerorderlines.product')
                        ->findOrFail($id);

        return view('customer_orders._panel_customer_order_availability', compact('order'));

        $order = \App\CustomerOrder::findOrFail($id);

        $sheet_id = $order->production_sheet_id;

        $order->update(['production_sheet_id' => null]);

        // $sheet_id = $request->input('current_production_sheet_id');

        return redirect()->route('productionsheet.calculate', [$sheet_id]);
    }


    public function quickAddLines(Request $request, $order_id)
    {
        parse_str($request->input('product_id_values'), $product_id);
        parse_str($request->input('combination_id_values'), $combination_id);
        parse_str($request->input('quantity_values'), $quantity);

        return compact('order_id', 'product_id', 'combination_id', 'quantity');
    }




    /*
    |--------------------------------------------------------------------------
    | Not CRUD stuff here
    |--------------------------------------------------------------------------
    */

    protected function showPdf($id, Request $request)
    {
        // return $id;

        // PDF stuff
        try {
            $document = $this->customerOrder
                            ->with('customer')
//                            ->with('invoicingAddress')
//                            ->with('customerInvoiceLines')
//                            ->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('customerorders')
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        }

        // abi_r($document->hasManyThrough('App\CustomerInvoiceLineTax', 'App\CustomerInvoiceLine'), true);

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        // Template impersonation
        $template = \App\Template::create(['file_name'=>'shipping_slip', 'paper'=>'A4', 'orientation'=>'portrait']);
        $document->template = $template;

        $template = 'customer_orders.templates.' . $document->template->file_name;  // . '_dist';
        $paper = $document->template->paper;    // A4, letter
        $orientation = $document->template->orientation;    // 'portrait' or 'landscape'.
        
        $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
//      $pdf = \PDF::loadView('customer_invoices.templates.test', $data)->setPaper('a4', 'landscape');

        // PDF stuff ENDS

        $pdfName    = 'invoice_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');
    }

    public function sendemail( Request $request )
    {
        $id = $request->input('invoice_id');

        // PDF stuff
        try {
            $document = CustomerInvoice::
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

        $template = 'customer_invoices.templates.' . $document->template->file_name;
        $paper = $document->template->paper;    // A4, letter
        $orientation = $document->template->orientation;    // 'portrait' or 'landscape'.
        
        $pdf        = \PDF::loadView( $template, compact('document', 'company') )
//                          ->setPaper( $paper )
//                          ->setOrientation( $orientation );
                            ->setPaper( $paper, $orientation );
        // PDF stuff ENDS

        // MAIL stuff
        try {

            $pdfName    = 'invoice_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

            $pathToFile     = storage_path() . '/pdf/' . $pdfName .'.pdf';
            $pdf->save($pathToFile);

            $template_vars = array(
                'company'       => $company,
                'invoice_num'   => $document->number,
                'invoice_date'  => abi_date_short($document->document_date),
                'invoice_total' => $document->as_money('total_tax_incl'),
                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => $company->address->email,
                'fromName' => $company->name_fiscal,
                'to'       => $document->customer->address->email,
                'toName'   => $document->customer->name_fiscal,
                'subject'  => $request->input('email_subject'),
                );

            

            // http://belardesign.com/2013/09/11/how-to-smtp-for-mailing-in-laravel/
            \Mail::send('emails.customerinvoice.default', $template_vars, function($message) use ($data, $pathToFile)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!
                
                $message->attach($pathToFile);

            }); 
            
            unlink($pathToFile);

        } catch(\Exception $e) {

            return redirect()->back()->with('error', 'La Factura '.$document->number.' no se pudo enviar al Cliente');
        }
        // MAIL stuff ENDS
        

        return redirect()->back()->with('success', 'La Factura '.$document->number.' se envió correctamente al Cliente');
    }


/* ********************************************************************************************* */    



}
