<?php 

namespace App\Traits;

use App\Models\Configuration;
use App\Models\Tax;

use App\Helpers\Price;

trait BillableCustomTrait
{
    
    public function makeTotals( $document_discount_percent = null, $document_rounding_method = null )
    {
        if ( ($document_discount_percent !== null) && ($document_discount_percent >= 0.0) )
            $this->document_discount_percent = $document_discount_percent;
        
        if ( $document_rounding_method === null )
            $document_rounding_method = Configuration::get('DOCUMENT_ROUNDING_METHOD');

        $currency = $this->document_currency;

        // Just to make sure...
        // https://blog.jgrossi.com/2018/querying-and-eager-loading-complex-relations-in-laravel/
        $this->load(['lines', 'taxes']);

        $lines      = $this->lines;
        $line_taxes = $this->taxes;

        // Calculate these: 
        //      $this->total_lines_tax_excl 
        //      $this->total_lines_tax_incl

        // Calculate base
        $this->total_lines_tax_excl = 0.0;

        switch ( $document_rounding_method ) {
            case 'line':
                # Round off lines and summarize
                $this->total_lines_tax_excl = $lines->sum( function ($line) use ($currency) {
                            return $line->as_price('total_tax_excl', $currency);
                        } );
                break;
            
            case 'total':
                # Summarize (by tax), round off and summarize

                $tax_classes = Tax::with('taxrules')->get();

                foreach ($tax_classes as $tx) 
                {
                    $lns = $lines->where('tax_id', $tx->id);

                    if ($lns->count()) 
                    {
                        $amount = $lns->sum('total_tax_excl');

                        $this->total_lines_tax_excl += $this->as_priceable( $amount, $currency );
                    } 
                }

                break;
            
            case 'custom':
                # code...
                // break;
            
            case 'none':
            
            default:
                # Just summarize
                $this->total_lines_tax_excl = $lines->sum('total_tax_excl');
                break;
        }

        // Calculate taxes
        $this->total_lines_tax_incl = 0.0;

        switch ( $document_rounding_method ) {
            case 'line':
                # Round off lines and summarize

                // abi_r($line_taxes);die();

                $this->total_lines_tax_incl = $line_taxes->sum( function ($line) use ($currency) {
                            return $line->as_price('total_line_tax', $currency);
                        } );
                // abi_r($this->total_lines_tax_incl, true);

                break;
            
            case 'total':
                # Summarize (by tax), round off and summarize
                $tax_classes = Tax::with('taxrules')->get();

                foreach ($tax_classes as $tx) 
                {
                    $lns = $line_taxes->where('tax_id', $tx->id);

                    if ($lns->count()) 
                    {
                        foreach ($tx->taxrules as $rule) {

                            $line_rules = $lns->where('tax_rule_id', $rule->id);

                            if ($line_rules->count()) 
                            {
                                $amount = $line_rules->sum('total_line_tax');

                                $this->total_lines_tax_incl += $this->as_priceable($amount, $currency);
                            }
                        }
                    } 
                }

                break;
            
            case 'custom':
                # code...
                // break;
            
            case 'none':
            
            default:
                # Just summarize
                $this->total_lines_tax_incl = $lines->sum('total_tax_incl') - $this->total_lines_tax_excl;
                break;
        }

        $this->total_lines_tax_incl += $this->total_lines_tax_excl;

        // abi_r($this->total_lines_tax_incl, true);

        // These are NOT rounded!
//        $this->total_lines_tax_excl = $lines->sum('total_tax_excl');
//        $this->total_lines_tax_incl = $lines->sum('total_tax_incl');


// $document_rounding_method = 'line' :: Document lines are rounded, then added to totals






// $document_rounding_method = 'total' :: Document lines are NOT rounded. Totals are rounded
// abi_r($this->total_lines_tax_excl.' - '.$this->total_lines_tax_incl);die();



        if ($this->document_discount_percent>0 || $this->document_ppd_percent>0) 
        {
            $reduction =  (1.0 - $this->document_discount_percent/100.0) * (1.0 - $this->document_ppd_percent/100.0);

            $total_tax_incl = $this->total_lines_tax_incl * $reduction - $this->document_discount_amount_tax_incl - $this->document_ppd_amount_tax_incl;
            $total_tax_excl = $this->total_lines_tax_excl * $reduction - $this->document_discount_amount_tax_excl - $this->document_ppd_amount_tax_excl;

            // Make a Price object for rounding
            $p = Price::create([$total_tax_excl, $total_tax_incl], $currency);
            $p->applyRounding();        // But see: BillableTotalsTrait :: applyDiscount()

            // Improve this: Sum subtotals by tax type must match Order Totals
            // $p->applyRoundingWithoutTax( );

            $this->total_currency_tax_incl = $p->getPriceWithTax();
            $this->total_currency_tax_excl = $p->getPrice();

        } else {

            $this->total_currency_tax_incl = $this->total_lines_tax_incl;
            $this->total_currency_tax_excl = $this->total_lines_tax_excl;
            
        }


        // Not so fast, Sony Boy
        if ( $this->currency_conversion_rate != 1.0 ) 
        {

            // Make a Price object 
            $p = Price::create([$this->total_currency_tax_excl, $this->total_currency_tax_incl], $this->currency, $this->currency_conversion_rate);

            // abi_r($p);

            $p = $p->convertToBaseCurrency();

            // abi_r($p, true);

            // Improve this: Sum subtotals by tax type must match Order Totals
            // $p->applyRoundingWithoutTax( );

            $this->total_tax_incl = $p->getPriceWithTax();
            $this->total_tax_excl = $p->getPrice();

        } else {

            $this->total_tax_incl = $this->total_currency_tax_incl;
            $this->total_tax_excl = $this->total_currency_tax_excl;

        }


        // So far, so good
        $this->save();

        return true;
    }
  
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    private function documenttotals_rounding_custom()
    {
        return $this->documenttotals_rounding_none();
    }

}