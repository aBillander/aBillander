<?php 

namespace App\Traits;

trait BillableDocumentAsIsLinesTrait
{

    /**
     * Add Product "As Is" to ShippingSlip
     *
     * 
     */
    public function addProductAsIsLine( $product_id, $combination_id = null, $quantity = 1.0, $params = [] )
    {
        // Do the Mambo!
        $line_type = 'product';

        // Customer
        $customer = $this->customer;
        $salesrep = array_key_exists('sales_rep_id', $params) 
                            ? SalesRep::find( (int) $params['sales_rep_id'] ) 
                            : $customer->salesrep;
        
        // Currency
        $currency = $this->document_currency;

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
        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $product->name;

        $measure_unit_id = $product->measure_unit_id;

        $package_measure_unit_id = array_key_exists('package_measure_unit_id', $params) 
                            ? $params['package_measure_unit_id'] 
                            : $product->measure_unit_id;

        $pmu_conversion_rate = 1.0; // Temporarily default

        $pmu_label = ''; // Temporarily default

        // Measure unit stuff...
        if ( $package_measure_unit_id != $measure_unit_id )
        {
            $mu  = $product->measureunits->where('id', $measure_unit_id        )->first();
            $pmu = $product->measureunits->where('id', $package_measure_unit_id)->first();

            $pmu_conversion_rate = $pmu->conversion_rate;

            $quantity = $quantity * $pmu_conversion_rate;

            $pmu_label = array_key_exists('pmu_label', $params) && $params['pmu_label']
                            ? $params['pmu_label'] 
                            : $pmu->name.' : '.(int) $pmu_conversion_rate.'x'.$mu->name;

        }

        // $cost_price = $product->cost_price;
        // Do this ( because of getCostPriceAttribute($value) ):
        $cost_price = $product->getOriginal('cost_price');

        // Tax
        $tax = $product->tax;
        $taxing_address = $this->taxingaddress;
        $tax_percent = $tax->getTaxPercent( $taxing_address );
        $sales_equalization = array_key_exists('sales_equalization', $params) 
                            ? $params['sales_equalization'] 
                            : $customer->sales_equalization;

        // Product Price
        $price = $product->getPrice();
//        if ( $price->currency->id != $currency->id ) {
//            $price = $price->convert( $currency );
//        }
        $unit_price = $price->getPrice();

        // Calculate price per $customer_id now!
        // $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );
        $customer_price = $product->getPriceByCustomerPriceList( $customer, $quantity, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $customer_price->price_is_tax_inc;        // <= $document->customer->currentPricesEnteredWithTax( $document->document_currency )

        // Customer Final Price
        if ( array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'] / $pmu_conversion_rate, $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = clone $customer_price;
        }

        // Discount
        $discount_percent = array_key_exists('discount_percent', $params) 
                            ? $params['discount_percent'] 
                            : 0.0;

        // Final Price
        $unit_final_price = clone $unit_customer_final_price;
        if ( $discount_percent ) 
            $unit_final_price->applyDiscountPercent( $discount_percent );

        // Sales Rep
        $sales_rep_id = array_key_exists('sales_rep_id', $params) 
                            ? $params['sales_rep_id'] 
                            : optional($salesrep)->id;
        
        $commission_percent = array_key_exists('sales_rep_id', $params) && array_key_exists('commission_percent', $params) 
                            ? $params['commission_percent'] 
                            : optional($salesrep)->getCommission( $product, $customer ) ?? 0.0;



        // Misc
        $line_sort_order = array_key_exists('line_sort_order', $params) 
                            ? $params['line_sort_order'] 
                            : $this->getNextLineSortOrder();
/*
        $extra_quantity = array_key_exists('extra_quantity', $params) 
                            ? $params['extra_quantity'] 
                            : 0.0;

        $extra_quantity_label = array_key_exists('extra_quantity_label', $params) 
                            ? $params['extra_quantity_label'] 
                            : '';
*/
        $extra_quantity = 0.0;

        $extra_quantity_label = '';
        
        $notes = array_key_exists('notes', $params) 
                            ? $params['notes'] 
                            : '';


        // Build OrderLine Object
        $data = [
            'line_sort_order' => $line_sort_order,
            'line_type' => $line_type,
            'product_id' => $product_id,
            'combination_id' => $combination_id,
            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
            'measure_unit_id' => $measure_unit_id,
            'extra_quantity'       => $extra_quantity,
            'extra_quantity_label' => $extra_quantity_label,

            'package_measure_unit_id' => $package_measure_unit_id,
            'pmu_conversion_rate'     => $pmu_conversion_rate,
            'pmu_label'               => $pmu_label,

            'prices_entered_with_tax' => $pricetaxPolicy,
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price->getPrice(),
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price->getPriceWithTax(),
            'unit_final_price' => $unit_final_price->getPrice(),
            'unit_final_price_tax_inc' => $unit_final_price->getPriceWithTax(), 
            'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

            'total_tax_incl' => $quantity * $unit_final_price->getPriceWithTax(),
            'total_tax_excl' => $quantity * $unit_final_price->getPrice(),

            'tax_percent' => $tax_percent,
            'commission_percent' => $commission_percent,
            'notes' => $notes,
            'locked' => 0,
    
    //        'customer_order_id',
            'tax_id' => $tax->id,
            'sales_rep_id' => $sales_rep_id,
        ];


        // Finishing touches
        $lineClass = $this->getClassName().'Line';
        $document_line = ( new $lineClass() )->create( $data );

        $this->lines()->save($document_line);


        // Let's deal with taxes
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );

        $document_line->applyTaxRules( $rules );

        // Hummm! What about extra units (quantity at no cost)?
        if ( $extra_quantity > 0.0 )
        {
            // Re-Build OrderLine Object
            $data['line_sort_order'] = $line_sort_order + 1;    // Stay close to previous line, because this line "is a child of" previous line
            $data['quantity'] = $extra_quantity;

            $data['extra_quantity'] = 0.0;
            $data['extra_quantity_label'] = '';

            $data['unit_customer_final_price'] = 0.0;
            $data['unit_customer_final_price_tax_inc'] = 0.0;

            $data['unit_final_price'] = 0.0;
            $data['unit_final_price_tax_inc'] = 0.0;

            $data['total_tax_incl'] = 0.0;
            $data['total_tax_excl'] = 0.0;

            $data['notes'] = $extra_quantity_label;

            // Add extra line
            $document_extra_line = ( new $lineClass() )->create( $data );

            $this->lines()->save($document_extra_line);


            // Let's deal with taxes
            $document_extra_line->applyTaxRules( $rules );
        }


        // Now, update Document Totals
        $this->makeTotals();


        // Good boy, bye then
        return $document_line;

    }

}