<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CustomerOrderTemplate;
use App\CustomerOrderTemplateLine;

use App\Configuration;
use App\Customer;
use App\Template;
use App\CustomerOrder;
use App\ShippingMethod;

class CustomerOrderTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerordertemplates = CustomerOrderTemplate::
                                          with('customer')
                                        ->withCount('customerordertemplatelines')
                                        ->orderBy('id', 'asc')
                                        ->get();

        return view('customer_order_templates.index', compact('customerordertemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_order_templates.create');
    }

    public function createAfterOrder($id)
    {
        $document_id = 0;

        $document = CustomerOrder::find($id);

        if ( $document )
            $document_id = $document->id;
        
        return view('customer_order_templates.create_after_order', compact('document_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, CustomerOrderTemplate::$rules);

        $customerordertemplate = CustomerOrderTemplate::create($request->all());

        return redirect('customerordertemplates')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerordertemplate->id], 'layouts') . $request->input('name'));
    }

    public function storeAfterOrder(Request $request)
    {
        $rules = CustomerOrderTemplate::$rules;

        // Since Customer & Shipping Address is taken after the Order:
        $rules = array_filter($rules, function($k) {
                return $k != 'customer_id' && $k != 'shipping_address_id';
            }, ARRAY_FILTER_USE_KEY);

        // And Order **MUST** be issued
        $rules = $rules + ['customer_order' => 'required|min:1'];

        $this->validate($request, $rules);

        $document_id = $request->input('customer_order');
        
        $document = CustomerOrder::with('lines')->where('document_reference', $document_id)->first();

        if ( !$document )
            $document = CustomerOrder::with('lines')->where('id', $document_id)->first();

        if ( !$document )
            return redirect('customerordertemplates')
                    ->with('error', l('Unable to load this record &#58&#58 (:id) ', ['id' => $document_id], 'layouts'));

        $data = [
                'customer_id' => $document->customer_id,
                'shipping_address_id' => $document->shipping_address_id,
        ];

        $request->merge( $data );

        $this->validate($request, CustomerOrderTemplate::$rules);

        $extra_data = [
//                'last_customer_order_id' => $document->id,
//                'last_document_reference' => $document->document_reference,
                'total_tax_incl' => $document->total_tax_incl,
                'total_tax_excl' => $document->total_tax_excl,
        ];

        // Create Template
        $customerordertemplate = CustomerOrderTemplate::create($request->all() + $extra_data);

        // Create Template Lines
        foreach ($document->lines->where('line_type', 'product') as $line) 
        {
            $data = [
                'line_sort_order' => $line->line_sort_order, 
//                'line_type', 
                'product_id' => $line->product_id, 
                'combination_id' => $line->combination_id,
                'quantity' => $line->quantity, 
                'measure_unit_id' => $line->measure_unit_id, 
//                'package_measure_unit_id', 
//                'pmu_conversion_rate',  => $line->
//                'pmu_label', 
                'notes' => $line->notes, 
                'active' => 1,
            ];

            $customerordertemplateline = CustomerOrderTemplateLine::create($data);

            $customerordertemplate->customerordertemplatelines()->save($customerordertemplateline);
        }


        return redirect('customerordertemplates')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerordertemplate->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerOrderTemplate $customerordertemplate)
    {
        return $this->edit($customerordertemplate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerOrderTemplate $customerordertemplate)
    {
        return view('customer_order_templates.edit', compact('customerordertemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerOrderTemplate $customerordertemplate)
    {
        $this->validate($request, CustomerOrderTemplate::$rules);

        $customerordertemplate->update($request->all());

        return redirect('customerordertemplates')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customerordertemplate->id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerOrderTemplate $customerordertemplate)
    {
        $id = $customerordertemplate->id;

        $customerordertemplate->delete();

        return redirect('customerordertemplates')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                CustomerOrderTemplateLine::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }




    public function createCustomerOrder(CustomerOrderTemplate $customerordertemplate, Request $request)
    {
        $customerordertemplate->load(['customerordertemplatelines', 'customer', 'customer.currency', 'shippingaddress', 'shippingaddress.shippingmethod', 'template']);
        $customer = $customerordertemplate->customer;
        $shippingaddress = $customerordertemplate->shippingaddress;

        $cotlines = $customerordertemplate->customerordertemplatelines;

        // Create Customer Order Header
        $shipping_method_id =   $shippingaddress->shippingmethod     ?
                                $shippingaddress->shippingmethod->id :
                                $customer->getShippingMethodId();

        $shipping_method = ShippingMethod::find($shipping_method_id);
        $carrier_id = $shipping_method ? $shipping_method->carrier_id : null;

        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'customer_id' => $customer->id,
//            'user_id' => $this->,

            'sequence_id' => Configuration::getInt('DEF_CUSTOMER_ORDER_SEQUENCE'),

            'created_via' => 'customer_order_template',

            'document_date' => \Carbon\Carbon::now(),

            'currency_conversion_rate' => $customer->currency->conversion_rate,
//            'down_payment' => $this->down_payment,

            'document_discount_percent' => $customerordertemplate->document_discount_percent > 0.0 ? 
                                                $customerordertemplate->document_discount_percent  : 
                                                $customer->discount_percent,
            
            'document_ppd_percent'      => $customerordertemplate->document_ppd_percent > 0.0      ? 
                                                $customerordertemplate->document_ppd_percent       : 
                                                $customer->discount_ppd_percent,

            'total_currency_tax_incl' => 0.0,
            'total_currency_tax_excl' => 0.0,
//            'total_currency_paid' => $this->total_currency_paid,

            'total_tax_incl' => 0.0,
            'total_tax_excl' => 0.0,

//            'commission_amount' => $this->commission_amount,

            // Skip notes

            'status' => 'draft',
            'onhold' => 0,
            'locked' => 0,

            'invoicing_address_id' => $customer->invoicing_address_id,
            'shipping_address_id' => $customerordertemplate->shipping_address_id,   // To do: check if exist in customer address book
            'warehouse_id' => Configuration::getInt('DEF_WAREHOUSE'),
            'shipping_method_id' => $shipping_method_id,
            'carrier_id' => $carrier_id,
            'sales_rep_id' => $customer->sales_rep_id,
            'currency_id' => $customer->currency->id,
            'payment_method_id' => $customer->getPaymentMethodId(),
            'template_id' => $customerordertemplate->template_id > 0 ? : Configuration::getInt('DEF_CUSTOMER_ORDER_TEMPLATE'),
        ];

        // Model specific data
        $extradata = [
//            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];

        // Let's get dirty
        $order = CustomerOrder::create( $data + $extradata );



        // Create Customer Order Lines
        // Cowboys, lett's herd the cattle!
        foreach ($cotlines as $cotline) {
            # code...

            $line[] = $order->addProductLine( $cotline->product_id, null, $cotline->quantity, ['notes' => $cotline->notes] );

            // ^- Document totals are calculated when a line is added   
        }

        
        // Did I forget something? Maybe YES
        // Shipping Cost Stuff!
        $method = $order->shippingmethod ?: $order->shippingaddress->getShippingMethod();
        $free_shipping = (Configuration::getNumber('ABCC_FREE_SHIPPING_PRICE') >= 0.0) ? Configuration::getNumber('ABCC_FREE_SHIPPING_PRICE') : null;

        list($shipping_label, $cost, $tax) = array_values(ShippingMethod::costPriceCalculator( $method, $order, $free_shipping ));

        $tax_id      = $tax['id'];
        $tax_percent = $tax['sales'];
        $tax_se_percent = $tax['sales_equalization'];

        $params = [
            'line_type' => 'shipping',
//            'prices_entered_with_tax' => $pricetaxPolicy,
            'name' => $shipping_label.' :: '.$method->name,
            'cost_price' => $cost,
            'unit_price' => $cost,
            'discount_percent' => 0.0,
            'unit_customer_price' => $cost,
            'unit_customer_final_price' => $cost,
            'tax_id' => $tax_id,
            'sales_equalization' => $order->customer->sales_equalization,

//            'line_sort_order' => $request->input('line_sort_order'),
//            'notes' => $request->input('notes', ''),

            'prices_entered_with_tax' => 0,
        ];

        $line[] = $order->addServiceLine( null, null, 1.0, $params );



        // Good boy, so far
        if ( Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') )
            $order->confirm();
        
        $customerordertemplate->last_used_at =  $order->document_date;
        $customerordertemplate->last_customer_order_id =  $order->id;
        $customerordertemplate->last_document_reference =  $order->document_reference;
        $customerordertemplate->total_tax_incl =  $order->total_tax_incl;
        $customerordertemplate->total_tax_excl =  $order->total_tax_excl;
        $customerordertemplate->save();

        return redirect()->route('customerorders.edit', $order->id)
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $order->id], 'layouts'));
    }
}
