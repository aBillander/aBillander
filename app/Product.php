<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Validation\Rule;

use App\Traits\ViewFormatterTrait;
// use App\Traits\AutoSkuTrait;

class Product extends Model {

    use ViewFormatterTrait;
//    use AutoSkuTrait;
    use SoftDeletes;

    public static $types = array(
            'simple', 
            'virtual', 
            'combinable', 
            'grouped',
        );

    public static $procurement_types = array(
            'purchase', 
            'manufacture', 
            'none', 
            'assembly',
        );

    protected $dates = ['deleted_at'];

//    protected $appends = ['quantity_available'];
    
    protected $fillable = [ 'product_type', 'procurement_type', 
                            'name', 'reference', 'ean13', 'description', 'description_short', 
                            'quantity_decimal_places', 'manufacturing_batch_size', 

                            'location', 'width', 'height', 'depth', 'weight', 

                            'notes', 'blocked', 'active', 
                            'measure_unit_id', 'category_id', 

                            'work_center_id', 'route_notes',
                          ];

    public static $rules = array(
        'create' => array(
                            'name'         => 'required|min:2|max:128',
                            'product_type'     => 'required|in:simple,virtual,combinable,grouped',
                            'procurement_type' => 'required|in:purchase,manufacture,none,assembly',

 //                           'manufacturing_batch_size' => 'required|min:1',

                            'reference'       => 'required|min:2|max:32|unique:products,reference,{$id},id,deleted_at,NULL', 
                            // See: https://wisdmlabs.com/blog/laravel-soft-delete-unique-validations/
                            'measure_unit_id' => 'exists:measure_units,id',
 //                           'category_id'  => 'exists:categories,id',
                    ),
        'main_data' => array(
                            'name'        => 'required|min:2|max:128',
                            'reference'   => 'sometimes|required|min:2|max:32|unique:products,reference,',     // https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update
                            'tax_id'      => 'exists:taxes,id',
                            'category_id' => 'exists:categories,id',
                    ),
        'manufacturing' => array(
                            
                    ),
        'sales' => array(
                            'price'         => 'required|numeric|min:0',
                            'price_tax_inc' => 'required|numeric|min:0', 
                    ),
        'inventory' => array(
                            
                    ),
        'manufacturing' => array(
                            
                    ),
        'internet' => array(
                            
                    ),
        );

    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getQuantityAvailableAttribute()
    {
        //
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['stock']) )
        {
            if ( $params['stock'] == 0 )
                $query->where('quantity_onhand', '<=', 0);
            if ( $params['stock'] == 1 )
                $query->where('quantity_onhand', '>', 0);
        }

        if ( isset($params['category_id']) && $params['category_id'] > 0 )
        {
            $query->where('category_id', '=', $params['category_id']);
        }

        if ( isset($params['active']) )
        {
            if ( $params['active'] == 0 )
                $query->where('active', '=', 0);
            if ( $params['active'] == 1 )
                $query->where('active', '>', 0);
        }

        return $query;
    }
    

    public function getFeaturedImage()
    { 
        // If no featured image, return one, anyway
  //      return $this->images()->orderBy('is_featured', 'desc')->orderBy('position', 'asc')->first();
    }

    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l($type, [], 'appmultilang');;
            }

            return $list;
    }

            

    public static function getProcurementTypeList()
    {
            $list = [];
            foreach (self::$procurement_types as $type) {
                $list[$type] = l($type, [], 'appmultilang');;
            }

            return $list;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
	}
    
    public function bomitems()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\BOMItem', 'product_id');
    }
    
    public function boms()
    {
        return $this->hasManyThrough('App\ProductBOM', 'App\BOMItem', 'product_id', 'id', 'id', 'product_bom_id')->with('measureunit');
    }
    
    public function bomitem()
    {
        return $this->bomitems->first();
    }
    
    public function bom()
    {
        return $this->boms->first();
    }

    public function images()
    {
        // return $this->morphMany('App\Image', 'imageable');
    }
		
    public function category()
    {
//        return $this->belongsTo('App\Category');
	}


    /*
    |--------------------------------------------------------------------------
    | Data Provider
    |--------------------------------------------------------------------------
    */

    /**
     * Provides a json encoded array of matching product names
     * @param  string $query
     * @return json
     */
 /*   public static function searchByNameAutocomplete_dist($query, $onhand_only = 0)
    {
        $q = Product::select('*', 'products.id as product_id', 'taxes.id as tax_id', 
                                  'products.name as product_name', 'taxes.name as tax_name')
                    ->leftjoin('taxes','taxes.id','=','products.tax_id')
                    ->orderBy('products.name')
                    ->where('products.name', 'like', '%' . $query . '%');

        if ($onhand_only) $q = $q->where('products.quantity_onhand', '>', '0');

         $products = $q->get();

         return json_encode( array('query' => $query, 'suggestions' => $products) );
    }

    /**
     * Provides a json encoded array of matching product names
     * @param  string $query
     * @return json
     */
 /*   public static function searchByNameAutocomplete($query, $onhand_only = 0)
    {
        $columns = [ 'id', 'product_type', 'name', 'reference',
 //                   'measure_unit', 'quantity_decimal_places', 
                    'reorder_point', 'price', 'price_tax_inc',
                    'quantity_onhand', 'quantity_onorder', 'quantity_allocated', 
                    'blocked', 'active', 
 //                   'tax_id',
        ];

//       $q = Product::with('tax')
        $q = Product::select( $columns )
                    ->where('name', 'like', '%' . $query . '%')
                    ->take( intval( \App\Configuration::get('DEF_ITEMS_PERAJAX') ) )
                    ->orderBy('name');

        if ($onhand_only) $q = $q->where('quantity_onhand', '>', '0');

         $products = $q->get();

         return json_encode( array('query' => $query, 'suggestions' => $products) );
    }
*/	


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include finishe products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsManufactured($query)
    {
        return $query->where('procurement_type', 'manufacture');
    }

    public function scopeIsPurchased($query)
    {
        return $query->where('procurement_type', 'purchase');
    }
}