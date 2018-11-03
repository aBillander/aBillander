<?php 

namespace App\Traits;

use App\Traits\BillableIntrospectorTrait;
use App\Traits\BillableCustomTrait;

use App\Configuration;

use ReflectionClass;

trait BillableTrait
{
    use BillableIntrospectorTrait;
    use BillableCustomTrait;

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

        if ( method_exists($this, $method) ) {
            //
            return $this->$method();
        } else {
            //
            return $this->documenttotals_rounding_none();
        }
    }
    
    // Alias
    public function totals( $document_rounding_method = null )
    {
        return $this->documenttotals( $document_rounding_method );
    }


    private function documenttotals_rounding_total()
    {
        $currency = $this->currency;
        $currency->conversion_rate = $this->conversion_rate;

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
                        $tl->customer_order_line_id = 0;

                        $key = $linetax->tax_rule_id;
                        $tax_lines = $tax_lines->reject(function ($value, $key) use ($key) {
                                            return $value->tax_rule_id == $key;
                                        })
//                                  ->put($item['tax_id'], $tl);
                                  ->push($tl);
                    } else {
                        //
                        $tax_lines->push($tl);
                    }
                }

                $item['tax_lines'] = $tax_lines;
                
                $key = $document_line->tax_id;
                $totals = $totals->reject(function ($value, $key) use ($key) {
                                    return $value['tax_id'] == $key;
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
                            $item1->taxable_base   =  $currency->round($item1->taxable_base  );
                            $item1->total_line_tax =  $currency->round($item1->total_line_tax);

                            return $item1;
                        }),
                    ];

            $new['tax_amount'] = $new['tax_lines']->sum('total_line_tax');

            return $new;
        });


        // abi_r($totals_rounded);die();
/* * /
        // Output
        foreach ($totals_rounded as $total) {
            # code...
            abi_r($total['tax_id'].' - '.$total['tax_name'].' - '.$total['net_amount'].' - '.$total['tax_amount']);

            foreach ($total['tax_lines'] as $value) {
                # code...
                abi_r(' ^---'.$value->name.' - '.$value->taxable_base.' - '.$value->total_line_tax.' - '.'');
            }
        }
/ * */
        
        // die();


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

                $item['net_amount'] += $currency->round($document_line->total_tax_excl);

                $tax_lines = $item['tax_lines'];

                foreach ($document_line->linetaxes as $linetax) {
                    # code...
                    $tl = $linetax;
                    if ( ( $tls = $tax_lines->where('tax_rule_id', $linetax->tax_rule_id) )->count() > 0 )
                    {
                        //
                        $tl = $tls->first();

                        $tl->taxable_base += $currency->round($linetax->taxable_base);
                        $tl->total_line_tax += $currency->round($linetax->total_line_tax);
                        $tl->customer_order_line_id = 0;

                        $key = $linetax->tax_rule_id;
                        $tax_lines = $tax_lines->reject(function ($value, $key) use ($key) {
                                            return $value->tax_rule_id == $key;
                                        })
//                                  ->put($item['tax_id'], $tl);
                                  ->push($tl);
                    } else {
                        //
                        $tax_lines->push($tl);
                    }
                }

                $item['tax_lines'] = $tax_lines;
                
                $key = $document_line->tax_id;
                $totals = $totals->reject(function ($value, $key) use ($key) {
                                    return $value['tax_id'] == $key;
                                })
                          ->push($item);

            } else {
                $totals->push([
                                        'tax_id' => $document_line->tax_id,
                                        'tax_name' => $document_line->tax->name,
                                        'net_amount' => $document_line->total_tax_excl,
                                        'tax_lines' => $document_line->linetaxes->map(function ($item1, $key1) use ($currency) {
                                            //
                                            $item1->taxable_base   =  $currency->round($item1->taxable_base  );
                                            $item1->total_line_tax =  $currency->round($item1->total_line_tax);

                                            return $item1;
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

                        $key = $linetax->tax_rule_id;
                        $tax_lines = $tax_lines->reject(function ($value, $key) use ($key) {
                                            return $value->tax_rule_id == $key;
                                        })
//                                  ->put($item['tax_id'], $tl);
                                  ->push($tl);
                    } else {
                        //
                        $tax_lines->push($tl);
                    }
                }

                $item['tax_lines'] = $tax_lines;
                
                $key = $document_line->tax_id;
                $totals = $totals->reject(function ($value, $key) use ($key) {
                                    return $value['tax_id'] == $key;
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

}