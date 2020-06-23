<?php 

namespace App\Traits;

use App\Price;

trait BillableDocumentLinesTrait
{
   use BillableDocumentAsIsLinesTrait;

   use SupplierBillableDocumentLinesTrait;      // <<< ???

    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Pump it up!
    |--------------------------------------------------------------------------
    */


    public function updateLine($line_id, $params = ['line_type' => 'comment'])
    {
        $line_type = $params['line_type'] ?: '';

        $params = array_except($params, ['line_type']);

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->updateProductLine($line_id, $params);
                break;
            
            case 'service':
            case 'shipping':
                # code...
                return $this->updateServiceLine($line_id, $params);
                break;
            
            case 'comment':
                # code...
                return $this->updateCommentLine($line_id, $params);
                break;
            
            default:
                # code...
                // Document Line Type not supported
                return NULL;
                break;
        }


        // Good boy, bye then
        return NULL;
    }


    /**
     * Add Product to ShippingSlip
     *
     *     'prices_entered_with_tax', 'unit_customer_final_price', 'discount_percent', 'line_sort_order', 'sales_equalization', 'sales_rep_id', 'commission_percent'
     */
    public function addProductLine( $product_id, $combination_id = null, $quantity = 1.0, $params = [] )
    {
        // Do the Mambo!
        $line_type = 'product';

        // Customer
        $customer = $this->customer;
        $salesrep = $customer->salesrep;
        
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

        // Still with me? 
        if ( $package_measure_unit_id != $measure_unit_id  &&
        
            // Is there any package rule?
             $pack_rule = $customer->getPackageRule( $product, $package_measure_unit_id, $currency ) )
        {
                // Calculate quantity conversion
                $pmu_conversion_rate = $pack_rule->conversion_rate;
                $pmu_label           = $pack_rule->name;

                // Assumes $pack_rule is not null
                // $package_price = $pack_rule->price;
                $customer_final_price = new Price( $pack_rule->getUnitPrice() );
                $customer_final_price->applyTaxPercent( $tax_percent );

                // Still one thing left: rule_type = 'promo' (avoid)
                $promo_rule = null;
            

        } else {
            //
                $customer_final_price = $product->getPriceByCustomerPriceRules( $customer, $quantity, $currency );

                // Still one thing left: rule_type = 'promo'
                $promo_rule = $customer->getExtraQuantityRule( $product, $currency );
        }



        
        if ( !$customer_final_price )
            $customer_final_price = clone $customer_price;  // No price Rules available
        
        // Better price?
        if ( $customer_final_price->getPrice() > $unit_customer_price )
        {
            $customer_final_price = clone $customer_price;
        }
        $unit_customer_final_price = $customer_final_price->getPrice();


        // Still one thing left: rule_type = 'promo'
        $extra_quantity = 0.0;
        $extra_quantity_label = '';

        if ($promo_rule)
        {
            // First: Does it apply?
            if ( $unit_customer_final_price == $unit_customer_price )   // No price rule has been applied
            {
                $extra_quantity = floor( $quantity / $promo_rule->from_quantity ) * $promo_rule->extra_quantity;
                $extra_quantity_label = $extra_quantity > 0 ? $promo_rule->name : '';
            }
        }



        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $customer_price->price_is_tax_inc;
/*
        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'], $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = clone $customer_price;
        }
*/
        // Discount
        $discount_percent = array_key_exists('discount_percent', $params) 
                            ? $params['discount_percent'] 
                            : 0.0;

        // Final Price
        $final_price = clone $customer_final_price;
//        if ( $discount_percent ) 
//            $unit_final_price->applyDiscountPercent( $discount_percent );

        // Sales Rep
        $sales_rep_id = array_key_exists('sales_rep_id', $params) 
                            ? $params['sales_rep_id'] 
                            : optional($salesrep)->id;
        
        $commission_percent = array_key_exists('sales_rep_id', $params) && array_key_exists('commission_percent', $params) 
                            ? $params['commission_percent'] 
                            : optional($salesrep)->getCommision( $product, $customer ) ?? 0.0;



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
            'unit_customer_final_price' => $customer_final_price->getPrice(),
            'unit_customer_final_price_tax_inc' => $customer_final_price->getPriceWithTax(),
            'unit_final_price' => $final_price->getPrice(),
            'unit_final_price_tax_inc' => $final_price->getPriceWithTax(), 
            'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

            'total_tax_incl' => $quantity * $final_price->getPriceWithTax(),
            'total_tax_excl' => $quantity * $final_price->getPrice(),

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

    /**
     * Add Product to Order
     *
     *     'prices_entered_with_tax', 'unit_customer_final_price', 'discount_percent', 'line_sort_order', 'sales_equalization', 'sales_rep_id', 'commission_percent'
     */
    public function updateProductLine( $line_id, $params = [] )
    {
        // Do the Mambo!
        $lineClass = $this->getClassName().'Line';
        $relation = 'document';     // strtolower($this->getClass());

        $document_line = ( new $lineClass() )->where($this->getClassSnakeCase().'_id', $this->id)
                        ->with($relation)
                        ->with($relation.'.customer')
                        ->with('product')
//                        ->with('combination')
                        ->with('product.tax')
                        ->with('product.ecotax')
                        ->with('measureunit')
                        ->with('packagemeasureunit')
                        ->with('tax')
                        ->findOrFail($line_id);


        // Customer
        $customer = $this->customer;
        $salesrep = $this->salesrep;
        
        // Currency
        $currency = $this->document_currency;

        // Product
        if ($document_line->combination_id>0) {
//            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
//            $product = $combination->product;
//            $product->reference = $combination->reference;
//            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = $document_line->product;
        }

        $pmu_conversion_rate = 1.0; // Temporarily default

        // Measure unit stuff...
        if ( $document_line->package_measure_unit_id && ($document_line->package_measure_unit_id != $document_line->measure_unit_id) )
        {
            $pmu_conversion_rate = $document_line->pmu_conversion_rate;
/*
            $quantity = $quantity * $pmu_conversion_rate;

            $pmu_label = array_key_exists('pmu_label', $params) && $params['pmu_label']
                            ? $params['pmu_label'] 
                            : $pmu->name.' : '.(int) $pmu_conversion_rate.'x'.$mu->name;
*/            
        }

        // Warning: stored $quantity is ALLWAYS in "stock keeping unit"
        if (array_key_exists('quantity', $params)) 
        {
            if (array_key_exists('use_measure_unit_id', $params) && ( $params['use_measure_unit_id'] == 'measure_unit_id' ))
            {
                // FORCE measure unit to default line measure unit
                // See CustomerOrdersController@createSingleShippingSlip, section relative to back order creation
                // No need of calculations
                $quantity = $params['quantity'];

            } else {
                // "Input quantity" (from form, etc.) is expected in "line measure unit", i.e., in package_measure_unit,
                // when $document_line->package_measure_unit_id != $document_line->measure_unit_id
                $quantity = $params['quantity'] * $pmu_conversion_rate;
            }
        }
        else
            $quantity = $document_line->quantity;       // Already in "stock keeping unit"

        // Tax
        $tax = $product->tax;
        $taxing_address = $this->taxingaddress;
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $sales_equalization = array_key_exists('sales_equalization', $params) 
                            ? $params['sales_equalization'] 
                            : $document_line->sales_equalization;

        // Product Price
//        $price = $product->getPrice();
//        $unit_price = $price->getPrice();


/*      Not needed:

        // Calculate price per $customer_id now!
        $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer
        // What to do???

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();
*/
        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $document_line->price_is_tax_inc;

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'] / $pmu_conversion_rate, $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = \App\Price::create([$document_line->unit_customer_final_price, $document_line->unit_customer_final_price_tax_inc, $pricetaxPolicy], $currency);
        }

        // Discount
        $discount_percent = array_key_exists('discount_percent', $params) 
                            ? $params['discount_percent'] 
                            : $document_line->discount_percent;

        // Final Price
        $unit_final_price = clone $unit_customer_final_price;
        if ( $discount_percent ) 
            $unit_final_price->applyDiscountPercent( $discount_percent );

        // Sales Rep
        $sales_rep_id = array_key_exists('sales_rep_id', $params) 
                            ? $params['sales_rep_id'] 
                            : $document_line->sales_rep_id;
        
        $commission_percent = array_key_exists('sales_rep_id', $params) && array_key_exists('commission_percent', $params) 
                            ? $params['commission_percent'] 
                            : $document_line->commission_percent;

        // Misc
        $notes = array_key_exists('notes', $params) 
                            ? $params['notes'] 
                            : $document_line->notes;


        // Build OrderLine Object
        $data = [
 //           'line_sort_order' => $line_sort_order,
 //           'line_type' => $line_type,
 //           'product_id' => $product_id,
 //           'combination_id' => $combination_id,
 //           'reference' => $reference,
 //           'name' => $name,
            'quantity' => $quantity,
 //           'measure_unit_id' => $measure_unit_id,

            'prices_entered_with_tax' => $pricetaxPolicy,
    
//            'cost_price' => $cost_price,
//            'unit_price' => $unit_price,
//            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price->getPrice(),
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price->getPriceWithTax(),
            'unit_final_price' => $unit_final_price->getPrice(),
            'unit_final_price_tax_inc' => $unit_final_price->getPriceWithTax(), 
  //          'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

  //          'total_tax_incl' => $quantity * $unit_final_price->getPriceWithTax(),
  //          'total_tax_excl' => $quantity * $unit_final_price->getPrice(),

            'tax_percent' => $tax_percent,
            'commission_percent' => $commission_percent,
            'notes' => $notes,
    //        'locked' => 0,
    
    //        'customer_order_id',
    //        'tax_id' => $tax->id,
            'sales_rep_id' => $sales_rep_id,
        ];

        // More stuff

        if (array_key_exists('line_sort_order', $params)) 
            $data['line_sort_order'] = $params['line_sort_order'];
        
        if (array_key_exists('pmu_label', $params)) 
            $data['pmu_label'] = $params['pmu_label'];
        
        if (array_key_exists('extra_quantity_label', $params)) 
            $data['extra_quantity_label'] = $params['extra_quantity_label'];
        
        if (array_key_exists('name', $params)) 
            $data['name'] = $params['name'];
        

        if (array_key_exists('name', $params)) 
            $data['name'] = $params['name'];
        
        if (array_key_exists('sales_equalization', $params)) 
            $data['sales_equalization'] = $params['sales_equalization'];
        
        if (array_key_exists('measure_unit_id', $params)) 
            $data['measure_unit_id'] = $params['measure_unit_id'];
        
        if (array_key_exists('sales_rep_id', $params)) 
            $data['sales_rep_id'] = $params['sales_rep_id'];
        
        if (array_key_exists('commission_percent', $params)) 
            $data['commission_percent'] = $params['commission_percent'];


        // Finishing touches
        $document_line->update( $data );


        // Let's deal with taxes
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );

        $document_line->applyTaxRules( $rules );


        // Now, update Document Totals
        $this->makeTotals();


        // Good boy, bye then
        return $document_line;

    }


    /**
     * Add Service to ShippingSlip
     *
     *     'prices_entered_with_tax', 'unit_customer_final_price', 'discount_percent', 'line_sort_order', 'sales_equalization', 'sales_rep_id', 'commission_percent'
     */
    public function addServiceLine( $product_id = null, $combination_id = null, $quantity = 1.0, $params = [] )
    {
        // Do the Mambo!
        $line_type = array_key_exists('line_type', $params) 
                            ? $params['line_type'] 
                            : 'service';

        // Customer
        $customer = $this->customer;
        $salesrep = $customer->salesrep;
        
        // Currency
        $currency = $this->document_currency;

        // Product
        // Consider "not coded products" only

        $reference = array_key_exists('reference', $params) 
                            ? $params['reference'] 
                            : '';

        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $line_type;

        $cost_price = array_key_exists('cost_price', $params) 
                            ? $params['cost_price'] 
                            : 0.0;

        // Tax
        $tax_id = array_key_exists('tax_id', $params) 
                            ? $params['tax_id'] 
                            : \App\Configuration::get('DEF_TAX');
        $tax = \App\Tax::findOrFail($tax_id);
        $taxing_address = $this->taxingaddress;
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $sales_equalization = array_key_exists('sales_equalization', $params) 
                            ? $params['sales_equalization'] 
                            : 0;

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : \App\Configuration::get('PRICES_ENTERED_WITH_TAX');

        // Service Price
        $price = new \App\Price($params['unit_customer_final_price'], $pricetaxPolicy, $currency);
        $price->applyTaxPercent( $tax_percent );
//        if ( $price->currency->id != $currency->id ) {
//            $price = $price->convert( $currency );
//        }
        $unit_price = $price->getPrice();

        // Calculate price per $customer_id now!
        // $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );
        $customer_price = clone $price;

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer

        // $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'], $pricetaxPolicy, $currency );

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
                            : 0.0;



        // Misc
        $measure_unit_id = array_key_exists('measure_unit_id', $params) 
                            ? $params['measure_unit_id'] 
                            : \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS');

        $line_sort_order = array_key_exists('line_sort_order', $params) 
                            ? $params['line_sort_order'] 
                            : $this->getNextLineSortOrder();

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
        $product = new \App\Product(['tax_id' => $tax->id]);
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );

        $document_line->applyTaxRules( $rules );


        // Now, update Document Totals
        $this->makeTotals();


        // Good boy, bye then
        return $document_line;
    }

    /**
     * Add Product to Order
     *
     *     'prices_entered_with_tax', 'unit_customer_final_price', 'discount_percent', 'line_sort_order', 'sales_equalization', 'sales_rep_id', 'commission_percent'
     */
    public function updateServiceLine( $line_id, $params = [] )
    {
        // Do the Mambo!
        $lineClass = $this->getClassName().'Line';
        $relation = 'document';     // strtolower($this->getClass());

        $document_line = ( new $lineClass() )->where($this->getClassSnakeCase().'_id', $this->id)
                        ->with($relation)
                        ->with($relation.'.customer')
//                        ->with('product')
//                        ->with('combination')
                        ->findOrFail($line_id);


        // Customer
        $customer = $this->customer;
        $salesrep = $this->salesrep;
        
        // Currency
        $currency = $this->document_currency;


        $line_type = array_key_exists('line_type', $params) 
                            ? $params['line_type'] 
                            : $document_line->line_type;

        // Product
        // Consider "not coded products" only

        $reference = array_key_exists('reference', $params) 
                            ? $params['reference'] 
                            : $document_line->reference;

        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $document_line->name;

        $cost_price = array_key_exists('cost_price', $params) 
                            ? $params['cost_price'] 
                            : $document_line->cost_price;

        // Tax
        $tax_id = array_key_exists('tax_id', $params) 
                            ? $params['tax_id'] 
                            : $document_line->tax_id;
        $tax = \App\Tax::findOrFail($tax_id);
        $taxing_address = $this->taxingaddress;
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $sales_equalization = array_key_exists('sales_equalization', $params) 
                            ? $params['sales_equalization'] 
                            : $document_line->sales_equalization;

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $document_line->price_is_tax_inc;

        // Product Price
//        $price = $product->getPrice();
//        $unit_price = $price->getPrice();

        // Service Price
        $price = new \App\Price($params['unit_customer_final_price'], $pricetaxPolicy, $currency);
        $price->applyTaxPercent( $tax_percent );
//        if ( $price->currency->id != $currency->id ) {
//            $price = $price->convert( $currency );
//        }
        $unit_price = $price->getPrice();

        // Calculate price per $customer_id now!
        // $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );
        $customer_price = clone $price;

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer
        // What to do???

        // $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'], $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = clone $customer_price;
        }

        // Discount
        $discount_percent = array_key_exists('discount_percent', $params) 
                            ? $params['discount_percent'] 
                            : $document_line->discount_percent;

        // Final Price
        $unit_final_price = clone $unit_customer_final_price;
        if ( $discount_percent ) 
            $unit_final_price->applyDiscountPercent( $discount_percent );

        // Sales Rep
        $sales_rep_id = array_key_exists('sales_rep_id', $params) 
                            ? $params['sales_rep_id'] 
                            : $document_line->sales_rep_id;
        
        $commission_percent = array_key_exists('sales_rep_id', $params) && array_key_exists('commission_percent', $params) 
                            ? $params['commission_percent'] 
                            : $document_line->commission_percent;

        // Misc
        $notes = array_key_exists('notes', $params) 
                            ? $params['notes'] 
                            : $document_line->notes;


        // Build OrderLine Object
        $data = [
 //           'line_sort_order' => $line_sort_order,
            'line_type' => $line_type,
 //           'product_id' => $product_id,
 //           'combination_id' => $combination_id,
 //           'reference' => $reference,
 //           'name' => $name,
 //           'quantity' => $quantity,
 //           'measure_unit_id' => $measure_unit_id,

            'prices_entered_with_tax' => $pricetaxPolicy,
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price->getPrice(),
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price->getPriceWithTax(),
            'unit_final_price' => $unit_final_price->getPrice(),
            'unit_final_price_tax_inc' => $unit_final_price->getPriceWithTax(), 
  //          'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

  //          'total_tax_incl' => $quantity * $unit_final_price->getPriceWithTax(),
  //          'total_tax_excl' => $quantity * $unit_final_price->getPrice(),

            'tax_percent' => $tax_percent,
            'commission_percent' => $commission_percent,
            'notes' => $notes,
    //        'locked' => 0,
    
    //        'customer_order_id',
            'tax_id' => $tax->id,
            'sales_rep_id' => $sales_rep_id,
        ];

        // More stuff
        if (array_key_exists('quantity', $params)) 
            $data['quantity'] = $params['quantity'];
        

        if (array_key_exists('line_sort_order', $params)) 
            $data['line_sort_order'] = $params['line_sort_order'];
        
        if (array_key_exists('notes', $params)) 
            $data['notes'] = $params['notes'];
        

        if (array_key_exists('name', $params)) 
            $data['name'] = $params['name'];
        
        if (array_key_exists('sales_equalization', $params)) 
            $data['sales_equalization'] = $params['sales_equalization'];
        
        if (array_key_exists('measure_unit_id', $params)) 
            $data['measure_unit_id'] = $params['measure_unit_id'];
        
        if (array_key_exists('sales_rep_id', $params)) 
            $data['sales_rep_id'] = $params['sales_rep_id'];
        
        if (array_key_exists('commission_percent', $params)) 
            $data['commission_percent'] = $params['commission_percent'];


        // Finishing touches
        $document_line->update( $data );


        // Let's deal with taxes
        $product = new \App\Product(['tax_id' => $tax->id]);
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );

        $document_line->applyTaxRules( $rules );


        // Now, update Document Totals
        $this->makeTotals();


        // Good boy, bye then
        return $document_line;
    }


    /**
     * Add Comment to ShippingSlip
     *
     *     'prices_entered_with_tax', 'unit_customer_final_price', 'discount_percent', 'line_sort_order', 'sales_equalization', 'sales_rep_id', 'commission_percent'
     */
    public function addCommentLine( $product_id = null, $combination_id = null, $quantity = 1.0, $params = [] )
    {
        // Do the Mambo!
        $line_type = array_key_exists('line_type', $params) 
                            ? $params['line_type'] 
                            : 'comment';

        // Customer
        $customer = $this->customer;
        $salesrep = $customer->salesrep;
        
        // Currency
        $currency = $this->document_currency;

        // Product
        // Consider "not coded products" only

        $reference = array_key_exists('reference', $params) 
                            ? $params['reference'] 
                            : '';

        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $line_type;

        $cost_price = array_key_exists('cost_price', $params) 
                            ? $params['cost_price'] 
                            : 0.0;

        // Tax
        $tax_id = array_key_exists('tax_id', $params) 
                            ? $params['tax_id'] 
                            : \App\Configuration::get('DEF_TAX');
        $tax = \App\Tax::findOrFail($tax_id);
        $taxing_address = $this->taxingaddress;
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $sales_equalization = array_key_exists('sales_equalization', $params) 
                            ? $params['sales_equalization'] 
                            : 0;

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : \App\Configuration::get('PRICES_ENTERED_WITH_TAX');

        // Service Price
        $price = new \App\Price($params['unit_customer_final_price'], $pricetaxPolicy, $currency);
        $price->applyTaxPercent( $tax_percent );
//        if ( $price->currency->id != $currency->id ) {
//            $price = $price->convert( $currency );
//        }
        $unit_price = $price->getPrice();

        // Calculate price per $customer_id now!
        // $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );
        $customer_price = clone $price;

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer

        // $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'], $pricetaxPolicy, $currency );

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
                            : 0.0;



        // Misc
        $measure_unit_id = array_key_exists('measure_unit_id', $params) 
                            ? $params['measure_unit_id'] 
                            : \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS');

        $line_sort_order = array_key_exists('line_sort_order', $params) 
                            ? $params['line_sort_order'] 
                            : $this->getMaxLineSortOrder() + 10;

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


        // Good boy, bye then
        return $document_line;
    }

    /**
     * Add Product to Order
     *
     *     'prices_entered_with_tax', 'unit_customer_final_price', 'discount_percent', 'line_sort_order', 'sales_equalization', 'sales_rep_id', 'commission_percent'
     */
    public function updateCommentLine( $line_id, $params = [] )
    {
        // Do the Mambo!
        $lineClass = $this->getClassName().'Line';
        $relation = 'document';     // strtolower($this->getClass());

        $document_line = ( new $lineClass() )->where($this->getClassSnakeCase().'_id', $this->id)
                        ->with($relation)
                        ->with($relation.'.customer')
//                        ->with('product')
//                        ->with('combination')
                        ->findOrFail($line_id);


        // Customer
        $customer = $this->customer;
        $salesrep = $this->salesrep;
        
        // Currency
        $currency = $this->document_currency;


        $line_type = array_key_exists('line_type', $params) 
                            ? $params['line_type'] 
                            : $document_line->line_type;

        // Product
        // Consider "not coded products" only

        $reference = array_key_exists('reference', $params) 
                            ? $params['reference'] 
                            : $document_line->reference;

        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $document_line->name;

        $cost_price = array_key_exists('cost_price', $params) 
                            ? $params['cost_price'] 
                            : $document_line->cost_price;

        // Tax
        $tax_id = array_key_exists('tax_id', $params) 
                            ? $params['tax_id'] 
                            : $document_line->tax_id;
        $tax = \App\Tax::findOrFail($tax_id);
        $taxing_address = $this->taxingaddress;
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $sales_equalization = array_key_exists('sales_equalization', $params) 
                            ? $params['sales_equalization'] 
                            : $document_line->sales_equalization;

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $document_line->price_is_tax_inc;

        // Product Price
//        $price = $product->getPrice();
//        $unit_price = $price->getPrice();

        // Service Price
        $price = new \App\Price($params['unit_customer_final_price'], $pricetaxPolicy, $currency);
        $price->applyTaxPercent( $tax_percent );
//        if ( $price->currency->id != $currency->id ) {
//            $price = $price->convert( $currency );
//        }
        $unit_price = $price->getPrice();

        // Calculate price per $customer_id now!
        // $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );
        $customer_price = clone $price;

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer
        // What to do???

        // $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'], $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = clone $customer_price;
        }

        // Discount
        $discount_percent = array_key_exists('discount_percent', $params) 
                            ? $params['discount_percent'] 
                            : $document_line->discount_percent;

        // Final Price
        $unit_final_price = clone $unit_customer_final_price;
        if ( $discount_percent ) 
            $unit_final_price->applyDiscountPercent( $discount_percent );

        // Sales Rep
        $sales_rep_id = array_key_exists('sales_rep_id', $params) 
                            ? $params['sales_rep_id'] 
                            : $document_line->sales_rep_id;
        
        $commission_percent = array_key_exists('sales_rep_id', $params) && array_key_exists('commission_percent', $params) 
                            ? $params['commission_percent'] 
                            : $document_line->commission_percent;

        // Misc
        $notes = array_key_exists('notes', $params) 
                            ? $params['notes'] 
                            : $document_line->notes;


        // Build OrderLine Object
        $data = [
 //           'line_sort_order' => $line_sort_order,
            'line_type' => $line_type,
 //           'product_id' => $product_id,
 //           'combination_id' => $combination_id,
 //           'reference' => $reference,
 //           'name' => $name,
 //           'quantity' => $quantity,
 //           'measure_unit_id' => $measure_unit_id,

            'prices_entered_with_tax' => $pricetaxPolicy,
    
            'cost_price' => $cost_price,
            'unit_price' => $unit_price,
            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price->getPrice(),
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price->getPriceWithTax(),
            'unit_final_price' => $unit_final_price->getPrice(),
            'unit_final_price_tax_inc' => $unit_final_price->getPriceWithTax(), 
  //          'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

  //          'total_tax_incl' => $quantity * $unit_final_price->getPriceWithTax(),
  //          'total_tax_excl' => $quantity * $unit_final_price->getPrice(),

            'tax_percent' => $tax_percent,
            'commission_percent' => $commission_percent,
            'notes' => $notes,
    //        'locked' => 0,
    
    //        'customer_order_id',
            'tax_id' => $tax->id,
            'sales_rep_id' => $sales_rep_id,
        ];

        // More stuff
        if (array_key_exists('quantity', $params)) 
            $data['quantity'] = $params['quantity'];
        

        if (array_key_exists('line_sort_order', $params)) 
            $data['line_sort_order'] = $params['line_sort_order'];
        
        if (array_key_exists('notes', $params)) 
            $data['notes'] = $params['notes'];
        

        if (array_key_exists('name', $params)) 
            $data['name'] = $params['name'];
        
        if (array_key_exists('sales_equalization', $params)) 
            $data['sales_equalization'] = $params['sales_equalization'];
        
        if (array_key_exists('measure_unit_id', $params)) 
            $data['measure_unit_id'] = $params['measure_unit_id'];
        
        if (array_key_exists('sales_rep_id', $params)) 
            $data['sales_rep_id'] = $params['sales_rep_id'];
        
        if (array_key_exists('commission_percent', $params)) 
            $data['commission_percent'] = $params['commission_percent'];


        // Finishing touches
        $document_line->update( $data );


        // Good boy, bye then
        return $document_line;
    }
}