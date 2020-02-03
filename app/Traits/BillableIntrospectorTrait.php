<?php 

namespace App\Traits;

use ReflectionClass;

trait BillableIntrospectorTrait
{
/*
    public static function boot()
    {
        parent::boot();

        self::$classname = ( new ReflectionClass($this) )->getShortName();
        self::$classname_full = ( new ReflectionClass($this) )->getName();
    }
*/    
    public function getClass()
    {
        // CustomerShippingSlipsController
        // CustomerShippingSlip
        static $classname;

        return $classname ?: $classname = ( new ReflectionClass($this) )->getShortName();
    }
    
    public function getClassName()
    {
        // App\Http\Controllers\CustomerShippingSlipsController
        // App\CustomerShippingSlip
        static $classname_full;

        return $classname_full ?: $classname_full = ( new ReflectionClass($this) )->getName();
    }
    
    public function getClassSnakeCase()
    {
        // customer_shipping_slips_controller
        // customer_shipping_slip
        return snake_case($this->getClass());
    }
    
    public function getClassLastSegment()
    {
        // Controller
        // Slip
        static $segment;

        if ($segment) return $segment;

        // http://otroblogmas.com/parsear-strings-formato-camelcase-php/
        $str = snake_case($this->getClass());

        $segments = array_reverse(explode('_', $str));

        return $segment = studly_case($segments[0]);
    }
    
    public function getClassFirstSegment()
    {
        // Cusromer
        // Supplier
        static $segment;

        if ($segment) return $segment;

        // http://otroblogmas.com/parsear-strings-formato-camelcase-php/
        $str = snake_case($this->getClass());

        $segments = explode('_', $str);

        return $segment = studly_case($segments[0]);
    }

    public function getParentClass()
    {
        // CustomerShippingSlips
        // CustomerShipping
        static $classname;

        return $classname ?: $classname = substr( $this->getClass(), 0, -strlen($this->getClassLastSegment()) );
    }
    
    public function getParentClassName()
    {
        // App\Http\Controllers\CustomerShippingSlips
        // App\CustomerShipping
        static $classname_full;

        return $classname_full ?: $classname_full = substr( $this->getClassName(), 0, -strlen($this->getClassLastSegment()) );
    }
    
    public function getParentClassSnakeCase()
    {
        // customer_shipping_slips
        // customer_shipping
        return snake_case($this->getParentClass());
    }
    
    public function getParentClassLowerCase()
    {
        // customershippingslips
        // customershipping
        return strtolower($this->getParentClass());
    }
    
    public function getParentModelSnakeCase()
    {
        // customer_shipping_slip
        // customer_shipping
        return snake_case(str_singular($this->getParentClass()));
    }
    
    public function getParentModelLowerCase()
    {
        // customershippingslip
        // customershipping
        return strtolower(str_singular($this->getParentClass()));
    }
}