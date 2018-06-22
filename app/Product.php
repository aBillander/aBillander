<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Validation\Rule;

use App\Traits\ViewFormatterTrait;
use App\Traits\AutoSkuTrait;

class Product extends Model {

    use ViewFormatterTrait;
    use AutoSkuTrait;
    use SoftDeletes;


    public $sales_equalization = 0;         // Handy property not stored. Takes its value after Customer Order Line sales_equalization flag

    public static $types = array(
            'simple', 
            'virtual', 
            'combinable', 
            'grouped',
        );

    public static $procurement_types = array(
            'purchase', 
            'manufacture', 
            'assembly',
            'none', 
        );

    protected $dates = ['deleted_at'];

    protected $appends = ['quantity_available'];
    
    protected $fillable = [ 'product_type', 'procurement_type', 
                            'name', 'reference', 'ean13', 'description', 'description_short', 
                            'quantity_decimal_places', 'manufacturing_batch_size',
//                            'warranty_period', 

                            'reorder_point', 'maximum_stock', 'price', 'price_tax_inc', 'cost_price', 
                            'supplier_reference', 'supply_lead_time',  

                            'location', 'width', 'height', 'depth', 'weight', 

                            'notes', 'stock_control', 'publish_to_web', 'blocked', 'active', 

                            'tax_id', 'category_id', 'main_supplier_id', 

                            'measure_unit_id', 'work_center_id', 'route_notes',
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
                            'price'         => 'required|numeric|min:0',
                            'price_tax_inc' => 'required|numeric|min:0',
                            'cost_price'    => 'required|numeric|min:0',
                            'tax_id'       => 'exists:taxes,id',
                            'category_id'  => 'exists:categories,id',
                            'quantity_onhand' => 'nullable|numeric|min:0',
                            'warehouse_id' => 'required_with:quantity_onhand|exists:warehouses,id',
                    ),
        'main_data' => array(
                            'name'        => 'required|min:2|max:128',
//                            'reference'   => 'sometimes|required|min:2|max:32|unique:products,reference,',     // https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update
                            'measure_unit_id' => 'exists:measure_units,id',
                            'category_id' => 'exists:categories,id',
                    ),
        'purchases' => array(
                            
                    ),
        'manufacturing' => array(
                            
                    ),
        'sales' => array(
                            'price'         => 'required|numeric|min:0',
                            'price_tax_inc' => 'required|numeric|min:0', 
                    ),
        'inventory' => array(
                            
                    ),
        'internet' => array(
                            
                    ),
        );
    

    public static function boot()
    {
        parent::boot();

        static::created(function($product)
        {
            if ( \App\Configuration::get('SKU_AUTOGENERATE') )
                if ( !$product->reference )
                    $product->autoSKU();
        });
    }

    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getQuantityAvailableAttribute()
    {
        $value =      $this->quantity_onhand  
                    + $this->quantity_onorder 
                    - $this->quantity_allocated 
                    + $this->quantity_onorder_mfg 
                    - $this->quantity_allocated_mfg;

        return $value;
    }

    public function getDisplayPriceAttribute()
    {
        $value = \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ?
                 $this->price_tax_inc :
                 $this->price ;

        return $this->as_priceable($value);
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

            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
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

        if ( isset($params['procurement_type']) && $params['procurement_type'] != '' )
        {
            $query->where('procurement_type', '=', $params['procurement_type']);
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
    

    public function getStockByWarehouse( $warehouse )
    { 
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;

    //    $product = \App\Product::find($this->id);

        $whs = $this->warehouses;
        if ($whs->contains($wh_id)) {
            $wh = $this->warehouses()->get();
            $wh = $wh->find($wh_id);
            $quantity = $wh->pivot->quantity;
        } else {
            $quantity = 0;
        }

        return $quantity;
    }
    

    public function getFeaturedImage()
    { 
        // If no featured image, return one, anyway
        return $this->images()->orderBy('is_featured', 'desc')->orderBy('position', 'asc')->first();
    }

    public function setFeaturedImage( \App\Image $image )
    { 
        $featured = $image->id;

        $images = $this->images;

        $images->map(function ($item, $key) use ($featured) {
            if ($item->id == $featured) {
                if (!$item->is_featured) {
                    $item->is_featured = 1;
                    $item->save();
                }
            } else {
                if ($item->is_featured) {
                    $item->is_featured = 0;
                    $item->save();
                }

            }

            return $item;
        });


        return true;
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
        return $this->morphMany('App\Image', 'imageable');
    }

    public function tax()
    {
        return $this->belongsTo('App\Tax');
    }
		
    public function category()
    {
        return $this->belongsTo('App\Category');
	}
    
    public function combinations()
    {
        return $this->hasMany('App\Combination');
    }
    
    public function stockmovements()
    {
        return $this->hasMany('App\StockMovement');
    }
    
    public function warehouses()
    {
        return $this->belongsToMany('App\Warehouse')->withPivot('quantity')->withTimestamps();
    }

    public function pricelistlines()
    {
        return $this->hasMany('App\PriceListLine');
    }

    public function pricelists()
    {
        return $this->belongsToMany('App\PriceList', 'price_list_lines', 'product_id', 'price_list_id')->as('pricelistline')->withPivot('price')->withTimestamps();

//        return $this->belongsToMany('App\PriceList', 'price_list_product', 'product_id', 'price_list_id')->withPivot('price')->withTimestamps();
    }
    
/*    
    public function pricelist( $list_id = null )
    {
        if ( $list_id > 0 )
            return $this->belongsToMany('App\PriceList')->where('price_list_id', '=', $list_id)->withPivot('price')->withTimestamps();
    } 
    
    public function prices()
    {
        return $this->hasMany('App\Price');
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
    | Price calculations
    |--------------------------------------------------------------------------
    */

    public function getPrice()
    {
        $price = [ $this->price, $this->price_tax_inc ];        // These prices are in Company Currency

        $priceObject = \App\Price::create( $price, \App\Context::getContext()->company->currency );

        return $priceObject;
    }

    public function getPriceByList( \App\PriceList $list )
    {
        // Return \App\Price Object
        return $list->getPrice( $this );
    }

    public function getPriceByCustomer( \App\Customer $customer, \App\Currency $currency = null )
    {
        // Return \App\Price Object
        return $customer->getPrice( $this, $currency );
    }
    

    public function getTaxRulesByAddress( \App\Address $address = null )
    {
        // Taxes depending on location
        // If no address, use default Company address
        if ( $address == null ) $address = \App\Context::getContext()->company->address;

        return $address->getTaxRules( $this->tax );
    }

    public function getTaxRulesByCustomer( \App\Customer $customer = null )
    {
        // Taxes depending on Customer, no matter of location
        if ( $customer == null ) return collect([]);

        return $customer->getTaxRules( $this );
    }

    public function getTaxRulesByProduct()
    {
        // Taxes depending on Product itself, such as recycle tax
        return collect([]);
    }

    public function getTaxRules( \App\Address $address = null, \App\Customer $customer = null )
    {
        $rules =         $this->getTaxRulesByAddress(  $address )
                ->merge( $this->getTaxRulesByCustomer( $customer ) )
                ->merge( $this->getTaxRulesByProduct() );

        return $rules;
    }
    

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

    public function scopeQualifyForCustomer($query, $customer_id, $currency_id) 
    {
        // Filter Products by Customer
        if ( \App\Configuration::get('PRODUCT_NOT_IN_PRICELIST') == 'block' ) 
        {
            $customer = \App\Customer::with('customergroup')->findorfail($customer_id);

            if ( !($currency_id) ) 
                $currency_id = \App\Context::getContext()->currency->id;

            if ($customer->price_list_id)
            {
                $price_list_id = $customer->price_list_id;

                return $query->whereHas('pricelists', function($query) use ($price_list_id, $currency_id) {
                    $query->where('price_lists.id', $price_list_id)->where('price_lists.currency_id', $currency_id);
                });

//                return $query->with('pricelists')->where('price_lists.id', $customer->price_list_id);
            }

            if ($customer->customergroup && $customer->customergroup->price_list_id)
            {
                $price_list_id = $customer->customergroup->price_list_id;

                return $query->whereHas('pricelists', function($query) use ($price_list_id, $currency_id) {
                    $query->where('price_lists.id', $price_list_id)->where('price_lists.currency_id', $currency_id);
                });
            }

        }

        return $query;
    }

    public function scopeQualifyForPriceList($query, $price_list_id) 
    {
        // Filter Products by Customer
        return $query->whereDoesntHave('pricelists', function($query) use ($price_list_id) {
                $query->where('price_lists.id', '=', $price_list_id);
        });
    }
}