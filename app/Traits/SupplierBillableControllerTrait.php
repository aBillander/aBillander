<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\Models\Context;
use App\Models\Configuration;
use App\Models\Supplier;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Combination;

trait SupplierBillableControllerTrait
{
    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxSupplierSearch(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;

//        if ( $query )

        if ($request->has('supplier_id'))
        {
            $search = $request->supplier_id;

            $suppliers = Supplier::
                    // select('id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id', 'invoice_sequence_id')
                                      with('currency')
                                    ->with('addresses')
                                    ->find( $search );

            $suppliers->invoice_sequence_id = $suppliers->getInvoiceSequenceId();

//            return $suppliers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $suppliers );
//            return json_encode( $suppliers );
            return response()->json( $suppliers );
        }

        if ($request->has('term'))
        {
            $search = $request->term;

            $suppliers = Supplier::select('id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id')
                                    ->where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
                                    ->isNotBlocked()
                                    ->with('currency')
                                    ->with('addresses')
                                    ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                    ->get();

//            return $suppliers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $suppliers );
//            return json_encode( $suppliers );
            return response()->json( $suppliers );
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
    }

    public function supplierAdressBookLookup($id)
    {
        if (intval($id)>0)
        {
            $supplier = Supplier::find($id);

            if ( $supplier )
                return response()->json( $supplier->getAddressList() );
        }

        // die silently
        return json_encode( [] );
    }



    /**
     * SEARCH
     *
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function searchSupplierProduct(Request $request)
    {
        $search = $request->term;

        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->IsPurchaseable()
                                ->qualifyForSupplier( $request->input('supplier_id'), $request->input('currency_id') )
//                                ->CheckStock()
                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $products );
    }

    public function searchSupplierService(Request $request)
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
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $products );
    }

    public function getSupplierProduct(Request $request)
    {
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $supplier_id     = $request->input('supplier_id');
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $supplier_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('measureunit')->with('purchasemeasureunit')->with('tax')->findOrFail(intval($product_id));
        }

        // Supplier
        $supplier = Supplier::findOrFail(intval($supplier_id));
        
        // Currency
        $currency = Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Tax
        $tax = $product->tax;
        // $taxing_address = Address::findOrFail($request->input('taxing_address_id'));
        $tax_percent = $tax->getTaxPercent();          // $taxing_address );

        $price = $product->getPrice();
        if ( $price->currency->id != $currency->id ) {
            $price = $price->convert( $currency );
        }

        // Calculate price per $supplier_id now!
        $supplier_price = $product->getPriceBySupplier( $supplier, 1, $currency );
//        $tax_percent = $tax->percent;               // Accessor: $tax->getPercentAttribute()
//        $price->applyTaxPercent( $tax_percent );

        if ($supplier_price) 
        {
            $supplier_price->applyTaxPercentToPrice($tax_percent);        

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
    
                'unit_supplier_price' => [ 
                            'tax_exc' => $supplier_price->getPrice(), 
                            'tax_inc' => $supplier_price->getPriceWithTax(),
                            'display' => Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $supplier_price->getPriceWithTax() : $supplier_price->getPrice(),
                            'price_is_tax_inc' => $supplier_price->price_is_tax_inc,  
//                            'price_obj' => $supplier_price,
                            ],

                'discount_percent' => $supplier_price->discount_percent,
    
                'tax_percent' => $tax_percent,
                'tax_id' => $product->tax_id,
                'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)",
                'ecotax_value_label' => $ecotax_value_label,
                'supplier_id' => $supplier_id,
                'currency' => $currency,
    
                'measure_unit_id' => $product->measure_unit_id,
                'purchase_measure_unit_id' => $product->supplymeasureunit->id,
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


    public function getSupplierProductPrices(Request $request)
    {
        // Temporarily
        return '';
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $supplier_id     = $request->input('supplier_id');
        $recent_sales_this_supplier = $request->input('recent_sales_this_supplier', 0);
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $supplier_id, $currency_id ] );

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
        $supplier = Supplier::findOrFail(intval($supplier_id));
        $supplier_group_id = $supplier->supplier_group_id;
        
        // Currency
        $currency = Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Pricelists
        $pricelists = $product->pricelists; //  PriceList::with('currency')->orderBy('id', 'ASC')->get();

        // Price Rules
        $supplier_rules = $product->getPriceRulesByCustomer( $supplier );
/*      Old stuff
        $supplier_rules = PriceRule::
//                                  where('supplier_id', $supplier->id)
                                  where(function($q) use ($supplier_id, $supplier_group_id) {

                                    $q->where('supplier_id', $supplier_id);

                                    $q->orWhere('supplier_group_id', NULL);

                                    $q->orWhere('supplier_group_id', 0);

                                    if ( $supplier_group_id > 0 )
                                    $q->orWhere('supplier_group_id', $supplier_group_id);

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
                                ->with('suppliergroup')
                                ->with('currency')
                                ->orderBy('product_id', 'ASC')
                                ->orderBy('supplier_id', 'ASC')
                                ->orderBy('from_quantity', 'ASC')
                                ->take(7)->get();
*/

/*
        // Recent Sales
        $lines = CustomerOrderLine::where('product_id', $product->id)
                            ->with(["document" => function($q){
                                $q->where('supplierorders.supplier_id', $supplier->id);
                            }])
                            ->with('document')
                            ->with('document.supplier')
                            ->whereHas('document', function($q) use ($supplier_id, $recent_sales_this_supplier) {
                                    if ( $recent_sales_this_supplier > 0 )
                                        $q->where('supplier_id', $supplier_id);
                                })
                            ->join('supplier_orders', 'supplier_order_lines.supplier_order_id', '=', 'supplier_orders.id')
                            ->select('supplier_order_lines.*', 'supplier_orders.document_date', \DB::raw('"supplierorders" as route'))
                            ->orderBy('supplier_orders.document_date', 'desc')
                            ->take(7)->get();
*/
        // Recent Sales
        $model = Configuration::get('RECENT_SALES_CLASS') ?: 'CustomerOrder';
        $class = '\App\\Models\\'.$model.'Line';
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);
        $tableLines = \Str::snake($model).'_lines';
        $lines = $class::where('product_id', $product->id)
                            ->with(["document" => function($q){
                                $q->where('supplierorders.supplier_id', $supplier->id);
                            }])
                            ->with('document')
                            ->with('document.supplier')
                            ->whereHas('document', function($q) use ($supplier_id, $recent_sales_this_supplier) {
                                    if ( $recent_sales_this_supplier > 0 )
                                        $q->where('supplier_id', $supplier_id);
                                })
                            ->join($table, $tableLines.'.'.\Str::snake($model).'_id', '=', $table.'.id')
                            ->select($tableLines.'.*', $table.'.document_date', \DB::raw('"'.$route.'" as route'))
                            ->orderBy($table.'.document_date', 'desc')
                            ->take(7)->get();


        return view($this->view_path.'._form_for_product_prices', $this->modelVars() + compact('product', 'pricelists', 'supplier_rules', 'lines'));
    }
}