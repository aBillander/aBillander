<?php 

namespace App\Traits;

use App\Configuration;

trait BillableEcotaxableTrait
{
    


    public function xxdocumenttotals( $document_rounding_method = null )
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
    public function xxtotals( $document_rounding_method = null )
    {
        return $this->documenttotals( $document_rounding_method );
    }
    
    // Handy 4 debuggin'
    public function ecotaxesonscreen( $document_rounding_method = null )
    {
        // $totals = $this->documenttotals( $document_rounding_method );
        $totals = $this->documentecotaxes( $document_rounding_method );

/* */
        // Output
        foreach ($totals as $total) {
            # code...
            abi_r($total['ecotax_id'].' - '.$total['ecotax_amount'].' - '.$total['ecotax_name'].' - '.$total['net_amount']);
        }
/* */
        
    }


    public function documentecotaxes( $document_rounding_method = null )
    {
        if ( $document_rounding_method === null )
            $document_rounding_method = Configuration::get('DOCUMENT_ROUNDING_METHOD');
        
        // To Do: Only "total" and "line" allowed
        // $net   = $currency->round( $net );

        $currency = $this->document_currency;
//        $currency->conversion_rate = $this->conversion_rate;
        //

        $document_lines      = $this->documentlines->where('line_type', 'product');
        // $line_taxes = $this->customerorderlinetaxes;
        
        // Let' get dirty!
        $totals = collect([]);

        foreach ($document_lines as $document_line) {
            # code...
            // $document_rounding_method = total
            if ( ( $items = $totals->where('ecotax_id', $document_line->ecotax_id) )->count() > 0 ) {
                $item = $items->first();

                $item['net_amount'] += $document_line->ecotax_total_amount;
                
                $tkey = $document_line->ecotax_id;
                $totals = $totals->reject(function ($value, $key) use ($tkey) {
                                    return $value['ecotax_id'] == $tkey;
                                })
                          ->push($item);
                // abi_r($totals);die();

            } else {
                $totals->push([
//                    $document_line->tax_id => [
                                        'ecotax_id' => $document_line->ecotax_id,
                                        'ecotax_name' => optional($document_line->ecotax)->name,
                                        'ecotax_amount' => $document_line->ecotax_amount,
                                        'net_amount' => $document_line->ecotax_total_amount,
//                                    ]
                    ]);
            }

        }

        // abi_r($totals);die();
        return $totals->sortByDesc('ecotax_amount');
    }






    private function xdocumenttotals_rounding_line()
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


    private function xdocumenttotals_rounding_none()
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

    
    public function xapplyDiscount()
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

            $new['net_amount'] = $new['gross_amount'] - $new['discount_amount'] - $new['ppd_amount'];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });

        return $totals_discounted;      // ->sortByDesc('net_amount');
    }

}