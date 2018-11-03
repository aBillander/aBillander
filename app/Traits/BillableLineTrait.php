<?php 

namespace App\Traits;

use App\Traits\BillableIntrospectorTrait;

use App\Configuration;

use ReflectionClass;

trait BillableLineTrait
{
    use BillableIntrospectorTrait;

/*    
    public function getParentClass()
    {
        static $classname;

        return $classname ?: $classname = rtrim( ( new ReflectionClass($this) )->getShortName(), 'Line' );
    }
    
    public function getParentClassName()
    {
        static $classname_full;

        return $classname_full ?: $classname_full = rtrim( ( new ReflectionClass($this) )->getName(), 'Line' );
    }
    
    public function getParentClassSnakeCase()
    {
        return snake_case($this->getParentClass());
    }
*/
    public function getCurrencyAttribute()
    {
        $currency = $this->document->currency;
        $currency->conversion_rate = $this->document->currency_conversion_rate;

        return $currency;
    }

    public function document()
    {
        return $this->belongsTo($this->getParentClassName(), $this->getParentClassSnakeCase().'_id');
    }

    public function linetaxes()
    {
//        return $this->hasMany('App\CustomerOrderLineTax', 'customer_order_line_id');

        return $this->hasMany( $this->getClassName().'Tax', $this->getClassSnakeCase().'_id' );
//                    ->orderBy('line_sort_order', 'ASC');
    }

    // Alias
    public function taxes()
    {
        return $this->linetaxes();
    }

}