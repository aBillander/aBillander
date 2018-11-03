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
        static $classname;

        return $classname ?: $classname = ( new ReflectionClass($this) )->getShortName();
    }
    
    public function getClassName()
    {
        static $classname_full;

        return $classname_full ?: $classname_full = ( new ReflectionClass($this) )->getName();
    }
    
    public function getClassSnakeCase()
    {
        return snake_case($this->getClass());
    }
    
    public function getClassLastSegment()
    { 
        static $segment;

        if ($segment) return $segment;

        // http://otroblogmas.com/parsear-strings-formato-camelcase-php/
        $str = snake_case($this->getClass());

        $segments = array_reverse(explode('_', $str));

        return $segment = studly_case($segments[0]);
    }

    public function getParentClass()
    {
        static $classname;

        return $classname ?: $classname = rtrim( ( new ReflectionClass($this) )->getShortName(), $this->getClassLastSegment() );
    }
    
    public function getParentClassName()
    {
        static $classname_full;

        return $classname_full ?: $classname_full = rtrim( ( new ReflectionClass($this) )->getName(), $this->getClassLastSegment() );
    }
    
    public function getParentClassSnakeCase()
    {
        return snake_case($this->getParentClass());
    }
}