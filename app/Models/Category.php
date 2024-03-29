<?php 

namespace App\Models;

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

    public static function getcategoryList( $onlySelectables = true )
    {
            // abi_r($onlySelectables, true);
            return Category::selectorList( $onlySelectables );
    }
    
    static function selectorList( $onlySelectables = true )
    {            
        if ( Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
            $tree = [];
            $categories =  Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
            
            foreach($categories as $category) {
                $children = $category->children()->orderby('position', 'asc')->pluck('name', 'id')->toArray();

                if ( $onlySelectables )
                {
                    // Only chidren are selectable
                    
                        $label = $category->name; // . ' ['.$category->id.'] ';

                        // Prevent duplicate names
                        while ( array_key_exists($label, $tree))
                            $label .= ' ';

                        $tree[$label] = $children;
                        // foreach($category->children as $child) {
                            // $tree[$category->name][$child->id] = $child->name;
                        // }
                    
                } else {
                    // All Categories are selectable

                    $tree[$category->id] = $category->name;

                    foreach ($children as $key => $child) {
                        // code...
                        $tree[$key] = '&nbsp; &nbsp; &#9492;&#9472; ' . $child;
                    }

                    // http://www.webusable.com/CharsExtendedTable.htm
                }
            }

            // abi_r($tree, true);
            return $tree;

        } else {
            // abi_r(Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray(), true);
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

        return $this->belongsTo(Category::class,'parent_id','id');

    }
    
    public function children() {

        return $this->hasMany(Category::class,'parent_id','id')->orderBy('position', 'asc')->orderBy('name', 'asc');

    }
    
    public function activechildren() {

        return $this->hasMany(Category::class,'parent_id','id')->where('active', '>', 0)->IsPublished()->orderBy('position', 'asc')->orderBy('name', 'asc');    // ->IsActive() ;

    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('position', 'asc')->orderBy('name', 'asc');
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

        return $this->hasMany(Product::class)
                    ->IsSaleable()  // Is for sale or not
                    ->IsAvailable() // Has stock
                    // This filter would "filter" products a customer is allowed
                    ->qualifyForCustomer( $customer_user->customer_id, $customer_user->customer->currency->id )
                                      ->IsActive()
                                      ->IsPublished()
                    ->get();
    }
	
}