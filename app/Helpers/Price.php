<?php 

namespace App;

use App\Traits\ViewFormatterTrait;

use \App\Currency as Currency;

class Price {

    use ViewFormatterTrait;

    public $amount;
    public $price_is_tax_inc;
    public $tax_percent;
    public $currency;
//    public $currency_conversion_rate;

    public $price;
    public $price_tax_inc;

    public $price_list_id;
    public $price_list_line_id;
	
    public function __construct( $amount = 0, $amount_is_tax_inc = 0, Currency $currency = null, $currency_conversion_rate = null )
    {
        if ( $currency === null ) $currency = \App\Context::getContext()->currency;
        if ( $currency_conversion_rate === null ) $currency_conversion_rate = $currency->conversion_rate;

        $this->amount = $amount;
        $this->price_is_tax_inc = $amount_is_tax_inc;
        $this->tax_percent = null;
        $currency->conversion_rate = $currency_conversion_rate;
        $this->currency = $currency;
//        $this->currency_conversion_rate = $currency_conversion_rate;

        if ($amount_is_tax_inc) {
            $this->price = null;
            $this->price_tax_inc = $amount;
        } else {
            $this->price = $amount;
            $this->price_tax_inc = null;
        }

        $this->price_list_id      = 0;
        $this->price_list_line_id = 0;
    }

    // Another constructor. See: https://stackoverflow.com/questions/1699796/best-way-to-do-multiple-constructors-in-php
    /**
     * Static constructor / factory
     */
    public static function create( $price = [], Currency $currency = null, $currency_conversion_rate = null ) 
    {
        // $price = [ price, price_tax_inc ]

        if (count($price)!=2) return self::create( [0.0, 0.0], $currency, $currency_conversion_rate );

        $price = array_values( $price );
        sort( $price, SORT_NUMERIC );

        $priceObj = new self();

        if ( $currency === null ) $currency = \App\Context::getContext()->currency;
        if ( $currency_conversion_rate === null ) $currency_conversion_rate = $currency->conversion_rate;
        $price_is_tax_inc = \App\Configuration::get('PRICES_ENTERED_WITH_TAX');

        $priceObj->price = $price[0];
        $priceObj->price_tax_inc = $price[0] ? $price[1] : 0.0;

        $priceObj->amount = $price_is_tax_inc ? $price[1] : $price[0] ;
        $priceObj->price_is_tax_inc = $price_is_tax_inc;
        $priceObj->tax_percent = ($price[0] != 0.0) ? (($price[1]-$price[0])/$price[0])*100.0 : 0.0;
        $currency->conversion_rate = $currency_conversion_rate;
        $priceObj->currency = $currency;
//        $priceObj->currency_conversion_rate = $currency_conversion_rate;

        $priceObj->price_list_id      = 0;
        $priceObj->price_list_line_id = 0;

        return $priceObj;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function convert( Currency $currency = null, $currency_conversion_rate = null )
    {
        $currency_from = $this->currency;
//        $currency_from->conversion_rate = $this->currency_conversion_rate;

        if ( $currency === null ) $currency = \App\Context::getContext()->currency;
        $currency_to = $currency;
        
        if ( $currency_conversion_rate !== null ) $currency_to->conversion_rate = $currency_conversion_rate;

        if ( ($currency_from->id == $currency_to->id) && ($currency_from->conversion_rate == $currency_to->conversion_rate) ) return clone $this;

        $amount = Currency::convertPrice($this->amount, $currency_from, $currency_to);

        $priceObject = new Price( $amount, $this->price_is_tax_inc, $currency_to);

        // abi_r( $priceObject );

        if ($this->tax_percent !== null) $priceObject->applyTaxPercent( $this->tax_percent );
        
        // abi_r( $priceObject, true );

        $priceObject->price_list_id      = $this->price_list_id;
        $priceObject->price_list_line_id = $this->price_list_line_id;

        return $priceObject;
    }

    public function convertToBaseCurrency()
    {
        return $this->convert( \App\Context::getContext()->currency );
    }

    public function applyTaxPercent( $percent = null )
    {
        if ( $percent === null ) $percent = 0.0;

        if ($this->price_is_tax_inc>0) {
            $this->price = $this->price_tax_inc/(1.0+$percent/100.0);
        } else {
            $this->price_tax_inc = $this->price*(1.0+$percent/100.0);
        }

        $this->tax_percent = $percent;
    }

    public function applyTaxPercentToPrice( $percent = null )
    {
        if ( $percent === null ) $percent = 0.0;

        // $this->price_is_tax_inc = 0;

        $this->applyTaxPercent($percent);
    }

    public function applyDiscountPercent( $percent = null )
    {
        if ( $percent === null ) $percent = 0.0;

        if ($this->price) 
            $this->price = $this->price*(1.0-$percent/100.0);

        if ($this->price_tax_inc)
            $this->price_tax_inc = $this->price_tax_inc*(1.0-$percent/100.0);
    }
    
    public function getPrice()
    {
        if ($this->price_is_tax_inc) {
            if ( $this->tax_percent === null ) return null;
            else return $this->price;
        } else {
            return $this->price;
        }
    }
    
    public function getPriceWithTax()
    {
        if ($this->price_is_tax_inc) {
            return $this->price_tax_inc;
        } else {
            if ( $this->tax_percent === null ) return null;
            else return $this->price_tax_inc;
        }
    }
    
    public function getDiscount( \App\Price $priceObjBase )
    {
        //
    }
    
    public function getMargin( \App\Price $priceObjBase )
    {
        //
    }
    
    public function applyRounding( )
    {
        // 
        $net   = $this->price;
        $gross = $this->price_tax_inc;
        $tax   = $gross - $net;
        $tax_percent = (float) $this->as_percentable($this->tax_percent);

        if ( \App\Configuration::get('ROUND_PRICES_WITH_TAX') ) {
            $gross = (float) $this->as_priceable( $gross, $this->currency );
            if ($tax_percent != 0.0) 
                $tax = (float) $this->as_priceable( $gross/(1.0+1.0/($tax_percent/100.0)), $this->currency );
            else
                $tax = 0.0;
            $net   = $gross - $tax;
        } else {
            $net   = (float) $this->as_priceable( $net, $this->currency );
            $tax   = (float) $this->as_priceable( $net*($tax_percent/100.0), $this->currency );
            $gross = $net + $tax;
        }

        $this->price         = $net;
        $this->price_tax_inc = $gross;
    }
    
    public function applyRoundingWithoutTax( )
    {
        // 

        $this->price         = (float) $this->as_priceable( $this->price,         $this->currency );
        $this->price_tax_inc = (float) $this->as_priceable( $this->price_tax_inc, $this->currency );
    }
    
    public function applyRoundingOnlyTax( )
    {
        // $net is fixed!
        $net   = (float) $this->as_priceable( $this->price, $this->currency );
        $gross = $this->price_tax_inc;
        
        $tax_percent = (float) $this->as_percentable($this->tax_percent);

        if ( \App\Configuration::get('ROUND_PRICES_WITH_TAX') ) {
            $gross = (float) $this->as_priceable( $gross, $this->currency );
            $tax   = $gross - $net;
        } else {
            $tax   = (float) $this->as_priceable( $net*($tax_percent/100.0), $this->currency );
            $gross = $net + $tax;
        }

        $this->price         = $net;
        $this->price_tax_inc = $gross;
    }
	
}