<?php 

namespace App\Traits;

use App\Traits\BillableIntrospectorTrait;
use App\Traits\BillableCustomTrait;

use App\Configuration;

// use ReflectionClass;

// Seems not used anywhere...

trait xBillableTrait
{
    use BillableIntrospectorTrait;
    use BillableCustomTrait;

    protected $totals = [];


    public function getDocumentCurrencyAttribute()
    {
        $currency = $this->currency;
        $currency->conversion_rate = $this->currency_conversion_rate;

        return $currency;
    }

    public function sequenceList()
    {
        return Sequence::listFor( $this->getClassName() );
    }

    

    public function documentlines()
    {

        // abi_r($this->getClassName().'Line'.' - '. $this->getClassSnakeCase().'_id');die();

        return $this->hasMany( $this->getClassName().'Line', $this->getClassSnakeCase().'_id' )
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function lines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->documentlines();
    }
    
    public function documentlinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasManyThrough($this->getClassName().'LineTax', $this->getClassName().'Line');
    }
    
    // Alias
    public function taxes()
    {
        return $this->documentlinetaxes();
    }


    public function documenttotals( $document_rounding_method = null )
    {
        if ( $document_rounding_method === null )
            $document_rounding_method = Configuration::get('DOCUMENT_ROUNDING_METHOD');

        // Just to make sure...
        $this->load('documentlines');

        $method = 'documenttotals_rounding_'.$document_rounding_method;

        if ( ! method_exists($this, $method) ) {
            //
            $document_rounding_method = 'none';
            $method = 'documenttotals_rounding_none';
        }

        $this->totals = $this->$method();

        // Apply document discount
        $this->totals = $this->applyDiscount();

        return $this->totals;
    }
    
    // Alias
    public function totals( $document_rounding_method = null )
    {
        return $this->documenttotals( $document_rounding_method );
    }
    
    // Handy 4 debuggin'
    public function totalsonscreen( $document_rounding_method = null )
    {
        $totals = $this->documenttotals( $document_rounding_method );

/* */
        // Output
        foreach ($totals as $total) {
            # code...
            abi_r($total['tax_id'].' - '.$total['tax_name'].' - '.$total['net_amount'].' - '.$total['tax_amount']);

            foreach ($total['tax_lines'] as $value) {
                # code...
                abi_r(' ^---'.$value->name.' - '.$value->taxable_base.' - '.$value->total_line_tax.' - '.'');
            }
        }
/* */
        
    }


    private function documenttotals_rounding_total()
    {
        $currency = $this->document_currency;
//        $currency->conversion_rate = $this->conversion_rate;

        $document_lines      = $this->documentlines;
        // $line_taxes = $this->customerorderlinetaxes;
        
        // Let' get dirty!
        $totals = collect([]);

        foreach ($document_lines as $document_line) {
            # code...
            // $document_rounding_method = total
            if ( ( $items = $totals->where('tax_id', $document_line->tax_id) )->count() > 0 ) {
                $item = $items->first();

                $item['net_amount'] += $document_line->total_tax_excl;

                $tax_lines = $item['tax_lines'];

                foreach ($document_line->linetaxes as $linetax) {
                    # code...
                    $tl = $linetax;
                    if ( ( $tls = $tax_lines->where('tax_rule_id', $linetax->tax_rule_id) )->count() > 0 )
                    {
                        //
                        $tl = $tls->first();

                        $tl->taxable_base += $linetax->taxable_base;
                        $tl->total_line_tax += $linetax->total_line_tax;
                        // $tl->customer_order_line_id = 0;

                        $tkey = $linetax->tax_rule_id;
                        $tax_lines = $tax_lines->reject(function ($value, $key) use ($tkey) {
                                            return $value->tax_rule_id == $tkey;
                                        })
//                                  ->put($item['tax_id'], $tl);
                                  ->push($tl);
                    } else {
                        //
                        $tax_lines->push($tl);
                    }
                }

                $item['tax_lines'] = $tax_lines;
                
                $tkey = $document_line->tax_id;
                $totals = $totals->reject(function ($value, $key) use ($tkey) {
                                    return $value['tax_id'] == $tkey;
                                })
                          ->push($item);
                // abi_r($totals);die();

            } else {
                $totals->push([
//                    $document_line->tax_id => [
                                        'tax_id' => $document_line->tax_id,
                                        'tax_name' => $document_line->tax->name,
                                        'net_amount' => $document_line->total_tax_excl,
                                        'tax_lines' => $document_line->linetaxes,
//                                    ]
                    ]);
            }

        }

        // abi_r($totals);die();

        // Let' get (even more) dirty!
        $totals_rounded = collect([]);

        $totals_rounded = $totals->map(function ($item, $key) use ($currency) {
            $new = [
                        'tax_id' => $item['tax_id'],
                        'tax_name' => $item['tax_name'],
                        'net_amount' => $currency->round($item['net_amount']),
                        'tax_amount' => 0.0,
                        'tax_lines' => $item['tax_lines']->map(function ($item1, $key1) use ($currency) {
                            //
                            // $item1->taxable_base   =  $currency->round($item1->taxable_base  );
                            // $item1->total_line_tax =  $currency->round($item1->total_line_tax);

                            return $item1->applyRounding();
                        }),
                    ];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });


        // abi_r($totals_rounded);die();


/*
        $tax_lines = $this->customerorderlinetaxes;


        foreach ($tax_lines as $line) {

// $document_rounding_method = 'line' :: Document lines are rounded, then added to totals

            if ( isset($totals[$line->tax_rule_id]) ) {
                $totals[$line->tax_rule_id]->taxable_base   += $line->taxable_base;
                $totals[$line->tax_rule_id]->total_line_tax += $line->total_line_tax;
            } else {
                $tax = new \App\CustomerOrderLineTax();
                $tax->taxable_base   = $line->taxable_base; 
                $tax->percent        = $line->percent;
                $line_tax->amount    = $rule->amount;
                $tax->total_line_tax = $line->total_line_tax;

                $totals[$line->tax_rule_id] = $tax;
            }

// $document_rounding_method = 'total' :: Document lines are NOT rounded. Totals are rounded

        }

        return collect($totals)->sortByDesc('percent')->values()->all();
*/
        return $totals_rounded->sortByDesc('net_amount');
    }


    private function documenttotals_rounding_line()
    {
        $currency = $this->document_currency;
//        $currency = $this->currency;
//        $currency->conversion_rate = $this->conversion_rate;

        $document_lines      = $this->documentlines;
        
        // Let' get dirty!
        $totals = collect([]);

        foreach ($document_lines as $document_line) {
            # code...
            //
            if ( ( $items = $totals->where('tax_id', $document_line->tax_id) )->count() > 0 ) {
                $item = $items->first();

                $item['net_amount'] += $currency->round($document_line->total_tax_excl);

                $tax_lines = $item['tax_lines'];

                foreach ($document_line->linetaxes as $linetax) {
                    # code...
                    $tl = $linetax->applyRounding();
                    if ( ( $tls = $tax_lines->where('tax_rule_id', $linetax->tax_rule_id) )->count() > 0 )
                    {
                        //
                        $tl1 = $tls->first();

                        $tl1->taxable_base += $tl->taxable_base;
                        $tl1->total_line_tax += $tl->total_line_tax;
                        // $tl1->customer_order_line_id = 0;

                        $tkey = $linetax->tax_rule_id;
                        $tax_lines = $tax_lines->reject(function ($value, $key) use ($tkey) {
                                            return $value->tax_rule_id == $tkey;
                                        })
//                                  ->put($item['tax_id'], $tl);
                                  ->push($tl1);
                    } else {
                        //
                        $tax_lines->push($tl);
                    }
                }

                $item['tax_lines'] = $tax_lines;
                
                $tkey = $document_line->tax_id;
                $totals = $totals->reject(function ($value, $key) use ($tkey) {
                                    return $value['tax_id'] == $tkey;
                                })
                          ->push($item);

            } else {
                $totals->push([
                                        'tax_id' => $document_line->tax_id,
                                        'tax_name' => $document_line->tax->name,
                                        'net_amount' => $currency->round($document_line->total_tax_excl),
                                        'tax_lines' => $document_line->linetaxes->map(function ($item1, $key1) use ($currency) {
                                            //
                                            // $item1->taxable_base   =  $currency->round($item1->taxable_base  );
                                            // $item1->total_line_tax =  $currency->round($item1->total_line_tax);

                                            return $item1->applyRounding();
                                        }),
                    ]);
            }

        }

        // Let' get (even more) dirty!
        // In fact, no rounding needed! (already done)
        $totals_rounded = collect([]);

        $totals_rounded = $totals->map(function ($item, $key) use ($currency) {
            $new = [
                        'tax_id' => $item['tax_id'],
                        'tax_name' => $item['tax_name'],
                        'net_amount' => $item['net_amount'],    // $currency->round($item['net_amount']),
                        'tax_amount' => 0.0,
                        'tax_lines' => $item['tax_lines']->map(function ($item1, $key1) use ($currency) {
                            //
                            $item1->taxable_base   =  $item1->taxable_base; // $currency->round($item1->taxable_base  );
                            $item1->total_line_tax =  $item1->total_line_tax;   // $currency->round($item1->total_line_tax);

                            return $item1;
                        }),
                    ];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });

        return $totals_rounded->sortByDesc('net_amount');
    }


    private function documenttotals_rounding_none()
    {
        $currency = $this->currency;
        $currency->conversion_rate = $this->conversion_rate;

        $document_lines      = $this->documentlines;
        
        // Let' get dirty!
        $totals = collect([]);

        foreach ($document_lines as $document_line) {
            # code...
            // $document_rounding_method = none
            if ( ( $items = $totals->where('tax_id', $document_line->tax_id) )->count() > 0 ) {
                $item = $items->first();

                $item['net_amount'] += $document_line->total_tax_excl;

                $tax_lines = $item['tax_lines'];

                foreach ($document_line->linetaxes as $linetax) {
                    # code...
                    $tl = $linetax;
                    if ( ( $tls = $tax_lines->where('tax_rule_id', $linetax->tax_rule_id) )->count() > 0 )
                    {
                        //
                        $tl = $tls->first();

                        $tl->taxable_base += $linetax->taxable_base;
                        $tl->total_line_tax += $linetax->total_line_tax;
                        $tl->customer_order_line_id = 0;

                        $tkey = $linetax->tax_rule_id;
                        $tax_lines = $tax_lines->reject(function ($value, $key) use ($tkey) {
                                            return $value->tax_rule_id == $tkey;
                                        })
//                                  ->put($item['tax_id'], $tl);
                                  ->push($tl);
                    } else {
                        //
                        $tax_lines->push($tl);
                    }
                }

                $item['tax_lines'] = $tax_lines;
                
                $tkey = $document_line->tax_id;
                $totals = $totals->reject(function ($value, $key) use ($tkey) {
                                    return $value['tax_id'] == $tkey;
                                })
                          ->push($item);

            } else {
                $totals->push([
                                        'tax_id' => $document_line->tax_id,
                                        'tax_name' => $document_line->tax->name,
                                        'net_amount' => $document_line->total_tax_excl,
                                        'tax_lines' => $document_line->linetaxes,
                    ]);
            }

        }

        // Let' get (even more) dirty!
        // In fact, no rounding needed!
        $totals_rounded = collect([]);

        $totals_rounded = $totals->map(function ($item, $key) use ($currency) {
            $new = [
                        'tax_id' => $item['tax_id'],
                        'tax_name' => $item['tax_name'],
                        'net_amount' => $item['net_amount'],    // $currency->round($item['net_amount']),
                        'tax_amount' => 0.0,
                        'tax_lines' => $item['tax_lines']->map(function ($item1, $key1) use ($currency) {
                            //
                            $item1->taxable_base   =  $item1->taxable_base; // $currency->round($item1->taxable_base  );
                            $item1->total_line_tax =  $item1->total_line_tax;   // $currency->round($item1->total_line_tax);

                            return $item1;
                        }),
                    ];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });

        return $totals_rounded->sortByDesc('net_amount');
    }

    
    public function applyDiscount()
    {
        $currency = $this->document_currency;
        $totals = $this->totals;

        if ($this->document_discount_percent==0) return $totals;

        $discount = $this->document_discount_percent;

        // Let' get (extra) dirty!
        // 
        $totals_discounted = collect([]);

        $totals_discounted = $totals->map(function ($item, $key) use ($currency, $discount) {
            $new = [
                        'tax_id' => $item['tax_id'],
                        'tax_name' => $item['tax_name'],
                        'net_amount' => $currency->round($item['net_amount'] * (1.0 - $discount/100.0)),    // $currency->round($item['net_amount']),
                        'tax_amount' => 0.0,
                        'tax_lines' => $item['tax_lines']->map(function ($item1, $key1) use ($currency, $discount) {
                            //
                            $item1->taxable_base   =  $currency->round( $item1->taxable_base * (1.0 - $discount/100.0) ); // $currency->round($item1->taxable_base  );
                            $item1->total_line_tax =  $currency->round( $item1->total_line_tax * (1.0 - $discount/100.0) );   // $currency->round($item1->total_line_tax);

                            return $item1;
                        }),
                    ];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });

        return $totals_discounted;      // ->sortByDesc('net_amount');
    }




    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Pump it up!
    |--------------------------------------------------------------------------
    */

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

        $cost_price = $product->cost_price;

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
        $customer_price = $product->getPriceByCustomer( $customer, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $customer_price->price_is_tax_inc;

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
                            : optional($salesrep)->getCommision( $product, $customer ) ?? 0.0;



        // Misc
        $measure_unit_id = array_key_exists('measure_unit_id', $params) 
                            ? $params['measure_unit_id'] 
                            : $product->measure_unit_id;

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


        // Let's deal with taxes
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );

        $document_line->applyTaxRules( $rules );


        // Now, update Order Totals
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
        $relation = strtolower($this->getClass());

        $document_line = ( new $lineClass() )->where($this->getClassSnakeCase().'_id', $this->id)
                        ->with($relation)
                        ->with($relation.'.customer')
                        ->with('product')
                        ->with('combination')
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

        // Calculate price per $customer_id now!
        $customer_price = $product->getPriceByCustomer( $customer, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer
        // What to do???

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $document_line->price_is_tax_inc;

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'], $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = \App\Price::create([$document_line->unit_final_price, $document_line->unit_final_price_tax_inc, $pricetaxPolicy], $currency);
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
 //           'quantity' => $quantity,
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
        $product->sales_equalization = $sales_equalization;
        $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );

        $document_line->applyTaxRules( $rules );


        // Now, update Order Totals
        $this->makeTotals();


        // Good boy, bye then
        return $document_line;

    }

}