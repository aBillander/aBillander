<?php 

namespace App\Traits;

use App\Models\Configuration;
use App\Helpers\Calculator;

trait BillableLineProfitabilityTrait
{
    // Get Cost Price for Profit calculations
    public function getProfitCostAttribute()
    {
        if ( Configuration::get('MARGIN_PRICE') == 'STANDARD' )
            return $this->cost_price;

        if ( Configuration::get('MARGIN_PRICE') == 'AVERAGE' )
            return $this->cost_average;

        // Sensible default
        return $this->cost_price;
    }

    // Get Final Price for Profit calculations
    public function getProfitFinalPriceAttribute()
    {
        if ( Configuration::isTrue('ENABLE_ECOTAXES') )
            return $this->unit_final_price - $this->ecotax_amount;

        // Otherwise
        return $this->unit_final_price;
    }

    // Tells if this line is taken into account for Document profit calculations
    public function getIsProfitableAttribute()
    {
        if ($this->line_type == 'product')
            return true;

        if ( Configuration::isTrue('INCLUDE_SERVICE_LINES_IN_PROFIT') && ($this->line_type == 'service') )
            return true;

        if ( Configuration::isTrue('INCLUDE_SHIPPING_COST_IN_PROFIT') && ($this->line_type == 'shipping') )
            return true;

        return false;
    }


    public function marginPercent()
    {
        return Calculator::margin( $this->profit_cost, 
                                   $this->profit_final_price, 
                                   $this->currency 
                               );
    }

    public function marginAmount()
    {
        return (  $this->profit_final_price 
                - $this->profit_cost
            ) * $this->quantity;
    }


    public function marginTwoPercent()
    {
        return Calculator::margin( $this->profit_cost, 
                                   $this->profit_final_price - $this->getSalesRepCommission() / $this->quantity, 
                                   $this->currency 
                               );
    }

    public function marginTwoAmount()
    {
        return (  $this->profit_final_price 
                - $this->profit_cost
               )*$this->quantity
               - $this->getSalesRepCommission();
    }



}