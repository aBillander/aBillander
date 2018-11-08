<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
	
    protected $fillable = [ 'name', 'iso_code', 'iso_code_num', 
                            'sign', 'signPlacement',  'blank',
                            'thousandsSeparator', 'decimalSeparator', 'decimalPlaces', 
                            'conversion_rate', 'active'
                          ];

    public static $rules = array(
    	'name'    => array('required', 'min:2', 'max:32'),
    	'iso_code' => array('required'),
    	'iso_code_num' => array('required', 'min:1'),
        'decimalSeparator' => array('required'),
        'decimalPlaces' => array('required', 'min:0'),
    	'conversion_rate' => array('required', 'min:0'),
    	);

    public function getFormatAttribute()
    {
        $format = '';
        $decimalSeparator = $this->decimalPlaces > 0 ?
                            $this->decimalSeparator : '';

        $format = 'XX'.$this->thousandsSeparator.'XXX'.$decimalSeparator.str_repeat('X',$this->decimalPlaces);

        $blank = $this->blank ? ' ' : '';
        if ( $this->signPlacement > 0 )
        	$format = $format . $blank . $this->sign;
        else
        	$format = $this->sign . $blank . $format;

        return $format;
    }

    public function getSignPrintableAttribute()
    {
        if ($this->iso_code == 'EUR') return "&euro;";

        return $this->sign;
    }
    
    /**
     * Find ISO Code
     * 
     */
    public static function findByIsoCode($code)
    {
        return self::where('iso_code', $code)->first();
    }

    /**
    * Return price with currency sign & currency decimal places
    *
    * @param float $amount Product price
    * @param object $currency Current currency ( NULL => context currency)
    * @return string Price correctly formated (sign, decimal separator...)
    * if you modify this function, don't forget to modify the Javascript function formatCurrency (in tools.js)
    */
    public static function viewMoneyWithSign($amount, Currency $currency = null)
    {
        if (!is_numeric($amount))
            return $amount;

        if ($currency === null)
            $currency = \App\Context::getContext()->currency;

        $number = number_format($amount, $currency->decimalPlaces, $currency->decimalSeparator, $currency->thousandsSeparator);

        $blank = $currency->blank ? ' ' : '';
        if ( $currency->signPlacement > 0 )
            $number = $number . $blank . $currency->sign;
        else
            $number = $currency->sign . $blank . $number;
        
        // NOTE: negative amounts may require additional formatting for negative sign: -100 / 100- / (100)

        return $number;
    }

    /**
    * Return price with currency decimal places
    *
    * @param float $amount Product price
    * @param object $currency Current currency ( NULL => context currency)
    * @return string Price correctly formated (sign, decimal separator...)
    * if you modify this function, don't forget to modify the Javascript function formatCurrency (in tools.js)
    */
    public static function viewMoney($amount, Currency $currency = null)
    {
        if (!is_numeric($amount))
            return $amount;

        if ($currency === null)
            $currency = \App\Context::getContext()->currency;

        $number = number_format($amount, $currency->decimalPlaces, $currency->decimalSeparator, $currency->thousandsSeparator);

        return $number;
    }

    /**
     *
     * Convert amount from a currency to an other currency automatically
     * @param float $amount
     * @param Currency $currency_from if null we used the default currency
     * @param Currency $currency_to if null we used the default currency
     */
    public static function convertAmount($amount, Currency $currency_from = null, Currency $currency_to = null)
    {
        $currency_default = Currency::find( intval(Configuration::get('DEF_CURRENCY')) );

        if ($currency_from === null) $currency_from = $currency_default;
        if ($currency_to   === null) $currency_to   = $currency_default;

        if ($currency_from == $currency_to) return $amount;


        if ($currency_from->id == Configuration::get('DEF_CURRENCY')) {
            $amount *= $currency_to->conversion_rate;
        } else {
            // Convert amount to default currency
            $amount = $amount / $currency_from->conversion_rate;
            // Convert to new currency
            $amount *= $currency_to->conversion_rate;
        }

        return $amount;
    }

    /**
     *
     * Convert amount from default currency to an other currency automatically
     * @param float $amount
     * @param Currency $currency_to if null we used the default currency
     */
    public static function convertPriceTo($amount, Currency $currency_to = null)
    {
        return self::convertPrice($amount, null, $currency_to);
    }

    /**
     *
     * Convert amount from a currency to default currency automatically
     * @param float $amount
     * @param Currency $currency_from if null we used the default currency
     */
    public static function convertPriceFrom($amount, Currency $currency_from = null)
    {
        return self::convertPrice($amount, $currency_from, null);
    }

    /**
     * Alias function (discourage usage, since name is missleading!!!)
     */
    
    public static function convertPrice($amount, Currency $currency_from = null, Currency $currency_to = null)
    {
        return self::convertAmount($amount, $currency_from, $currency_to);
    }

    
    public function round($amount)
    {
        if (!is_numeric($amount))
            return $amount;

        return round($amount, $this->decimalPlaces);
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function currencyconversionrates()
    {
        return $this->hasMany('App\CurrencyConversionRate');
    }
    
    public function customerinvoices()
    {
        return $this->hasMany('App\CustomerInvoice');
    }
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder');
    }
}