<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;

use App\Customer;
use App\CustomerShippingSlip;
use App\CustomerShippingSlipLine;

use App\Configuration;
use App\Sequence;

class CustomerShippingSlipsController extends BillableController
{

   public function __construct(Customer $customer, CustomerShippingSlip $document, CustomerShippingSlipLine $document_line)
   {
        parent::__construct();

        $this->model_class = CustomerShippingSlip::class;

        $this->customer = $customer;
        $this->document = $document;
        $this->document_line = $document_line;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        // $sequenceList = $this->document->sequenceList();

        // abi_r($sequenceList, true);

        // (new CustomerShippingSlip())->getVars();

/* * /
        abi_r($this->getClass());
        abi_r($this->getClassName());
        abi_r($this->getClassSnakeCase());
        abi_r($this->getClassLastSegment());
        abi_r($this->getParentClass());
        abi_r($this->getParentClassName());
        abi_r($this->getParentClassSnakeCase());
        abi_r($this->getParentClassLowerCase());
        abi_r($this->getParentModelSnakeCase());
        abi_r($this->getParentModelLowerCase(), true);



/ * */        
        return view($this->view_path.'.index', $this->modelVars() + compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;
        $model_snake_case = $this->model_snake_case;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();

        // abi_r(!(count($sequenceList)>0), true);

        if ( !(count($sequenceList)>0) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
        
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

        /*  
                Crear Pedido desde la ficha del Cliente  =>  ya conozco el customer_id

                ruta:  customers/{id}/createorder

                esta ruta la ejecuta:  CustomerShippingSlipsController@createWithCustomer
                enviando un objeto CustomerShippingSlip "vacío"

                y vuelve a /customershippingslips
        */
        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();

        if ( !count($sequenceList) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
        
        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList', 'customer_id'));
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

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        // Extra data
        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

                        'created_via'          => 'manual',
                        'status'               =>  'draft',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );

        $document = $this->document->create($request->all());

        // Maybe...
//        if (  Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') )
//            $customerOrder->confirm();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerShippingSlip  $customershippingslip
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit( $id );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerShippingSlip  $customershippingslip
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = $this->document->findOrFail($id);

        $customer = \App\Customer::find( $document->customer_id );

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

        return view($this->view_path.'.edit', $this->modelVars() + compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerShippingSlip  $customershippingslip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerShippingSlip $customershippingslip)
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
        $document = $customershippingslip;

        $document->fill($request->all());

        // Reset Export date
        if ( $request->input('export_date_form') == '' ) $document->export_date = null;

        $document->save();

        // abi_r($request->all(), true);

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerShippingSlip  $customershippingslip
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerShippingSlip $customershippingslip)
    {
        $id = $customershippingslip->id;

        $customershippingslip->delete();

        return redirect($this->model_path)
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    protected function confirm(CustomerShippingSlip $customershippingslip)
    {
        $customershippingslip->confirm();

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customershippingslip->id], 'layouts').' ['.$customershippingslip->document_reference.']');
    }


/* ****** Production Sheet *************************************************************************************** */    


/* ********************************************************************************************* */    


// https://stackoverflow.com/questions/39812203/cloning-model-with-hasmany-related-models-in-laravel-5-3


/* ********************************************************************************************* */  



    /**
     * AJAX Stuff.
     *
     * 
     */  





    public function FormForService( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view($this->view_path.'._form_for_service_edit');
                break;
            
            case 'create':
                # code...
                return view($this->view_path.'._form_for_service_create');
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






    public function storeShippingSlipLineService(Request $request, $order_id)
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

        // Build ShippingSlipLine Object
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
    
    //        'customer_shipping_slip_id',
            'tax_id' => $tax_id,
            'sales_rep_id' => $request->input('sales_rep_id', 0),
        ];


        // Finishin touches
        $order = $this->document->findOrFail($order_id);

        $order_line = $this->document_line->create( $data );

        $order->customershippingsliplines()->save($order_line);

        // Let's deal with taxes
        $product->sales_equalization = $sales_equalization;   // Sensible default for services!
        $rules = $product->getTaxRules( $order->taxingaddress,  $order->customer );

        $base_price = $order_line->quantity*$order_line->unit_final_price;
        // Rounded $base_price is the same, no matters the value of ROUND_PRICES_WITH_TAX

        $order_line->total_tax_incl = $order_line->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $line_tax = new \App\CustomerShippingSlipLineTax();

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

                $order_line->CustomerShippingSlipLineTaxes()->save($line_tax);
                $order_line->save();

        }

        // Now, update ShippingSlip Totals
        $order->makeTotals();









        return response()->json( [
                'msg' => 'OK',
                'data' => $order_line->toArray(),
//                'extra' => [$p->getPriceWithTax() , $p->getPrice() , $p->as_priceable($rule->amount)],
        ] );
    }


    public function updateShippingSlipLineService(Request $request, $line_id)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $order_line = $this->document_line
                        ->findOrFail($line_id);

        $order_id = $order_line->customer_shipping_slip_id;

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

        // Build ShippingSlipLine Object
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
    
    //        'customer_shipping_slip_id',
            'tax_id' => $tax_id,
            'sales_rep_id' => $request->input('sales_rep_id', 0),
        ];


        // Finishin touches
        $order = $this->document->findOrFail($order_id);

        $order_line->update( $data );

//        $order->customershippingsliplines()->save($order_line);

        // Let's deal with taxes
        $order_line->customershippingsliplinetaxes()->delete();

        $product->sales_equalization = $sales_equalization;   // Sensible default for services!
        $rules = $product->getTaxRules( $order->taxingaddress,  $order->customer );

        $base_price = $order_line->quantity*$order_line->unit_final_price;
        // Rounded $base_price is the same, no matters the value of ROUND_PRICES_WITH_TAX

        $order_line->total_tax_incl = $order_line->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $line_tax = new \App\CustomerShippingSlipLineTax();

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

                $order_line->CustomerShippingSlipLineTaxes()->save($line_tax);
                $order_line->save();

        }

        // Now, update ShippingSlip Totals
        $order->makeTotals();









        return response()->json( [
                'msg' => 'OK',
                'data' => $order_line->toArray()
        ] );
    }









    /*
    |--------------------------------------------------------------------------
    | Not CRUD stuff here
    |--------------------------------------------------------------------------
    */


/* ********************************************************************************************* */    



}


/* ********************************************************************************************* */



/* ********************************************************************************************* */

