<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;

use App\Configuration;
use App\Sequence;

use App\Traits\BillableIntrospectorTrait;
use App\Traits\BillableControllerTrait;
use App\Traits\BillableFormsControllerTrait;
use App\Traits\BillableDocumentControllerTrait;

class BillableController extends Controller
{

   use BillableIntrospectorTrait;
   use BillableControllerTrait;
   use BillableFormsControllerTrait;
   use BillableDocumentControllerTrait;

   protected $model, $model_snake_case, $model_path, $view_path;
   
   protected $customer, $document, $document_line;

   public function __construct()
   {
        $this->model = str_singular($this->getParentClass());       // CustomerShippingSlip
        $this->model_snake_case = $this->getParentModelSnakeCase(); // customer_shipping_slip
        $this->model_path = $this->getParentClassLowerCase();       // customershippingslips
        $this->view_path = $this->getParentClassSnakeCase();            // customer_shipping_slips
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

            $customers = \App\Customer::
                    // select('id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id', 'invoice_sequence_id')
                                      with('currency')
                                    ->with('addresses')
                                    ->find( $search );

            $customers->invoice_sequence_id = $customers->getInvoiceSequenceId();

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

            $customers = \App\Customer::select('id', 'name_fiscal', 'name_commercial', 'identification', 'reference_external', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id')
                                    ->where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
                                    ->isNotBlocked()
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
            $customer = \App\Customer::find($id);

            if ( $customer )
            	return response()->json( $customer->getAddressList() );
        }

        // die silently
        return json_encode( [] );
    }

    
    public function getDocumentHeader($id)
    {
        // Some rework needed!!!

        $document = $this->document
                        ->with('customer')
                        ->findOrFail($id);
        
        $customer = $document->customer;

        return 'Peo!';

        return view($this->view_path.'._tab_edit_header', $this->modelVars() + compact('document', 'customer'));
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
        $document = \App\CustomerOrder
                        ::with($model.'lines')
                        ->with($model.'lines.product')
                        ->findOrFail($id);

        return $document;
*/
        return view($this->view_path.'._panel_document_lines', $this->modelVars() + compact('document'));
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

        return view($this->view_path.'._panel_document_payments', $this->modelVars() + compact('document'));
    }



    public function searchProduct(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->IsSaleable()
                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
                                ->CheckStock()
                                ->IsActive()
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
                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );


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
        $customer_price = $product->getPriceByCustomer( $customer, 1, $currency );
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

    public function getProductPrices(Request $request)
    {
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $customer_id     = $request->input('customer_id');
        $recent_sales_this_customer = $request->input('recent_sales_this_customer', 0);
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

        $category_id = $product->category_id;

        // Customer
        $customer = \App\Customer::findOrFail(intval($customer_id));
        $customer_group_id = $customer->customer_group_id;
        
        // Currency
        $currency = \App\Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Pricelists
        $pricelists = $product->pricelists; //  \App\PriceList::with('currency')->orderBy('id', 'ASC')->get();

        // Price Rules

        $customer_rules = \App\PriceRule::
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
/*
        // Recent Sales
        $lines = \App\CustomerOrderLine::where('product_id', $product->id)
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


        return view($this->view_path.'._form_for_product_prices', $this->modelVars() + compact('product', 'pricelists', 'customer_rules', 'lines'));
    }

    public function getDocumentLine($document_id, $line_id)
    {
        $document_line = $this->document_line
                        ->with('product')
                        ->with('product.tax')
                        ->with('measureunit')
                        ->with('tax')
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [] );
/*
        $unit_customer_final_price = \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $order_line->unit_customer_final_price * ( 1.0 + $order_line->tax_percent / 100.0 ) : 
                                        $order_line->unit_customer_final_price ;
*/
        $tax = $document_line->tax;

        return response()->json( $document_line->toArray() + [
//            'unit_customer_final_price' => $unit_customer_final_price,
            'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)"
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


        // Let's Rock!
        $document = $this->document
                        ->with('customer')
                        ->with('taxingaddress')
                        ->with('salesrep')
                        ->with('currency')
                        ->findOrFail($document_id);

        // return $document;

        foreach ($product_id_values as $key => $pid) {
            # code...

            $line[] = $document->addProductLine( $pid, $combination_id_values[$key], $quantity_values[$key], [] );

            // abi_r($line, true);
        }

        return response()->json( [
                'msg' => 'OK',
                'document' => $document_id,
                'data' => $line,
 //               'currency' => $line[0]->currency,
        ] );

    }





    public function getDocumentProfit($id)
    {
        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->with('lines.product.ecotax')
                        ->findOrFail($id);

        return view($this->view_path.'._panel_document_profitability', $this->modelVars() + compact('document'));
    }


    public function getDocumentAvailability($id, Request $request)
    {
        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        return view($this->view_path.'._panel_document_availability', $this->modelVars() + compact('document'));
    }

    public function getDocumentAvailabilityModal($id, Request $request)
    {
        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        return view($this->view_path.'._modal_document_availability_content', $this->modelVars() + compact('document'));
    }



    public function reloadCosts($id, Request $request)
    {
        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        $document->loadLineCosts();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }




    public function updateDocumentTotal(Request $request, $document_id)
    {
        $document = $this->document
//                        ->with('customershippingsliplines')
//                        ->with('customershippingsliplines.product')
                        ->findOrFail($document_id);

        $document->document_discount_percent = $request->input('document_discount_percent', 0.0);

        $document->save();

        // Now, update ShippingSlip Totals
        $document->makeTotals();

        $view_path = $this->view_path;

        return view($view_path.'._panel_document_total', compact('document', 'view_path'));
    }


/* ********************************************************************************************* */  


    /**
     * MISC Stuff.
     *
     * 
     */

    public function duplicateDocument($id)
    {
        $document = $this->document->findOrFail($id);

        // Duplicate
        $clone = $document->replicate();

        // Extra data
        $seq = Sequence::findOrFail( $document->sequence_id );

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

        $clone->document_prefix      = null;
        $clone->document_id          = 0;

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

        // Duplicate Lines
        if ( $document->lines()->count() )
            foreach ($document->lines as $line) {

                $clone_line = $line->replicate();

                $clone->lines()->save($clone_line);

                if ( $line->taxes()->count() )
                    foreach ($line->taxes as $linetax) {

                        $clone_line_tax = $linetax->replicate();

                        $clone_line->taxes()->save($clone_line_tax);

                    }
            }

        // Save Customer document
        $clone->push();

        // Good boy:
        // not always needed
        // $clone->confirm();


        return redirect()->route($this->model_path.'.edit', [$clone->id])
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $clone->id], 'layouts'));
    }



}
