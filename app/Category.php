<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    
    protected $fillable = [ 'name', 'position', 'publish_to_web', 'webshop_id', 
                            'is_root', 'active', 'parent_id'
                          ];

    public static $rules = array(
        'main_data' => array(
    	                   'name'      => array('required', 'min:2',  'max:128'), 
                    ),
        'internet' => array(
                            
                    ),
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function children() {

        return $this->hasMany('App\Category','parent_id','id') ;

    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
	
}