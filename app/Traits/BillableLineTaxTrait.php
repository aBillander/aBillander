<?php 

namespace App\Traits;

use App\Traits\BillableIntrospectorTrait;

use App\Configuration;

use ReflectionClass;

trait BillableLineTaxTrait
{
    use BillableIntrospectorTrait;


    public function getCurrencyAttribute()
    {
        $currency = $this->document->currency;
        $currency->conversion_rate = $this->document->currency_conversion_rate;

        return $currency;
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