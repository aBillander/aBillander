<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends \Eloquent {

	protected $fillable = [ 'name', ];

    public static $rules = array(
    	'name'     => array('required', 'min:2', 'max:64'),
    	);
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function products()
    {
        return $this->hasMany('App\Product')->orderby('name', 'asc');
    }
}