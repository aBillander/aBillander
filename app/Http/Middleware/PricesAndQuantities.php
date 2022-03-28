<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

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
        'dispatch',
    ];

    /**
     * Clean the data in the given array.
     *
     * @param  array  $data
     * @param  string  $keyPrefix
     * @return array
     */
    protected function cleanArray(array $data, $keyPrefix = '')
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->cleanValue($keyPrefix.$key, $value);
        }

        // abi_r('Array');abi_r($data);

        return collect($data)->all();
    }

    /**
     * Clean the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function cleanValue($key, $value)
    {
        if (is_array($value)) {
            return $this->cleanArray($value, $key.'.');
        }

        return $this->transform($key, $value);
    }

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        // abi_r('Data');abi_r("$key, $value");

        if ( !is_string($value) )
        	return $value;

        if ( !$this->stub_in_array($key,  $this->check_with_stub) ) 
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
