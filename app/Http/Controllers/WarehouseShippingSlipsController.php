<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Warehouse;
use App\WarehouseShippingSlip as Document;
use App\WarehouseShippingSlipLine as DocumentLine;

use App\ShippingMethod;
use App\Carrier;

use App\Configuration;
use App\Sequence;
use App\Template;

use App\Product;

use App\Traits\DateFormFormatterTrait;

class WarehouseShippingSlipsController extends Controller
{
   
   use DateFormFormatterTrait;

   protected $warehouse, $document, $document_line;

   public function __construct(Warehouse $warehouse, Document $document, DocumentLine $document_line)
   {
        $this->warehouse = $warehouse;
        $this->document = $document;
        $this->document_line = $document_line;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // abi_r('----');die();
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $documents = $this->document
//                            ->filter( $request->all() )
                            ->with('warehouse')
                            ->with('warehousecounterpart')
                            ->with('shippingmethod')
                            ->with('carrier')
                            ->orderBy('document_date', 'desc');
                            // ->orderBy('document_reference', 'desc');
// https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
//                            ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');
//                          ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath('warehouseshippingslips');

        $statusList = Document::getStatusList();

        $shipment_statusList = Document::getShipmentStatusList();

        $carrierList =  Carrier::pluck('name', 'id')->toArray();

        return view('warehouse_shipping_slips.index', compact('documents', 'statusList', 'shipment_statusList', 'carrierList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $sequenceList = $this->document->sequenceList();

        if ( !(count($sequenceList)>0) )
            return redirect('warehouseshippingslips')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));


        $templateList = $this->document->templateList();

        $warehouseList =  Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();

        $shipping_methodList = ShippingMethod::pluck('name', 'id')->toArray();

        $carrierList = Carrier::pluck('name', 'id')->toArray();
        
        return view('warehouse_shipping_slips.create', compact('sequenceList', 'templateList', 'warehouseList', 'shipping_methodList', 'carrierList'));
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

        $request->merge( [
                                'number_of_packages'   => $request->input('number_of_packages', 1) > 0 ? (int) $request->input('number_of_packages') : 1,
                    ] );

        $rules = $this->document::$rules;

//        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
//        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);


        // Manage Shipping Method & Carrier
        $shipping_method_id = $request->input('shipping_method_id');
        $carrier_id = null;

        if ( $shipping_method_id )
        {
            // Need Carrier
            $s_method = ShippingMethod::find( $shipping_method_id );

            $carrier_id = $s_method ? $s_method->carrier_id : null;
        }


        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

                        'sequence_id'          => $request->input('sequence_id'), //  ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),

                        'created_via'          => 'manual',
                        'status'               =>  'draft',
                        'locked'               => 0,

                        'carrier_id'           => $carrier_id,
                     ];

        $request->merge( $extradata );

        $document = $this->document->create($request->all());

        // Move on
        // Maybe ALLWAYS confirm
        if ($request->has('nextAction'))
        {
            switch ( $request->input('nextAction') ) {
                case 'saveAndConfirm':
                    # code...
                    $document->confirm();

                    break;
                
                default:
                    # code...
                    break;
            }
        }

        return redirect('warehouseshippingslips/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function show(Document $warehouseShippingSlip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    // public function edit(Document $warehouseShippingSlip)
    public function edit($id)
    {
        // $document = $warehouseShippingSlip;
        $document = $this->document
                            ->with('warehouse')
                            ->with('warehousecounterpart')
                            ->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'delivery_date'], $document );

        // Not needed, since document is saved as "confirmed":
        $sequenceList = $this->document->sequenceList();


        $templateList = $this->document->templateList();

        $warehouseList =  Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();

        $shipping_methodList = ShippingMethod::pluck('name', 'id')->toArray();

        $carrierList = Carrier::pluck('name', 'id')->toArray();

        return view('warehouse_shipping_slips.edit', compact('document', 'sequenceList', 'templateList', 'warehouseList', 'shipping_methodList', 'carrierList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $warehouseshippingslip)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

//        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
//        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

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
        $document = $warehouseshippingslip;

        // abi_r($document->id);


        // Manage Shipping Method
        $shipping_method_id = $request->input('shipping_method_id', $document->shipping_method_id);

        $def_carrier_id = $document->shippingmethod ? $document->shippingmethod->carrier_id : null;
        if ( $shipping_method_id != $document->shipping_method_id )
        {
            // Need Carrier
            $s_method = ShippingMethod::find( $shipping_method_id );

            $def_carrier_id = $s_method ? $s_method->carrier_id : null;
        }


        // Manage Carrier
        $carrier_id = $request->input('carrier_id', $document->carrier_id);

        if ( $carrier_id != $document->carrier_id )
        {
            //            
            // $carrier_id = $request->input('carrier_id');
            if ( $carrier_id == -1 )
            {
                // Take from Shipping Method
                $carrier_id = $def_carrier_id;
            } 
            else
                if ( (int) $carrier_id < 0 ) $carrier_id = null;

            // Change Carrier
            // $document->force_carrier_id = true;
            // $document->carrier_id = $carrier_id;

            $request->merge( ['carrier_id' => $carrier_id] );
        }

        // abi_r($request->all());die();

        $document->fill($request->all());

        // abi_r($document->id);die();

        // abi_r($document);die();

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

                    break;
                
                case 'saveAndContinue':
                    # code...

                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $nextAction = $request->input('nextAction', '');
        
        if ( $nextAction == 'saveAndContinue' ) 
            return redirect('warehouseshippingslips/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        return redirect('warehouseshippingslips')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id, Request $request)
    public function destroy(Document $warehouseShippingSlip)
    {
        // $document = $this->document->findOrFail($id);
        $document = $warehouseShippingSlip;

        if( !$document->deletable )
            return redirect()->back()
                ->with('error', l('This record cannot be deleted because its Status &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        if ($request->input('open_parents', 0))
        {
            // Open parent Documents (Purchase orders)
        }

        $document->delete();

        return redirect('warehouseshippingslips')      // redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */


    public function getDocumentHeader($id)
    {
        // Some rework needed!!!

        $document = $this->document
                        ->with('customer')
                        ->findOrFail($id);
        
        $customer = $document->customer;

        return 'Peo!';

        return view('warehouse_shipping_slips._tab_edit_header', $this->modelVars() + compact('document', 'customer'));
    }

    
    public function getDocumentLines($id)
    {
//        $model = $this->getParentModelLowerCase();

//        return "$id - $model";

        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);
/*
        $model = 'customerorder';
        $document = CustomerOrder
                        ::with($model.'lines')
                        ->with($model.'lines.product')
                        ->findOrFail($id);

        return $document;
*/
        return view('warehouse_shipping_slips._panel_document_lines', compact('document'));
    }

    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                $this->document_line::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }

    public function getDocumentPayments($id)
    {
        $document = $this->document
                        ->with('payments')
                        ->findOrFail($id);

        return view('warehouse_shipping_slips._panel_document_payments', $this->modelVars() + compact('document'));
    }



    public function searchProduct(Request $request)
    {

        $search = $request->term;

        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
//                                ->IsSaleable()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
//                                ->CheckStock()
//                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }

    public function searchService(Request $request)
    {
        $search = $request->term;

        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->isService()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }

    public function getProduct(Request $request)
    {
        if ( $request->has('supplier_id') )
            return $this->getSupplierProduct($request);
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $customer_id     = $request->input('customer_id');
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $customer_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('tax')->findOrFail(intval($product_id));
        }

        // Customer
        $customer = Customer::findOrFail(intval($customer_id));
        
        // Currency
        $currency = Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Tax
        $tax = $product->tax;
        $taxing_address = Address::findOrFail($request->input('taxing_address_id'));
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $price = $product->getPrice();
        if ( $price->currency->id != $currency->id ) {
            $price = $price->convert( $currency );
        }

        // Calculate price per $customer_id now!
        $customer_price = $product->getPriceByCustomer( $customer, 1, $currency );
//        $tax_percent = $tax->percent;               // Accessor: $tax->getPercentAttribute()
//        $price->applyTaxPercent( $tax_percent );

        if ($customer_price) 
        {
            $customer_price->applyTaxPercentToPrice($tax_percent);

            $ecotax_amount = $product->ecotax ? 
                                  $product->ecotax->amount :
                                  0.0;

            $ecotax_value_label = $product->as_priceable($ecotax_amount).' '.$currency->name;
    
            $data = [
                'product_id' => $product->id,
                'combination_id' => $combination_id,
                'reference' => $product->reference,
                'name' => $product->name,
                'cost_price' => $product->cost_price,
                'unit_price' => [ 
                            'tax_exc' => $price->getPrice(), 
                            'tax_inc' => $price->getPriceWithTax(),
                            'display' => Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $price->getPriceWithTax() : $price->getPrice(),
                            'price_is_tax_inc' => $price->price_is_tax_inc,  
//                            'price_obj' => $price,
                            ],
    
                'unit_customer_price' => [ 
                            'tax_exc' => $customer_price->getPrice(), 
                            'tax_inc' => $customer_price->getPriceWithTax(),
                            'display' => Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $customer_price->getPriceWithTax() : $customer_price->getPrice(),
                            'price_is_tax_inc' => $customer_price->price_is_tax_inc,  
//                            'price_obj' => $customer_price,
                            ],
    
                'tax_percent' => $tax_percent,
                'tax_id' => $product->tax_id,
                'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)",
                'ecotax_value_label' => $ecotax_value_label,
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

                'measure_units' => $product->measureunits->pluck('name', 'id')->toArray(),
            ];
        } else
            $data = [];

        return response()->json( $data );
    }

    public function getProductPrices(Request $request)
    {
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $customer_id     = $request->input('customer_id');
        $recent_sales_this_customer = $request->input('recent_sales_this_customer', 0);
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $customer_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('tax')->findOrFail(intval($product_id));
        }

        $category_id = $product->category_id;

        // Customer
        $customer = Customer::findOrFail(intval($customer_id));
        $customer_group_id = $customer->customer_group_id;
        
        // Currency
        $currency = Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Pricelists
        $pricelists = $product->pricelists; //  PriceList::with('currency')->orderBy('id', 'ASC')->get();

        // Price Rules
        $customer_rules = $product->getPriceRulesByCustomer( $customer );
/*      Old stuff
        $customer_rules = PriceRule::
//                                  where('customer_id', $customer->id)
                                  where(function($q) use ($customer_id, $customer_group_id) {

                                    $q->where('customer_id', $customer_id);

                                    $q->orWhere('customer_group_id', NULL);

                                    $q->orWhere('customer_group_id', 0);

                                    if ( $customer_group_id > 0 )
                                    $q->orWhere('customer_group_id', $customer_group_id);

                                })
                                ->where(function($q) use ($product_id, $category_id) {

                                    $q->where('product_id', $product_id);

                                    // $q->orWhere('category_id', NULL);

                                    // $q->orWhere('category_id', 0);

                                    if ( $category_id > 0 )
                                    $q->orWhere('category_id', $category_id);

                                })
                                ->with('category')
                                ->with('product')
                                ->with('combination')
                                ->with('customergroup')
                                ->with('currency')
                                ->orderBy('product_id', 'ASC')
                                ->orderBy('customer_id', 'ASC')
                                ->orderBy('from_quantity', 'ASC')
                                ->take(7)->get();
*/

/*
        // Recent Sales
        $lines = CustomerOrderLine::where('product_id', $product->id)
                            ->with(["document" => function($q){
                                $q->where('customerorders.customer_id', $customer->id);
                            }])
                            ->with('document')
                            ->with('document.customer')
                            ->whereHas('document', function($q) use ($customer_id, $recent_sales_this_customer) {
                                    if ( $recent_sales_this_customer > 0 )
                                        $q->where('customer_id', $customer_id);
                                })
                            ->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
                            ->select('customer_order_lines.*', 'customer_orders.document_date', \DB::raw('"customerorders" as route'))
                            ->orderBy('customer_orders.document_date', 'desc')
                            ->take(7)->get();
*/
        // Recent Sales
        $model = Configuration::get('RECENT_SALES_CLASS') ?: 'CustomerOrder';
        $class = '\App\\'.$model.'Line';
        $table = snake_case(str_plural($model));
        $route = str_replace('_', '', $table);
        $tableLines = snake_case($model).'_lines';
        $lines = $class::where('product_id', $product->id)
                            ->with(["document" => function($q){
                                $q->where('customerorders.customer_id', $customer->id);
                            }])
                            ->with('document')
                            ->with('document.customer')
                            ->whereHas('document', function($q) use ($customer_id, $recent_sales_this_customer) {
                                    if ( $recent_sales_this_customer > 0 )
                                        $q->where('customer_id', $customer_id);
                                })
                            ->join($table, $tableLines.'.'.snake_case($model).'_id', '=', $table.'.id')
                            ->select($tableLines.'.*', $table.'.document_date', \DB::raw('"'.$route.'" as route'))
                            ->orderBy($table.'.document_date', 'desc')
                            ->take(7)->get();


        return view('warehouse_shipping_slips._form_for_product_prices', $this->modelVars() + compact('product', 'pricelists', 'customer_rules', 'lines'));
    }

    public function getDocumentLine($document_id, $line_id)
    {
        $document_line = $this->document_line
                        ->with('product')
                        ->with('product.tax')
                        ->with('product.ecotax')
                        ->with('measureunit')
                        ->with('packagemeasureunit')
                        ->with('tax')
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [] );
/*
        $unit_customer_final_price = Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $order_line->unit_customer_final_price * ( 1.0 + $order_line->tax_percent / 100.0 ) : 
                                        $order_line->unit_customer_final_price ;
*/
        $product = $document_line->product;
        $tax = $document_line->tax;

        $currency = Context::getContext()->currency;

        $ecotax_amount = $product && $product->ecotax ? 
                              $product->as_priceable($product->ecotax->amount) :
                              '0.00';

        $ecotax_value_label = $ecotax_amount.' '.$currency->name;

        if ( !$document_line->packagemeasureunit )
        {
            $document_line->package_measure_unit_id = $document_line->measure_unit_id;
            $document_line->pmu_conversion_rate = 1.0;
            $document_line->load('packagemeasureunit');
        }

        $pmu_conversion_rate = 1.0;
        $package_label = '';

        if ( $document_line->package_measure_unit_id != $document_line->measure_unit_id)
        {
            $pmu_conversion_rate = $document_line->pmu_conversion_rate;

            $package_label = (int) $pmu_conversion_rate.'x'.$document_line->measureunit->name;
        }

        // abi_r($document_line->toArray());die();

        return response()->json( $document_line->toArray() + [
//            'unit_customer_final_price' => $unit_customer_final_price,
            'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)",
            'ecotax_value_label' => $ecotax_value_label,

            'pmu_conversion_rate' => $pmu_conversion_rate,
            'package_label' => $package_label,
        ] );
    }

    public function deleteDocumentLine($line_id)
    {

        $document_line = $this->document_line
                        ->findOrFail($line_id);
/*
        $document = $this->customerOrder
                        ->findOrFail($order_line->customer_order_id);
*/

        $theID = $this->model_snake_case.'_id';
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


    public function quickAddLines(Request $request, $document_id)
    {
        parse_str($request->input('product_id_values'), $output);
        $product_id_values = $output['product_id_values'];

        parse_str($request->input('combination_id_values'), $output);
        $combination_id_values = $output['combination_id_values'];

        parse_str($request->input('quantity_values'), $output);
        $quantity_values = $output['quantity_values'];

        // abi_r( $document_id );
        // abi_r($request->all());die();


        // Let's Rock!
        $document = $this->document
                        ->findOrFail($document_id);

        // return $document;

        $line_sort_order = $document->getNextLineSortOrder();

        foreach ($product_id_values as $key => $pid) {
            # code...

            $line[] = $document->addProductLine( $pid, $combination_id_values[$key], $quantity_values[$key], ['line_sort_order' => $line_sort_order] );

            $line_sort_order += 10;

            // abi_r($line, true);
        }

        return response()->json( [
                'msg' => 'OK',
                'document' => $document_id,
                'data' => $line,
 //               'currency' => $line[0]->currency,
        ] );

    }





    public function getDocumentAvailability($id, Request $request)
    {
        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        $document_reference = $document->document_reference;

        $stockmovements = collect([]);
        if ( $document->status == 'closed' )
            $stockmovements = StockMovement::
                                  with('warehouse')
                                ->with('product')
                                ->with('combination')
//                              ->with('stockmovementable')
//                                ->with('stockmovementable.document')
                                ->where(function($query) use ($document_reference)
                                {
//                                    $query->where  ( 'id',                 'LIKE', '%'.$document_reference.'%' );
                                    $query->where( 'document_reference', 'LIKE', '%'.$document_reference.'%' );
                                })
                                ->where(function($query)
                                {
                                    $query->where  ( 'stockmovementable_type', '' );
                                    $query->orWhere( 'stockmovementable_type', 'LIKE', '%ShippingSlip%' );
                                })
                                ->orderBy('created_at', 'DESC')
                                ->orderBy('reference', 'ASC')
                                ->get();

        // abi_r($stockmovements);die();

        return view('warehouse_shipping_slips._panel_document_availability', $this->modelVars() + compact('document', 'stockmovements'));
    }

    public function getDocumentAvailabilityModal($id, Request $request)
    {
        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        return view('warehouse_shipping_slips._modal_document_availability_content', $this->modelVars() + compact('document'));
    }


}
