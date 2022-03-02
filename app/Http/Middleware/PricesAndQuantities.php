<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

// https://medium.com/@samolabams/transforming-laravel-request-data-using-middleware-bc95a07332f0

class PricesAndQuantities extends TransformsRequest
{
    /**
     * The names of the attributes that should be checked.
     *
     * @var array
     */
    protected $check_with_stub = [
        'price',
        'quantity',
        'amount',
    ];

    /**
     * Clean the data in the given array.
     *
     * @param  array  $data
     * @return array
     */
    protected function cleanArray(array $data, $name = null)
    {
        // abi_r($data);
        return collect($data)->map(function ($value, $key) use ($name) {
            return $this->cleanValue($key, $value, $name);
        })->all();
    }

    /**
     * Clean the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function cleanValue($key, $value, $name = null)
    {
        if (is_array($value)) {
            return $this->cleanArray($value, $key);     // Should pass the name of the array. Otherwise method transform cannot know "variable name" for $value
        }

        return $this->transform($key, $value, $name);
    }

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value, $name = null)
    {
        if ( !is_string($value) )
        	return $value;

        if ( !$this->stub_in_array($key,  $this->check_with_stub) && 
             !$this->stub_in_array($name, $this->check_with_stub)    ) 
        {
            return $value;
        }

        if ( strpos($value, '.') !== false )
        	$value = str_replace(',', '', $value);

        $new_value = $value;

        if ( strpos($value, ',') !== false )
        {
        	$parts = explode(',', $value.',0');	// Allways two parts minimum!
    		
    		$new_value = $parts[0].'.'.$parts[1];	// No others "dots" here
       	}

        return $new_value;
    }

    protected function stub_in_array($key, $values)
    {

        foreach ($values as $value) {
        // abi_r(($key.', '.$value));
        	# code...
        	if ( strpos($key, $value) !== false )
        		return true;
        }

        return false;
    }
}
