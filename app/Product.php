<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Validation\Rule;

use  \App\Configuration;

use App\Traits\ViewFormatterTrait;
use App\Traits\AutoSkuTrait;

use App\Traits\SearchableTrait;
// use App\Traits\FullTextSearchTrait;

class Product extends Model {

    use ViewFormatterTrait;
    use AutoSkuTrait;
    use SoftDeletes;

    use SearchableTrait;
//    use FullTextSearchTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.reference' => 10,
            'products.ean13' => 10,
 //           'products.description' => 10,
 //           'posts.title' => 2,
 //           'posts.body' => 1,
        ],
 //       'joins' => [
 //           'posts' => ['users.id','posts.user_id'],
 //       ],
    ];


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


    /**
     * The columns of the full text index
     * /
    protected $searchable = [
        'name', 
        'reference', 
        'ean13', 
        'description'
    ];
*/

    protected $dates = ['deleted_at'];

    protected $appends = ['quantity_available'];
    
    protected $fillable = [ 'product_type', 'procurement_type', 
                            'name', 'reference', 'ean13', 'description', 'description_short', 
                            'quantity_decimal_places', 'manufacturing_batch_size',
//                            'warranty_period', 

                            'reorder_point', 'maximum_stock', 'price', 'price_tax_inc', 'cost_price', 
                            'supplier_reference', 'supply_lead_time', 'manufacturer_id', 

                            'location', 'width', 'height', 'depth', 'weight', 

                            'notes', 'stock_control', 'publish_to_web', 'blocked', 'active', 
                            'out_of_stock', 'out_of_stock_text',

                            'tax_id', 'ecotax_id', 'category_id', 'main_supplier_id', 

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
                            'ean13'       => 'nullable|unique:products,ean13',
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
                            // todo: ean13 unique on update
                            'ean13' => 'nullable|unique:products,ean13', // ,'. $userId.',id',
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
            if ( Configuration::get('SKU_AUTOGENERATE') )
                if ( !$product->reference )
                    $product->autoSKU();
        });

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($product)
        {
            // before delete() method call this
            foreach($product->images as $line) {
                $line->deleteImage();
                $line->delete();
            }

            // Stock Movements
            foreach($product->stockmovements as $mvt) {
                $mvt->delete();
            }
        });

    }

    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getQuantityAllocatedAttribute()
    {
        // Allocated by Customer Orders
        // Document status = 'confirmed'
        $lines1 = \App\CustomerOrderLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q)
                            {
                                $q->where('status', 'confirmed');
                            }
                    )
                    ->get();

        $count1 = $lines1->sum('quantity');

        // Allocated by Customer Shipping Slips
        // Document status = 'confirmed'
        $lines2 = \App\CustomerShippingSlipLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q)
                            {
                                $q->where('status', 'confirmed');
                            }
                    )
                    ->get();

        $count2 = $lines2->sum('quantity');


        // Allocated by Customer Invoices
        // Document status = 'confirmed' && created_via = 'manual'
        $lines3 = \App\CustomerInvoiceLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q)
                            {
                                $q->where('status', 'confirmed');
                                $q->where('created_via', 'manual');
                            }
                    )
                    ->get();

        $count3 = $lines3->sum('quantity');

        


        $count = $count1 + $count2 + $count3;

        return $count;
    }

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
        $value = Configuration::get('PRICES_ENTERED_WITH_TAX') ?
                 $this->price_tax_inc :
                 $this->price ;

        return $this->as_priceable($value);
    }

    public function getStockBadgeAttribute()
    {
        $value = $this->quantity_onhand;

        if ( $value > Configuration::get('ABCC_STOCK_THRESHOLD') ) return 'success';

        if ( $value > 0.0 ) return 'warning';

        return 'danger';
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        if ( isset($params['term']) && trim($params['term']) !== '' )
        {
            $query->search( trim($params['term']) );
        }



        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            $query->orWhere('ean13', 'LIKE', '%' . trim($params['reference']) . '%');
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

        if ( isset($params['manufacturer_id']) && $params['manufacturer_id'] > 0 && 0)
        {
            $query->where('manufacturer_id', '=', $params['manufacturer_id']);
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

        $this->load(['warehouses']);

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
    
    public function getStock()
    { 
        $warehouses = \App\Warehouse::get();
        $count = 0;

        foreach ($warehouses as $warehouse) {
            # code...
            $count += $this->getStockByWarehouse( $warehouse->id );
        }

        return $count;
    }
    
    public function getStockNew()
    { 
        $warehouses = \App\Warehouse::get();
        $count = 0;

        foreach ($warehouses as $warehouse) {
            # code...
            $count += $this->getStockByWarehouse( $warehouse->id );
        }

        return $count;
    }
    

    public function getOutOfStock()
    { 
        if ( $this->out_of_stock == 'default' ) return Configuration::get('ABCC_OUT_OF_STOCK_PRODUCTS');

        return $this->out_of_stock;
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

    public static function getTypeName( $status )
    {
            return l($status, [], 'appmultilang');;
    }

            

    public static function getProcurementTypeList()
    {
            $list = [];
            foreach (self::$procurement_types as $type) {
                $list[$type] = l($type, [], 'appmultilang');;
            }

            return $list;
    }

    public static function getProcurementTypeName( $status )
    {
            return l($status, [], 'appmultilang');;
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

    public function ecotax()
    {
        return $this->belongsTo('App\Ecotax');
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

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'main_supplier_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo('App\Manufacturer', 'manufacturer_id');
    }
    
    public function warehouses()
    {
        return $this->belongsToMany('App\Warehouse')    //->as('warehouseline')
        ->withPivot('quantity')->withTimestamps();
    }

    public function warehouselines()
    {
        return $this->hasMany('App\WarehouseProductLine');
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
    public static function searchByNameAutocomplete($query, $onhand_only = 0)
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
                    ->orWhere('reference', 'like', '%' . $query . '%')
                    ->take( intval( Configuration::get('DEF_ITEMS_PERAJAX') ) )
                    ->orderBy('name');

        if ($onhand_only) $q = $q->where('quantity_onhand', '>', '0');

         $products = $q->take( Configuration::getInt('DEF_ITEMS_PERAJAX') )->get();


         return json_encode( $products );
//         return json_encode( array('query' => $query, 'suggestions' => $products) );
    }
	

    

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

    public function getPriceWithEcotax()
    {
        $price = [ $this->price, $this->price_tax_inc ];        // These prices are in Company Currency

        $priceObject = \App\Price::create( $price, \App\Context::getContext()->company->currency );
        
        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = \App\Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $priceObject->add( $this->getEcotax() ); 
        }

        return $priceObject;
    }

    public function getPriceByList( \App\PriceList $list = null )
    {
        // Return \App\Price Object
        if ($list && ( $price = $list->getPrice( $this ) ))
        {
            // Apply taxes
            $tax_percent = $this->tax->percent;
            $price->applyTaxPercent( $tax_percent );
        } else {
            
            $price = $this->getPrice();
        }
        
        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = \App\Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }

    // Deprecated DO NOT USE
    public function getPriceByListWithEcotax( \App\PriceList $list = null )
    {
        // Return \App\Price Object
        $price = $this->getPriceByList( $list );

        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = \App\Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }

    public function getPriceByCustomer( \App\Customer $customer, $quantity = 1, \App\Currency $currency = null )
    {
        // Return \App\Price Object
        $price = $customer->getPrice( $this, $quantity, $currency );

        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = \App\Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }
    

    public function getEcotax()
    {
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax ) return $this->ecotax->amount;

        return 0.0;
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

    public function getTaxRulesByProduct( \App\Address $address = null )
    {
        // Taxes depending on Product itself, such as recycle tax
        return collect([]);
        
        // If no address, use default Company address
        if ( $address == null ) $address = \App\Context::getContext()->company->address;

        $rules = collect([]);

        // Sales Equalization
        if ( 1 && $this->ecotax ) {

            $ecotax = $this->ecotax;

            $country_id = $address->country_id;
            $state_id   = $address->state_id;

            $rules_eco = $ecotax->ecotaxrules()->where(function ($query) use ($country_id) {
                $query->where(  'country_id', '=', 0)
                      ->OrWhere('country_id', '=', $country_id);
            })
                                     ->where(function ($query) use ($state_id) {
                $query->where(  'state_id', '=', 0)
                      ->OrWhere('state_id', '=', $state_id);
            })
                                     ->where('rule_type', '=', 'ecotax')
                                     ->get();

            if ( $rules_eco->isNotEmpty() ) $rules = $rules->merge( $rules_eco );

        }

        return $rules;
    }

    public function getTaxRules( \App\Address $address = null, \App\Customer $customer = null )
    {
        $rules =         $this->getTaxRulesByAddress(  $address  )
                ->merge( $this->getTaxRulesByCustomer( $customer ) )
                ->merge( $this->getTaxRulesByProduct(  $address  ) );

        // Higher Tax first
        // return $rules->sortByDesc('percent');
        // Not needed: use rule 'position' for precedence

        return $rules->sortBy('position');
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

    public function scopeIsSaleable($query)
    {
        // Apply filters here
        if ( Configuration::isTrue('SELL_ONLY_MANUFACTURED') ) 
            return $query->where('procurement_type', 'manufacture');

        return $query;
    }

    public function scopeIsService($query)
    {
        return $query->where('procurement_type', 'none');
    }

    public function scopeIsActive($query)
    {
        return $query->where('active', '>', 0);
    }

    public function scopeIsNew($query, $apply = true)
    {
        if ( !$apply ) return $query;

        return $query->whereDate('created_at', '>=', \Carbon\Carbon::now()->subDays( Configuration::getInt('ABCC_NBR_DAYS_NEW_PRODUCT') ));
    }

    public function scopeManufacturer($query, $manufacturer_id)
    {
        if ( (int) $manufacturer_id > 0 ) return $query->where('manufacturer_id', '=', $manufacturer_id);

        return $query;
    }

    public function scopeQualifyForCustomer($query, $customer_id, $currency_id) 
    {
        // Filter Products by Customer
        if ( Configuration::get('PRODUCT_NOT_IN_PRICELIST') == 'block' ) 
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



    public function scopeIsAvailable($query) 
    {
        // Products with stock
        $query->where('quantity_onhand', '>', 0);

        $query->orWhere(function ($query) {
                if ( Configuration::get('ABCC_OUT_OF_STOCK_PRODUCTS') != 'hide' )
                {
                    $query->where(function ($query) {
                            $query->where('quantity_onhand', '<=', 0);
                            $query->where('out_of_stock', '=', 'default');
                    });
                }
        });

        // return $query;

        $query->orWhere(function ($query) {
                    $query->where(function ($query) {
                            $query->where('quantity_onhand', '<=', 0);
                            $query->where('out_of_stock', '<>', 'default');
                            $query->where('out_of_stock', '<>', 'hide');
                    });
        });

        return $query;
    }
}
