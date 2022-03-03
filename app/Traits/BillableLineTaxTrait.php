<?php 

namespace App\Traits;

use App\Models\Traits\BillableIntrospectorTrait;

use App\Models\Configuration;

use ReflectionClass;

trait BillableLineTaxTrait
{
    use BillableIntrospectorTrait;


    public function getCurrencyAttribute()
    {
        // abi_r($this);die();

        return $this->documentline->currency;
    }
    
    public function applyRounding( )
    {
        // 
        $currency = $this->currency;
        $line_tax = $this;

        if ($line_tax->amount != 0.0)
        {
            //
            $tax   = $line_tax->total_line_tax;
            $line_tax->total_line_tax = $currency->round( $tax );
        } else {
            //
            $net   = $line_tax->taxable_base;
            $tax   = $line_tax->total_line_tax;
            $gross = $net + $tax;
            // $tax_percent = (float) $this->as_percentable($line_tax->percent);
            $tax_percent = $line_tax->percent;

            if ( Configuration::isTrue('ROUND_PRICES_WITH_TAX') ) {
                $gross = $currency->round( $gross );
/*
                if ($tax_percent != 0.0) 
                    $tax = $currency->round( $gross/(1.0+1.0/($tax_percent/100.0)) );
                else
                    $tax = 0.0;
*/
    //            $net   = $gross - $tax;
                $net   = $currency->round( $net );
                $tax = $gross - $net;
            } else {
                $net   = $currency->round( $net );
    //            $tax   = (float) $this->as_priceable( $net*($tax_percent/100.0), $this->currency );
                // Simpler version (may produce 1 cent higher tax):
    //            $tax   = $currency->round( $gross - $net );
    //            $gross = $net + $tax;
                $gross = $currency->round( $net*(1.0+$tax_percent/100.0) );
                $tax = $gross - $net;
            }

            $line_tax->taxable_base   = $net;
            $line_tax->total_line_tax = $tax;
        }

        return $line_tax;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function documentline()
    {
        return $this->belongsTo($this->getParentClassName(), $this->getParentClassSnakeCase().'_id');
    }



    public function tax()
    {
       return $this->belongsTo('App\Tax', 'tax_id');
    }

    public function taxrule()
    {
       return $this->belongsTo('App\TaxRule', 'tax_rule_id');
    }

}