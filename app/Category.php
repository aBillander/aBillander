<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

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
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsActive($query)
    {
        return $query->where('active', '>', 0);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('publish_to_web', '>', 0);
    }

    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function parent() {

        return $this->belongsTo('App\Category','parent_id','id');

    }
    
    public function children() {

        return $this->hasMany('App\Category','parent_id','id') ;

    }
    
    public function activechildren() {

        return $this->hasMany('App\Category','parent_id','id')->where('active', '>', 0)->IsPublished();    // ->IsActive() ;

    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Used in catalog sidebar
     *
     * Should accept a customer_id or Customer object (for a wider usage)
     *
     * @return mixed
     */
    public function customerproducts($customer_id=null, $currency_id=null)
    {
        $customer_user = Auth::user();

        if ( !$customer_user ) return collect([]);

        return $this->hasMany('App\Product')
                    ->IsSaleable()  // Is for sale or not
                    ->IsAvailable() // Has stock
                    // This filter would "filter" products a customer is allowed
                    ->qualifyForCustomer( $customer_user->customer_id, $customer_user->customer->currency->id )
                                      ->IsActive()
                                      ->IsPublished()
                    ->get();
    }
	
}