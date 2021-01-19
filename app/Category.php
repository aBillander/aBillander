<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

class Category extends Model {
    
    protected $fillable = [ 'name', 'description', 'position', 'publish_to_web', 'webshop_id', 'reference_external', 
                            'is_root', 'active', 'parent_id'
                          ];

    public static $rules = array(
        'main_data' => array(
    	                   'name'      => array('required', 'min:2',  'max:128'), 
                    ),
        'internet' => array(
                            
                    ),
    	);
    

    public static function boot()
    {
        parent::boot();

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($category)
        {
            // before delete() method call this


            // Check Children
            if ( $category->children->count() > 0 )
            {
                throw new \Exception( l('Category has Sub-Categories', 'exceptions') );
            }

            // Check Products
            if ( $category->products->count() > 0 )
            {
                throw new \Exception( l('Category has Products', 'exceptions') );
            }

            // Check Price Rules
            if ( PriceRule::where('category_id', $category->id)->get()->count() > 0 )
            {
                throw new \Exception( l('Category has Price Rules', 'exceptions') );
            }

        });

    }
    



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getcategoryList()
    {
            return Category::selectorList();
    }
    
    static function selectorList()
    {            
        if ( Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
            $tree = [];
            $categories =  Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
            
            foreach($categories as $category) {
                $label = $category->name;

                // Prevent duplicate names
                while ( array_key_exists($label, $tree))
                    $label .= ' ';

                $tree[$label] = $category->children()->orderby('position', 'asc')->pluck('name', 'id')->toArray();
                // foreach($category->children as $child) {
                    // $tree[$category->name][$child->id] = $child->name;
                // }
            }
            // abi_r($tree, true);
            return $tree;

        } else {
            // abi_r(\App\Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray(), true);
            return Category::where('parent_id', '=', '0')->orderby('position', 'asc')->pluck('name', 'id')->toArray();
        }

    }

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

        return $this->hasMany('App\Category','parent_id','id')->orderBy('position', 'asc')->orderBy('name', 'asc');

    }
    
    public function activechildren() {

        return $this->hasMany('App\Category','parent_id','id')->where('active', '>', 0)->IsPublished()->orderBy('position', 'asc')->orderBy('name', 'asc');    // ->IsActive() ;

    }

    public function products()
    {
        return $this->hasMany('App\Product')->orderBy('position', 'asc')->orderBy('name', 'asc');
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