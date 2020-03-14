<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrier extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
	protected $fillable = [ 'name', 'alias', 'active',
                            'tracking_url', 'transit_time', 
             ];

    public static $rules = array(
        'name'    => array('required', 'min:2', 'max:64'),
        'alias'    => array('required', 'min:2', 'max:32'),
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function shippingmethods()
    {
        return $this->hasMany('App\ShippingMethod');
    }
}