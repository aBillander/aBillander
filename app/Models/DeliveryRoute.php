<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{

    protected $fillable = ['alias', 'name', 'driver_name', 'active', 'notes', 'carrier_id'];

    public static $rules = array(
        'alias'        => array('required', 'min:2', 'max:32'),
        'name'         => array('required', 'min:2', 'max:64'),
        'carrier_id'   => 'required|exists:carriers,id',
    	);
	
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }
    
    public function deliveryroutelines()
    {
        return $this->hasMany(DeliveryRouteLine::class, 'delivery_route_id')->orderBy('line_sort_order', 'asc');
    }
    
    // Alias
    public function lines()
    {
        return $this->deliveryroutelines();
    }

}
