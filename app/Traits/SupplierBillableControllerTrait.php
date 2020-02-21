<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\Context;
use App\Configuration;
use App\Supplier;
use App\Currency;
use App\Product;
use App\Combination;

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
                                    ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );

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
                                ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );


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
                                ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );


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
            $product = Product::with('tax')->findOrFail(intval($product_id));
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
                'supplier_id' => $supplier_id,
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

}