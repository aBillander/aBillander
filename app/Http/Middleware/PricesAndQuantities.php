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
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if ( !is_string($value) )
        	return $value;

        if (!$this->stub_in_array($key, $this->check_with_stub)) {
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
        	# code...
        	if ( strpos($key, $value) !== false )
        		return true;
        }

        return false;
    }
}
