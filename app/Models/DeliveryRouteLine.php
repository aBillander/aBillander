<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRouteLine extends Model
{
    
    protected $fillable = [ 
    						'line_sort_order', 
    						'delivery_route_id', 'customer_id', 'address_id', 
    						'active', 'notes',
    					];

    public static $rules = array(
    	'delivery_route_id' => 'exists:delivery_routes,id', 
    	'customer_id'       => 'exists:customers,id', 
    	'address_id'        => 'exists:addresses,id',
    	'line_sort_order'   => 'sometimes|nullable|numeric|min:0',
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function deliveryroute()
    {
        return $this->belongsTo(DeliveryRoute::class, 'delivery_route_id');
	}

    public function customer()
    {
        return $this->belongsTo(Customer::class);
	}

    public function address()
    {
        return $this->belongsTo(Address::class);
	}
}
