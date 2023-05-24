<?php 

namespace App\Traits;

use App\Models\Configuration;
use App\Helpers\Calculator;

trait BillableProfitabilityTrait
{

    // Maximum revenue possible
    public function getTotalTargetRevenue()
    {
        $lines = $this->profitablelines;

        $total_revenue = $lines->sum(function ($line) {

                // Unit Price has NOT Ecotax included
                return $line->quantity * $line->unit_price;

            });

        return $total_revenue;
    }

    // Actual revenue
    public function getTotalRevenue()
    {
        $lines = $this->profitablelines;

        $total_revenue = $lines->sum(function ($line) {

                return $line->quantity * $line->profit_final_price;

            });

        return $total_revenue;
    }

    // Alias
    // Deprecated, but still present in views
    public function getTotalRevenueAttribute()
    {
        return $this->getTotalRevenue();
    }


    public function getDocumentTotalDiscountPercentAttribute()
    {
        return $this->document_discount_percent 
             + $this->document_ppd_percent
             - $this->document_discount_percent * $this->document_ppd_percent / 100.0;
    }

    public function getDocumentTotalDiscountLinesAttribute()
    {
        $lines = $this->lines->where('line_type', 'discount');

        $total_discount_lines = $lines->sum(function ($line) {

                return (-1.0) * $line->quantity * $line->unit_final_price;

            });

        return $total_discount_lines;
    }

    public function getDocumentDiscountAttribute()
    {
        $d = ( 1.0 - $this->document_discount_percent / 100.0 ) * ( 1.0 - $this->document_ppd_percent / 100.0 );

        $document_discount = ($d != 0.0) ?
                                $this->total_currency_tax_excl * (1.0/$d - 1.0) :
                                0.0;

        return $document_discount;
    }

    public function aux($p)
    {
        $p = $p/100.0;

        return $p/(1.0-$p);
    }


    public function getTotalRevenueWithDiscount()
    {
/*
        abi_r($this->getTotalRevenue());
        abi_r($this->document_total_discount_lines);
        abi_r($this->getTotalRevenue() - $this->document_total_discount_lines);
        abi_r($this->document_discount);
        abi_r($this->getTotalRevenue() - $this->document_total_discount_lines - $this->document_discount);
        die();
*/

        return ( $this->getTotalRevenue() - $this->document_total_discount_lines - $this->document_discount );
    }

    // Alias
    // Deprecated, but still present in views
    public function getTotalRevenueWithDiscountAttribute()
    {
        return $this->getTotalRevenueWithDiscount();
    }



    public function getTotalCostPrice()
    {
        $lines = $this->profitablelines;

        $total_cost_price = $lines->sum(function ($line) {

                return $line->quantity * $line->profit_cost;

            });

        return $total_cost_price;
    }

    // Alias
    // Deprecated, but still present in views
    public function getTotalCostPriceAttribute()
    {
        return $this->getTotalCostPrice();
    }



    public function marginPercent()
    {
        return Calculator::margin( $this->total_cost_price, 
                                   $this->total_revenue_with_discount, 
                                   $this->currency 
                               );
    }

    public function marginAmount()
    {
        return  $this->total_revenue_with_discount 
              - $this->total_cost_price;
    }



    public function getSalesRepCommission()
    {
        $lines = $this->lines->where('line_type', 'product');

        $total_commission = $lines->sum(function ($line) {

                return $line->getSalesRepCommission();

            });

        // abi_r($total_commission);die();

        return $total_commission;
    }

    public function getTotalCommissionPercentAttribute()
    {
        return ( $this->total_commission / $this->total_revenue ) * 100.0;
    }



    public function marginTwoPercent()
    {
        return Calculator::margin( $this->total_cost_price, 
                                   $this->total_revenue_with_discount - $this->getSalesRepCommission(), 
                                   $this->currency 
                               );
    }

    public function marginTwoAmount()
    {
        return  $this->total_revenue_with_discount 
              - $this->getSalesRepCommission()
              - $this->total_cost_price;
    }
}