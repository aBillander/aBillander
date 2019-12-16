<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{

    protected $fillable = ['alias', 'name', 'driver_name', 'active', 'notes'];

    public static $rules = array(
        'alias'        => array('required', 'min:2', 'max:32'),
        'name'         => array('required', 'min:2', 'max:64'),
    	);
	
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function deliveryroutelines()
    {
        return $this->hasMany('App\DeliveryRouteLine', 'delivery_route_id')->orderBy('line_sort_order', 'asc');
    }
    
    // Alias
    public function lines()
    {
        return $this->deliveryroutelines();
    }

}
