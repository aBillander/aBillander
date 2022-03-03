<?php 

namespace App\Traits;

use App\Models\Configuration;
// use App\Models\CustomerOrderLineTax;

trait BillableTotalsTrait
{
    


    public function documenttotals( $document_rounding_method = null )
    {
        if ( $document_rounding_method === null )
            $document_rounding_method = Configuration::get('DOCUMENT_ROUNDING_METHOD');

        $discount_rounding_method = null;
        if ( $document_rounding_method == 'accounting' )
        {
            $discount_rounding_method = 'accounting';
            $document_rounding_method = 'none';
        }
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
        $this->totals = $this->applyDiscount( $discount_rounding_method );

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

        $document_lines      = $this->documentlines->where('line_type', '<>', 'comment');
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
                $tax = new CustomerOrderLineTax();
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

        $document_lines      = $this->documentlines->where('line_type', '<>', 'comment');
        
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

        $document_lines      = $this->documentlines->where('line_type', '<>', 'comment');
        
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

        // abi_r($totals);abi_r('*****************************');// die();

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

        // abi_r($totals_rounded);die();

        return $totals_rounded->sortByDesc('net_amount');
    }

    
    public function applyDiscount( $discount_rounding_method = null )
    {
        $currency = $this->document_currency;
        $totals   = $this->totals;

        $discount = $this->document_discount_percent;
        $ppd      = $this->document_ppd_percent;

        // if ($discount==0 && $ppd==0) return $totals;

        // Let' get (extra) dirty!
        // 
        $totals_discounted = collect([]);

        $totals_discounted = $totals->map(function ($item, $key) use ($currency, $discount, $ppd) {
            
            $gross_amount    = $item['net_amount'];
            $discount_amount = $item['net_amount'] * ($discount/100.0);
            $ppd_amount      = $item['net_amount'] * (1.0 - $discount/100.0) * ($ppd/100.0);


if( $discount_rounding_method = 'accounting')  // No rounding
{

            $new = [
                        'tax_id' => $item['tax_id'],
                        'tax_name' => $item['tax_name'],

                        'gross_amount'    => $gross_amount, 
                        'discount_amount' => $discount_amount,
                        'ppd_amount'      => $ppd_amount,

                        'net_amount' => 0.0,
                        'tax_amount' => 0.0,
                        'tax_lines' => $item['tax_lines']->map(function ($item1, $key1) use ($currency, $discount, $ppd) {
                            //
                            $item1->taxable_base   =  $item1->taxable_base   * (1.0 - $discount/100.0) * (1.0 - $ppd/100.0);
                            $item1->total_line_tax =  $item1->total_line_tax * (1.0 - $discount/100.0) * (1.0 - $ppd/100.0);

                            return $item1;
                        }),
                    ];

} else {

            $new = [
                        'tax_id' => $item['tax_id'],
                        'tax_name' => $item['tax_name'],

                        'gross_amount'    => $currency->round($gross_amount), 
                        'discount_amount' => $currency->round($discount_amount),
                        'ppd_amount'      => $currency->round($ppd_amount),

                        'net_amount' => 0.0,    // $currency->round($item['net_amount'] * (1.0 - $discount/100.0)),    // $currency->round($item['net_amount']),
                        'tax_amount' => 0.0,
                        'tax_lines' => $item['tax_lines']->map(function ($item1, $key1) use ($currency, $discount, $ppd) {
                            //
                            $item1->taxable_base   =  $currency->round( $item1->taxable_base   * (1.0 - $discount/100.0) * (1.0 - $ppd/100.0) ); // $currency->round($item1->taxable_base  );
                            $item1->total_line_tax =  $currency->round( $item1->total_line_tax * (1.0 - $discount/100.0) * (1.0 - $ppd/100.0) );   // $currency->round($item1->total_line_tax);

                            return $item1;
                        }),
                    ];

}

            $new['net_amount'] = $new['gross_amount'] - $new['discount_amount'] - $new['ppd_amount'];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });

        return $totals_discounted;      // ->sortByDesc('net_amount');
    }

}