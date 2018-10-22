<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

class Category extends Model {
    
    protected $fillable = [ 'name', 'position', 'publish_to_web', 'webshop_id', 'reference_external', 
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

    public function customerproducts($customer_id=null, $currency_id=null)
    {
        $customer_user = Auth::user();

        return $this->hasMany('App\Product')->qualifyForCustomer( $customer_user->customer_id, $customer_user->customer->currency->id )->get();
    }
	
}