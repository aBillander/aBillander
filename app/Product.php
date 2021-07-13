<?php 

namespace App;

use App\Scopes\ShowOnlyActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ProductPackableTrait;
use App\Traits\ModelAttachmentableTrait;

// use Illuminate\Validation\Rule;

use Auth;
use Carbon\Carbon;

use App\Configuration;
use App\MeasureUnit;

use App\Traits\ViewFormatterTrait;
use App\Traits\AutoSkuTrait;

use App\Traits\StockableTrait;

use App\Traits\SearchableTrait;
// use App\Traits\FullTextSearchTrait;

class Product extends Model {

    use ViewFormatterTrait;
    use AutoSkuTrait;
    use SoftDeletes;
    
    use ProductPackableTrait;
    use ModelAttachmentableTrait;

    use StockableTrait;

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

    public static $mrp_types = array(
            'manual',  //  => manualy place manufacture or purchase orders
            'onorder',  //  => manufactured or purchased on order
            'reorder',  //  => Reorder Point Planning
            // 'forecast' => Forecast Based Planning
            // 'phased'   => Time-phased Planning (planning cycles)
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

    protected $dates = ['deleted_at', 'available_for_sale_date', 'new_since_date'];

    protected $appends = ['extra_measureunits', 'tool_id', 'quantity_available'];
    
    protected $fillable = [ 'product_type', 'procurement_type', 'mrp_type', 
                            'name', 'position', 'reference', 'ean13', 'description', 'description_short', 
                            'quantity_decimal_places', 'manufacturing_batch_size',
//                            'warranty_period', 

                            'reorder_point', 'maximum_stock', 'price', 'price_tax_inc', 'cost_price', 
                            'recommended_retail_price', 'recommended_retail_price_tax_inc', 

                            'supplier_reference', 'supply_lead_time', 'manufacturer_id', 

                            'location', 'width', 'height', 'depth', 'volume', 'weight',

                            'notes', 'stock_control', 'publish_to_web', 'webshop_id', 'blocked', 'active', 
                            'out_of_stock', 'out_of_stock_text', 'available_for_sale_date', 'new_since_date', 

                            'tax_id', 'ecotax_id', 'category_id', 'main_supplier_id', 'purchase_measure_unit_id', 

                            'lot_tracking', 'expiry_time', 'lot_number_generator', 'lot_policy', 

                            'measure_unit_id', 'work_center_id', 'route_notes', 'machine_capacity', 'units_per_tray', 

                            'name_en', 'price_usd', 'price_usd_conversion_rate',
                          ];

    public static $rules = array(
        'create' => array(
                            'name'         => 'required|min:2|max:128',
                            'name_en'         => 'nullable|min:2|max:128',
                            'product_type'     => 'required|in:simple,virtual,combinable,grouped',
                            'procurement_type' => 'required|in:purchase,manufacture,none,assembly',

 //                           'manufacturing_batch_size' => 'required|min:1',

                            'reference'       => 'required|min:2|max:32|unique:products,reference,{$id},id,deleted_at,NULL', 
                            // See: https://wisdmlabs.com/blog/laravel-soft-delete-unique-validations/
                            'ean13'                        => 'nullable|unique:products,ean13,{$id},id,deleted_at,NULL',
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
                            'name_en'         => 'nullable|min:2|max:128',
                            'reference'   => 'sometimes|required|min:2|max:32|unique:products,reference',     // https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update
                            // Reference & ean13 rules for uniqueness are completed on ProductController update method
                            'ean13'                              => 'nullable|unique:products,ean13', // ,'. $userId.',id',
                            'measure_unit_id' => 'exists:measure_units,id',
                            'category_id' => 'exists:categories,id',
                    ),
        'purchases' => array(
                            
                    ),
        'manufacturing' => array(
                            'manufacturing_batch_size' => 'required|numeric|min:0|not_in:0',
                            
                    ),
        'sales' => array(
                            'price'         => 'required|numeric|min:0',
                            'price_tax_inc' => 'required|numeric|min:0', 

                            'recommended_retail_price'         => 'required|numeric|min:0',
                            'recommended_retail_price_tax_inc' => 'required|numeric|min:0', 
                            
                            'available_for_sale_date' => 'nullable|date',
                    ),
        'inventory' => array(
                            
                    ),
        'internet' => array(
                            
                    ),
        );
    

    public static function boot()
    {
        parent::boot();

        // See ProductsController::indexQueryRaw()
        static::addGlobalScope(new ShowOnlyActiveScope( Configuration::isTrue('SHOW_PRODUCTS_ACTIVE_ONLY') ));      // (new ManagerResolver());

        static::created(function($product)
        {
            if ( Configuration::get('SKU_AUTOGENERATE') )
                if ( !$product->reference )
                    $product->autoSKU();
        });


        static::deleting(function ($product)
        {
            // before delete() method call this
            $relations = [
                    'customerquotationlines',
                    'customerorderlines',
                    'customershippingsliplines',
                    'customerinvoicelines',
                    'warehouseshippingsliplines',
                    'lots',
                    'boms',
                    'productBOMlines',
            ];

            if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
                $relations = $relations + ['productionorders', 'productionorderlines'];

            // load relations
            $product->load( $relations );

            // Check Relations
            foreach ($relations as $relation) {
                # code...
                if ( $product->{$relation}->count() > 0 )
                    throw new \Exception( l('Product has :relation', ['relation' => $relation], 'exceptions') );
            }

            // To do: manage models: Supplier Price list line

        });

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        // cause a delete of a Customer to cascade to children so they are also deleted
        static::deleted(function ($product)
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
            
            // Attachments
            foreach($product->attachments as $line) {
                $line->delete();
            }

            $product->warehouselines()->delete();
            $product->pricelistlines()->delete();
            $product->stockcountlines()->delete();
            $product->cartlines()->delete();
            $product->pricerules()->delete();
            $product->productmeasureunits()->delete();
            $product->producttools()->delete();
            $product->customerordertemplatelines()->delete();

            $product->packitems()->delete();
        });

    }

    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    // Get Cost Price for Profit calculations
    public function getProfitCostAttribute()
    {
        if ( Configuration::get('MARGIN_PRICE') == 'STANDARD' )
            return $this->cost_price;

        if ( Configuration::get('MARGIN_PRICE') == 'AVERAGE' )
            return $this->cost_average;

        // Sensible default
        return $this->cost_price;
    }

    public function getBomAttribute()
    {
        // Easy get BOM
        return $this->certifiedboms()->first();
    }



    public function getChildToolQuantity( $product_id, $quantity = 1.0, $children = [] )
    {
        //
        $all_children = $this->getChildToolQuantityWithChildren( $product_id, $quantity, $children );

        return collect( $all_children )->sum('quantity');
    }

    public function getChildToolQuantityWithChildren( $tool_id, $quantity = 1.0, $children = [] )
    {
        if ( $this->tool_id == $tool_id )
        {
                $child = [
                            'child_product_id' => $this->id,
                            'quantity' => $quantity
                ];

                $children[] = $child;
        }

        $bom = $this->bom;

        // BOM lines
        if ( !$bom ) return $children;

        foreach( $bom->BOMlines as $line ) {
            
            $line_product = $line->product;
            $line_product_bom = $line_product->bom;

                $children = $line_product->getChildToolQuantityWithChildren( $tool_id, $quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0), $children );


            // if ( $line->product_id != $product_id ) continue;

            // $quantity += $child_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
        }

        return $children;
    }






    public function getChildProductQuantity( $product_id, $quantity = 1.0, $children = [] )
    {
        //
        $all_children = $this->getChildProductQuantityWithChildren( $product_id, $quantity, $children );

        return collect( $all_children )->sum('quantity');
    }

    public function getChildProductQuantityWithChildren( $product_id, $quantity = 1.0, $children = [] )
    {
        // $product_id = $data['product_id'];
        // $product = Product::findOrFail( $data['product_id'] );
        // if ( !array_key_exists('child_quantity', $data) )
        //     $data['child_quantity'] = 1.0;
        $bom = $this->bom;

        // BOM lines
        if ( !$bom ) return null;

        foreach( $bom->BOMlines as $line ) {
            
            $line_product = $line->product;
            $line_product_bom = $line_product->bom;

            if ( $line->product_id == $product_id )
            {
                $child = [
                            'child_product_id' => $this->id,
                            'quantity' => $quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0)
                ];

                $children[] = $child;
            }
            else

            if ( $line_product_bom )
            {
                $children = $line_product->getChildProductQuantityWithChildren( $product_id, $quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0), $children );
            }            

            // if ( $line->product_id != $product_id ) continue;

            // $quantity += $child_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
        }

        return $children;
    }



    public function getQuantityOnorderAttribute()
    {
        // On Order by Supplier Orders
        // Document status = 'confirmed'
        $lines1 = SupplierOrderLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q)
                            {
                                $q->where('status', 'confirmed');
                            }
                    )
                    ->get();

        $count1 = $lines1->sum('quantity');

        // On Order by Supplier Shipping Slips
        // Document status = 'confirmed'
        $lines2 = SupplierShippingSlipLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q)
                            {
                                $q->where('status', 'confirmed');
                            }
                    )
                    ->get();

        $count2 = $lines2->sum('quantity');


        // On Order by Supplier Invoices
        // Document status = 'confirmed' && created_via = 'manual'
        $lines3 = SupplierInvoiceLine::
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

    public function getAllocations( $min_date = null )
    {
        // Allocated by Customer Orders
        // Document status = 'confirmed'
        $lines1 = CustomerOrderLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q) use ($min_date)
                            {
                                $q->where('status', 'confirmed');

                                if ($min_date != null)
                                    $q->where('document_date', '>=', $min_date);
                            }
                    )
                    ->with('document')
                    ->with('document.customer')
                    ->get();


        // Allocated by Customer Shipping Slips
        // Document status = 'confirmed'
        $lines2 = CustomerShippingSlipLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q) use ($min_date)
                            {
                                $q->where('status', 'confirmed');

                                if ($min_date != null)
                                    $q->where('document_date', '>=', $min_date);
                            }
                    )
                    ->with('document')
                    ->with('document.customer')
                    ->get();

        $count2 = $lines2->sum('quantity');


        // Allocated by Customer Invoices
        // Document status = 'confirmed' && created_via = 'manual'
        $lines3 = CustomerInvoiceLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q) use ($min_date)
                            {
                                $q->where('status', 'confirmed');
                                $q->where('created_via', 'manual');

                                if ($min_date != null)
                                    $q->where('document_date', '>=', $min_date);
                            }
                    )
                    ->with('document')
                    ->with('document.customer')
                    ->get();

        $count3 = $lines3->sum('quantity');

        


        $lines = $lines1->merge($lines2)->merge($lines3);

        // Do some sorting here (by document_date, ASC):
        $lines = $lines->sortBy(function ($line, $key) {
            return $line->document->document_date;
        });

        return $lines;
    }

    public function getQuantityAllocatedAttribute()
    // To Do: refactor using getAllocations()
    {
        $date = Configuration::get('STOCKMOVEMENTS_AFTER_DATE');

        try {

            $min_date = $date ? 
                        \Carbon\Carbon::createFromFormat('Y-m-d', $date) :
                        null;
            
        } catch (\Exception $e) {

            $min_date = \Carbon\Carbon::now()->subDays(130);
            
        }    

        // Allocated by Customer Orders
        // Document status = 'confirmed'
        $lines1 = CustomerOrderLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q) use ($min_date)
                            {
                                $q->where('status', 'confirmed');

                                if ($min_date != null)
                                    $q->where('document_date', '>=', $min_date);
                            }
                    )
                    ->get();

        $count1 = $lines1->sum('quantity');

        // Allocated by Customer Shipping Slips
        // Document status = 'confirmed'
        $lines2 = CustomerShippingSlipLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q) use ($min_date)
                            {
                                $q->where('status', 'confirmed');

                                if ($min_date != null)
                                    $q->where('document_date', '>=', $min_date);
                            }
                    )
                    ->get();

        $count2 = $lines2->sum('quantity');


        // Allocated by Customer Invoices
        // Document status = 'confirmed' && created_via = 'manual'
        $lines3 = CustomerInvoiceLine::
                      where('product_id', $this->id)
                    ->whereHas('document', function($q) use ($min_date)
                            {
                                $q->where('status', 'confirmed');
                                $q->where('created_via', 'manual');

                                if ($min_date != null)
                                    $q->where('document_date', '>=', $min_date);
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

    public function getQuantityReorderSuggestedAttribute()
    {
        $value =      $this->maximum_stock 
                    - $this->quantity_available;

        return $value > 0 ? $value : 0.0;
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

    public function getCostPriceAttribute($value)
    {
//        $value = $this->cost_price;

        if( \Illuminate\Support\Facades\Auth::guard('salesrep')->check() ) return 0.0;

        return $value;
    }

    public function getCostAverageAttribute($value)
    {
//        $value = $this->cost_average;

        if( \Illuminate\Support\Facades\Auth::guard('salesrep')->check() ) return 0.0;

        return $value;
    }

    public function getParentCategoryIdAttribute()
    {

        return optional(optional($this->category)->parent)->id;
    }

    public function getIsPackagingAttribute()
    {

        return $this->category_id        == Configuration::getInt('PACKAGING_PRODUCTS_CATEGORY') ||
               $this->parent_category_id == Configuration::getInt('PACKAGING_PRODUCTS_CATEGORY');
    }

    // Alias
    public function getMeasureunitsAttribute()
    {

        return $this->measureunitsGet();
    }

    public function getExtraMeasureunitsAttribute()
    {

        return $this->extra_measureunitsGet();
    }

    // Alias
    public function getToolsAttribute()
    {

        return $this->toolsGet();
    }

    // Assumes only 1 Tool per product
    public function getToolAttribute()
    {

        return $this->tools->first();
    }

    public function getToolIdAttribute()
    {

        return optional($this->tools->first())->id;
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
            // $query->search( trim($params['term']) );     // Original

            $query->search( trim($params['term']), null, true, false );
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

            if ( Auth::user()->language->iso_code == 'en' )
            {
                $query->orWhere('name_en', 'LIKE', '%' . trim($params['name'] . '%'));
            }
        }

        if ( isset($params['stock']) )
        {
            if ( $params['stock'] == 0 )
                $query->where('quantity_onhand', '<=', 0);
            if ( $params['stock'] == 1 )
                $query->where('quantity_onhand', '>', 0);
        }

        if ( isset($params['stock_control']) )
        {
            if ( $params['stock_control'] == 0 )
                $query->where('stock_control', '<=', 0);
            if ( $params['stock_control'] == 1 )
                $query->where('stock_control', '>', 0);
        }

        if ( isset($params['main_supplier_id']) )
        {
            if ( $params['main_supplier_id'] > 0 )
                $query->where('main_supplier_id', $params['main_supplier_id']);
            if ( $params['main_supplier_id'] < 0 )
                $query->where('main_supplier_id', 0)->orWhere('main_supplier_id', null);
        }

        if ( isset($params['category_id']) && $params['category_id'] > 0 )
        {
            $query->where('category_id', '=', $params['category_id'])
                  ->orderBy('position', 'asc');
        }

        if ( isset($params['manufacturer_id']) && $params['manufacturer_id'] > 0 && 0)
        {
            $query->where('manufacturer_id', '=', $params['manufacturer_id']);
        }

        if ( isset($params['procurement_type']) && $params['procurement_type'] != '' )
        {
            $query->where('procurement_type', '=', $params['procurement_type']);
        }

        if ( isset($params['mrp_type']) && $params['mrp_type'] != '' )
        {
            $query->where('mrp_type', '=', $params['mrp_type']);
        }

        if ( isset($params['active']) )
        {
            if ( Configuration::isTrue('SHOW_PRODUCTS_ACTIVE_ONLY') )
            {
                if ( $params['active'] == 1 )
                {
                    // Show active products, same as global scope ShowOnlyActiveScope
                    // Do nothing
                    ;

                } else {
                    // Show not active products (0) or all (-1)
                    // Remove global scope
                    $query->withoutGlobalScope(ShowOnlyActiveScope::class);

                    // https://www.manifest.uk.com/blog/overriding-eloquent-global-scopes

                    // Show not active products (0)
                    if ( $params['active'] == 0 )
                        $query->where('active', '=', 0);
                }

            } else {

                if ( $params['active'] == 0 )
                    $query->where('active', '=', 0);
                
                if ( $params['active'] == 1 )
                    $query->where('active', '>', 0);
            }
        }

        return $query;
    }

/* Moved to StockableTrait 
    public function getStockByWarehouse( $warehouse_id = null  )
    {
        // By convention
        if ( $warehouse_id === null ) return $this->quantity_onhand;

        $warehouse = $this->warehouses->where('id', $warehouse_id)->first();

        if ( !$warehouse ) return 0.0;

        return $warehouse->pivot ? $warehouse->pivot->quantity : 0.0;
    }
*/    

    public function getLastStockTakingByWarehouse( $warehouse = null )
    {
        if ( $warehouse == null ) $warehouse = Configuration::getInt('DEF_WAREHOUSE');

        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;

        // Retrieve movements
        $mvt = StockMovement::
                      where('product_id', $this->id)
                    ->where('warehouse_id', $wh_id)
                    ->where( function($query){
                                // $now = Carbon::now()->startOfDay(); 
                                // 
                                $query->where(  'movement_type_id', StockMovement::INITIAL_STOCK);
                                $query->orWhere('movement_type_id', StockMovement::ADJUSTMENT);
                        } )
                    ->orderBy('date', 'desc')
                    ->first();


        $last_stock_taking_date = $mvt ? $mvt->date : '';

        return $mvt;
        return $last_stock_taking_date;
    }

    public function getStockToDateByWarehouse(  $warehouse = null, Carbon $date = null  )
    {
        if ( $warehouse == null ) $warehouse = Configuration::getInt('DEF_WAREHOUSE');
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;
        
        if ( $date      == null ) $date      = Carbon::now();   //->endOfDay();
        else                      $date      = $date->endOfDay();

        // Last movement BEFORE requested date
        $mvt = StockMovement::
                      where('product_id', $this->id)
                    ->where('warehouse_id', $wh_id)
                    ->where('date', '<=', $date)
                    ->orderBy('date', 'desc')           // Guess "well ordered" movements
                    ->orderBy('id', 'DESC')
                    ->first();

        if ($mvt)
            return $mvt->quantity_after_movement;

        return 0.0;
    }

    

    public function getPriceForStockValuation( $thePrice = null  )
    {
        if ( $thePrice !== null )
            return $thePrice;

        // Configuration::get('INVENTORY_VALUATION_METHOD') => STANDARD: AVERAGE: CURRENT: 

        switch ( Configuration::get('INVENTORY_VALUATION_METHOD') ) {
            case 'STANDARD':
                # code...
                return $this->cost_price;
                break;
            
            case 'AVERAGE':
                # code...
                return $this->cost_average;
                break;
            
            case 'CURRENT':
                # code...
                return $this->last_purchase_price;
                break;
            
            default:
                # code...
                break;
        }
    }

    

    public function getStockToDateFullByWarehouse(  $warehouse = null, Carbon $date = null  )
    {
        if ( $warehouse == null ) $warehouse = Configuration::getInt('DEF_WAREHOUSE');
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;
        
        if ( $date      == null ) $date      = Carbon::now();   //->endOfDay();
        else                      $date      = $date->endOfDay();

        // Last movement BEFORE requested date
        $mvt = StockMovement::
                      where('product_id', $this->id)
                    ->where('warehouse_id', $wh_id)
                    ->where('date', '<=', $date)
                    ->orderBy('date', 'desc')           // Guess "well ordered" movements
                    ->orderBy('id', 'DESC')
                    ->first();

        if ($mvt)
            return $mvt;

        return null;
    }



    // Just to disappear
    public function getStockToDateByWarehouse_old_stuff(  $warehouse = null, Carbon $date = null  )
    {
        if ( $warehouse == null ) $warehouse = Configuration::getInt('DEF_WAREHOUSE');
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;
        
        if ( $date      == null ) $date      = Carbon::now();   //->endOfDay();
        else                      $date      = $date->endOfDay();

        $last_stock_taking_mvt = $this->getLastStockTakingByWarehouse( $warehouse );

        // Retrieve movements
        $mvts = StockMovement::
                      where('product_id', $this->id)
                    ->where('warehouse_id', $wh_id)
                    ->where('date', '<', $date)
                    ->where( function($query) use ($last_stock_taking_mvt) {
                                if ( $last_stock_taking_mvt ) 
                                    $query->where('date', '>', $last_stock_taking_mvt->date);
                        } )
                    ->where( function($query){
                                // $now = Carbon::now()->startOfDay(); 
                                // 
                                $query->where('movement_type_id', '<>', StockMovement::INITIAL_STOCK);
                                $query->where('movement_type_id', '<>', StockMovement::ADJUSTMENT);
                        } )
                    ->orderBy('date', 'desc')
                    ->get();

        // abi_r($mvts, true);

        // abi_toSql(

        return optional($last_stock_taking_mvt)->quantity_after_movement - ($mvts->sum('quantity_before_movement') - $mvts->sum('quantity_after_movement'));
    }

    public function getStockToDate(  Carbon $date = null  )
    {
        if ( $date      == null ) $date      = Carbon::now();   //->endOfDay();
        else                      $date      = $date->endOfDay();

        $warehouses = Warehouse::get();
        $count = 0;

        foreach ($warehouses as $warehouse) {
            # code...
            $count += $this->getStockToDateByWarehouse( $warehouse->id, $date );
        }

        return $count;
    }

    public function getStockToDateFull(  Carbon $date = null  )
    {
        if ( $date      == null ) $date      = Carbon::now();   //->endOfDay();
        else                      $date      = $date->endOfDay();

        $warehouses = Warehouse::get();
        $count1 = 0;

        $mvts = collect([]);

        foreach ($warehouses as $warehouse) {
            # code...
            $ws_mvt = $this->getStockToDateFullByWarehouse( $warehouse->id, $date );
            if ($ws_mvt)
            {
                $count1 += $ws_mvt->quantity_after_movement;

                $mvts->push($ws_mvt);
            }
        }

        // Prepare
        $f_mvt = null;

        $count = 0.0;

        // Sort $mvts
                    // ->orderBy('date', 'desc')           // Guess "well ordered" movements
                    // ->orderBy('id', 'DESC')
        if ( $mvts->count() )
        {
            if ( $mvts->count() > 1 )
            $mvts = $mvts->sort(function($a, $b) {
               if($a->date === $b->date) {
                 if($a->id === $b->id) {
                   return 0;
                 }
                 return $a->id > $b->id ? -1 : 1;           // If you return -1 that moves the $b variable down the array, return 1 moves $b up the array and return 0 keeps $b in the same place. 
               } 
               return $a->date > $b->date ? -1 : 1;
            });
            // https://stackoverflow.com/questions/33713392/how-to-sort-illuminate-collection-by-multiple-columns-in-laravel-5-1/33713443#33713443
            // https://github.com/laravel/ideas/issues/11
            // https://www.php.net/manual/en/function.usort.php#refsect1-function.usort-parameters

            // First movement is the most recent one
            $f_mvt = $mvts->first();

            // Stock
            $count = $mvts->sum('quantity_after_movement');
        }

        return [
            'movement' => $f_mvt,
            'stock'    => $count,
            'stock1'   => $count1,
        ];
    }

 

 /* ************************************************************************************* */

    // Brand new stuff

    public function getStockByWarehouse( $warehouse = null )
    {
        if ( $warehouse == null ) $warehouse = Configuration::getInt('DEF_WAREHOUSE');
        $warehouse_id = $warehouse instanceof Warehouse
                    ? $warehouse->id
                    : (int) $warehouse ;

        $line = $this->warehouselines()->where('warehouse_id', $warehouse_id)->get()->first();

        if($line)
            return $line->quantity;

        return 0.0;
    }   


 /* ************************************************************************************* */

    

    public function getOutOfStock()
    { 
        if ( $this->out_of_stock == 'default' ) return Configuration::get('ABCC_OUT_OF_STOCK_PRODUCTS');

        return $this->out_of_stock;
    }
    

    public function getFeaturedImage()
    { 
        // If no featured image, GET one, anyway
        $img = $this->images()->orderBy('is_featured', 'desc')->orderBy('position', 'asc')->first();

        if ($img) return $img;

        // If no featured image, RETURN one, anyway
        return new \App\Image();
    }

    public function setFeaturedImage( Image $image )
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

            

    public static function getMrpTypeList()
    {
            $list = [];
            foreach (self::$mrp_types as $type) {
                $list[$type] = l('App\\Product.'.$type, [], 'appmultilang');;
            }

            return $list;
    }

    public static function getMrpTypeName( $status )
    {
            return l('App\\Product.'.$status, [], 'appmultilang');;
    }


    public function getMeasureUnitList()
    {
        // if ( Configuration::isTrue('ENABLE_MANUFACTURING') && $this->measureunits->count() )
        if ( $this->measureunits->count() )
            return $this->measureunits->pluck('name', 'id')->toArray();

        return MeasureUnit::pluck('name', 'id')->toArray();
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

    public function purchasemeasureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'purchase_measure_unit_id');
    }
    
    public function productmeasureunits()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\ProductMeasureUnit', 'product_id')->with('measureunit');
    }
    
    
    /**
     * Returns ALL measure units for product
     * @param 
     * @return type
     */
    public function measureunitsGet()
    // used by: public function getMeasureunitsAttribute()
    {        
        $list = $this->productmeasureunits;

        // Would be better a hasmanythrou relation...
        // but, how to attach the right value of conversion_rate?
        $units = $list->map(function ($item, $key) {
            $unit = $item->measureunit;
            $unit->conversion_rate = $item->conversion_rate;
            return $unit;
        });

        // Not sure if Product (default or stock) Measure Unit is in database, so remove (if present) and add
        $extra_units = $units->reject(function ($value, $key) {
            return $value->id == $this->measure_unit_id;
        });

        // Deault unit first
        return $extra_units->prepend( $this->measureunit );  // ->pluck('name', 'id')->toArray();
    }
    
    
    /**
     * Returns EXTRA measure units for product
     * @param 
     * @return type
     */
    public function extra_measureunitsGet()
    {
        $list = $this->productmeasureunits;

        // Would be better a hasmanythrou relation...
        // but, how to attach the right value of conversion_rate?
        $units = $list->map(function ($item, $key) {
            $unit = $item->measureunit;
            $unit->conversion_rate = $item->conversion_rate;
            return $unit;
        });

        // Not sure if Product (default or stock) Measure Unit is in database, so remove (if present) and add
        $extra_units = $units->reject(function ($value, $key) {
            return $value->id == $this->measure_unit_id;
        });

        return $extra_units;  // ->pluck('name', 'id')->toArray();
    }


    
    public function producttools()
    {
        return $this->hasMany('App\ProductTool', 'product_id')->with('tool');
    }
    
    public function toolsGet()
    // used by: public function getToolsAttribute()
    {
        $list = $this->producttools;

        $tools = $list->map(function ($item, $key) {
            return $item->tool;
        });

        return $tools;  // ->pluck('name', 'id')->toArray();
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
    
    public function certifiedboms()
    {
        return $this->boms()->where('status', 'certified');
    }
    

    public function productBOMlines()
    {
        return $this->hasMany('App\ProductBOMLine', 'product_id');
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

    // Latest Stock Movement
    // https://p.softonsofa.com/tweaking-eloquent-relations-how-to-get-latest-related-model/
    public function latestStockmovement()
    {
      return $this->hasOne('App\StockMovement')->latest();
      // Same as: $this->hasOne('App\StockMovement')->orderBy('created_at', 'desc');
    }


    public function mainsupplier()
    {
        return $this->belongsTo('App\Supplier', 'main_supplier_id');
    }

    // Alias
    public function supplier()
    {
        return $this->mainsupplier();
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

    public function stockcountlines()
    {
        return $this->hasMany('App\StockCountLine');
    }

    public function cartlines()
    {
        return $this->hasMany('App\CartLine');
    }

    public function pricerules()
    {
        return $this->hasMany('App\PriceRule');
    }

    public function customerordertemplatelines()
    {
        return $this->hasMany('App\CustomerOrderTemplateLine');
    }

    public function customerquotationlines()
    {
        return $this->hasMany('App\CustomerQuotationLine');
    }

    public function customerorderlines()
    {
        return $this->hasMany('App\CustomerOrderLine');
    }

    public function customershippingsliplines()
    {
        return $this->hasMany('App\CustomerShippingSlipLine');
    }

    public function customerinvoicelines()
    {
        return $this->hasMany('App\CustomerInvoiceLine');
    }

    public function warehouseshippingsliplines()
    {
        return $this->hasMany('App\WarehouseShippingSlipLine');
    }

    public function productionorders()
    {
        return $this->hasMany('App\ProductionOrder');
    }

    public function productionorderlines()
    {
        return $this->hasMany('App\ProductionOrderLine');
    }

    

    public function lots()
    {
        return $this->hasMany('App\Lot')->orderBy('expiry_at', 'DESC');
    }

    public function availableLots()
    {
        // $sort_order = ($this->lot_policy == 'FIFO' ? 'ASC' : 'DESC');
        // ^-- Will not work, since "$this" is not defined

        return $this->hasMany('App\Lot')->where('quantity', '>', 0)->orderBy('expiry_at', 'ASC');   // Most common sorting, I guess (FIFO)
    }

    public function availableLotsSorted()
    {
        $sort_order = ($this->lot_policy == 'FIFO' ? 'ASC' : 'DESC');

        if ($sort_order == 'ASC')
            return $this->availableLots;

        return $this->availableLots->sortByDesc('expiry_at');
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
                    'cost_average', 
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

        $priceObject = Price::create( $price, Context::getContext()->company->currency );

        return $priceObject;
    }

    public function getPriceWithEcotax()
    {
        $price = [ $this->price, $this->price_tax_inc ];        // These prices are in Company Currency

        $priceObject = Price::create( $price, Context::getContext()->company->currency );
        
        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $priceObject->add( $this->getEcotax() ); 
        }

        return $priceObject;
    }

    public function getPriceByList( PriceList $list = null )
    {
        // Return Price Object
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
//            $ecoprice = Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }

    public function getPriceByListId( $list_id = null )
    {
        $list = null;

        if ((int) $list_id > 0)
            $list = PriceList::find((int) $list_id);

        return $this->getPriceByList( $list );
    }

    // Deprecated DO NOT USE
    public function getPriceByListWithEcotax( PriceList $list = null )
    {
        // Return Price Object
        $price = $this->getPriceByList( $list );

        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }

    public function getPriceByCustomer( Customer $customer, $quantity = 1, Currency $currency = null )
    {
        // Return Price Object
        $price = $customer->getPrice( $this, $quantity, $currency );

        // Add Ecotax
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }

    public function getPriceByCustomerPriceRules( Customer $customer, $quantity = 1, Currency $currency = null )
    {
        // Return Price Object
        $price = $customer->getPriceByRules( $this, $quantity, $currency );

        // Add Ecotax
        // Price may be null if no rule applies
        if ( $price && Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }

    public function getPriceByCustomerPriceList( Customer $customer, $quantity = 1, Currency $currency = null )
    {
        // Return Price Object (Never null!!!)
        $price = $customer->getPriceByPriceList( $this, $quantity, $currency );

        // Add Ecotax
        if ( Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        {
            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }



    public function getPriceBySupplier( Supplier $supplier, $quantity = 1, Currency $currency = null )
    {
        // Return Price Object
        $price = $supplier->getPrice( $this, $quantity, $currency );

        // Add Ecotax
        if ( Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax )
        // Homeland Suppliers only
        // To do: Ecotax depends on location(in the same way that regular Taxes)
        // Check Product->getTaxRulesByProduct
        if ( $supplier->address->country_id == Context::getContext()->company->address->country_id )
        {
            // Template: $price = [ price, price_tax_inc, price_is_tax_inc ]
//            $ecoprice = Price::create([
//                        $this->getEcotax(), 
//                        $this->getEcotax()*(1.0+$price->tax_percent/100.0), 
//                        $price->price_tax_inc
//                ]);

            $price->add( $this->getEcotax() ); 
        }

        return $price;
    }


    public function getReferenceBySupplier( Supplier $supplier )
    {
        return $supplier->getReference( $this );
    }
    

    public function getEcotax()
    {
        if (  Configuration::isTrue('ENABLE_ECOTAXES') && $this->ecotax ) return $this->ecotax->amount;

        return 0.0;
    }


    public function getTaxRulesByAddress( Address $address = null )
    {
        // Taxes depending on location
        // If no address, use default Company address
        if ( $address == null ) $address = Context::getContext()->company->address;

        return $address->getTaxRules( $this->tax );
    }

    public function getTaxRulesByCustomer( Customer $customer = null )
    {
        // Taxes depending on Customer, no matter of location
        if ( $customer == null ) return collect([]);

        return $customer->getTaxRules( $this );
    }

    public function getTaxRulesByProduct( Address $address = null )
    {
        // Taxes depending on Product itself, such as recycle tax
        return collect([]);
        
        // If no address, use default Company address
        if ( $address == null ) $address = Context::getContext()->company->address;

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

    public function getTaxRules( Address $address = null, Customer $customer = null )
    {
        $rules =         $this->getTaxRulesByAddress(  $address  )
                ->merge( $this->getTaxRulesByCustomer( $customer ) )
                ->merge( $this->getTaxRulesByProduct(  $address  ) );

        // Higher Tax first
        // return $rules->sortByDesc('percent');
        // Not needed: use rule 'position' for precedence

        return $rules->sortBy('position');
    }

    public function getSupplierTaxRules( Address $address = null, Supplier $supplier = null )
    {
        $rules =         $this->getTaxRulesByAddress(  $address  )
//                ->merge( $this->getTaxRulesBySupplier( $supplier ) )
                ->merge( $this->getTaxRulesByProduct(  $address  ) );

        // Higher Tax first
        // return $rules->sortByDesc('percent');
        // Not needed: use rule 'position' for precedence

        return $rules->sortBy('position');
    }

    public function getQuantityPriceRules( Customer $customer = null )
    {
        $product = $this;

        $price_rules = PriceRule::
                    // Currency
                      where( function($query) use ($customer) {
                            if ($customer)
                            {
                                $query->where('currency_id', $customer->currency_id);
                            }
                        } )
                    // Customer range
                    ->where( function($query) use ($customer) {
                            if ($customer)
                            {
                                $query->where( function($query1) use ($customer) {

                                    $query1->where('customer_id', $customer->id);
                                    if ($customer->customer_group_id)
                                        $query1->orWhere('customer_group_id', $customer->customer_group_id);
                                } );

                                $query->orWhere( function($query1) {
                                    $query1->whereDoesntHave('customer');
                                    $query1->whereDoesntHave('customergroup');
                                } );
                            
                            }
                        } )
                    // Product range
                    ->where( function($query) use ($product) {
                                $query->where('product_id', $product->id);
                                if ($product->category_id)
                                    $query->orWhere('category_id',  $product->category_id);
                        } )
                    // Quantity range
//                    ->where( 'from_quantity', '>=', 1 )
                    // Date range
                    ->where( function($query){
                                $now = Carbon::now()->startOfDay(); 
                                $query->where( function($query) use ($now) {
                                    $query->where('date_from', null);
                                    $query->orWhere('date_from', '<=', $now);
                                } );
                                $query->where( function($query) use ($now) {
                                    $query->where('date_to', null);
                                    $query->orWhere('date_to', '>=', $now);
                                } );
                        } )
                                ->orderBy('from_quantity', 'ASC')
                                ->get();

        return $price_rules;
    }

    public function getPriceRulesByCustomer( Customer $customer, Currency $currency = null )
    {
        $rules = $customer->getPriceRules( $this, $currency );

        return $rules;
    }





    public function hasQuantityPriceRules( Customer $customer = null )
    {
        return $this->getQuantityPriceRules( $customer )->count();
    }

    public function hasExtraItemsPriceRules( Customer $customer = null )
    {
        return $this->getQuantityPriceRules($customer)->contains('rule_type', 'promo');
    }

    public function hasPackagePriceRules( Customer $customer = null )
    {
        $munits = $this->extra_measureunits->pluck('id');

        return $this->getQuantityPriceRules($customer)->whereIn('measure_unit_id', $munits)->contains('rule_type', 'pack');

        $rules = $this->getQuantityPriceRules($customer)->contains('rule_type', 'pack');

        if ( !$rules )
            return collect([]);

       

        abi_r($rules);die();

         // Hummm! Let's check measure units
        $munits = $this->extra_measureunits;
        foreach ($rules as $key => $rule) {
            # code...
            $rmu = $rule->measure_unit_id;
            if ( !$munits->contains('id', $rmu) )
            {
                // Discard rule
                $rules->forget($key);
            }
        }

        return $rules;
    }

    public function getPackagesWithPriceRules( Customer $customer = null )
    {
        $packages = null;

        $munits = $this->extra_measureunits->pluck('id');

        $rules =  $this->getQuantityPriceRules($customer)->whereIn('measure_unit_id', $munits)->where('rule_type', 'pack');

        if ( $rules->count() > 0 )
        {
            $packages = $rules->map(function ($item, $key) {
                            $unit = $item->measureunit;
                            $unit->conversion_rate = $item->conversion_rate;
                            return $unit;
                        })
                        ->unique()
//                        ->prepend( $this->measureunit )
                        ;
        }

        return $packages;
    }

    /**
     * Validate if a Price Rule still applies for a product in cart
     * (in quantity and in date validity)
     *
     * @param               $qty
     * @param Customer|null $customer
     * @return bool
     */
    public function hasApplicableQuantityPriceRules($qty, Customer $customer = null)
    {
        /** @var PriceRule $price_rule */
        foreach ($this->getQuantityPriceRules($customer) as $price_rule) {
            if ($price_rule->applies($qty)) {
                return true;
            }
        }
        return false;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include finished products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsManufactured($query, $apply = true)
    {
        if ( !$apply )
            return $query;

        return $query->where(function ($query) {
                            $query->where('procurement_type', 'manufacture');
                            $query->orWhere('procurement_type', 'assembly');
                    });
    }

    /**
     * Scope a query to only include part items products (raw materials or sub-assemblies).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsPartItem($query, $apply = true)
    {
        if ( !$apply )
            return $query;

        return $query->where(function ($query) {
                            $query->where(  'procurement_type', 'assembly');
                            $query->orWhere('procurement_type', 'purchase');
                            $query->orWhere('procurement_type', 'none');
                    });
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

    public function scopeIsPurchaseable($query, $all = false)
    {
        // Apply filters here
        if ( $all ) 
            return $query;

        return $query->where('procurement_type', 'purchase');
    }

    public function scopeIsService($query)
    {
        return $query->where('procurement_type', 'none');
    }

    public function scopeIsActive($query)
    {
        if ( Configuration::isTrue('SHOW_PRODUCTS_ACTIVE_ONLY') )
            // Scope already applied as Global Scope
            return $query;
        else
            return $query->where('active', '>', 0);
    }

    public function scopeIsBlocked($query, $apply = true)
    {
        if ( $apply )
            return $query->where('blocked', '>', 0);

        return $query->where('blocked', 0);
    }


    public function scopeIsPublished($query)
    {
        return $query;
        return $query->where('publish_to_web', '>', 0);
    }


    public function scopeIsNew($query, $apply = true)
    {
        if ( !$apply ) return $query;

        $theDate = Carbon::now()->subDays( Configuration::getInt('ABCC_NBR_DAYS_NEW_PRODUCT') );

        return $query->where(function ($query) use ($theDate) {
                $query->whereDate('new_since_date', '>=', $theDate)
                      ->orWhereDate('created_at',   '>=', $theDate);
            });

        // $column = $this->new_since_date ? 'new_since_date' : 'created_at';

        // return $query->whereDate($column, '>=', Carbon::now()->subDays( Configuration::getInt('ABCC_NBR_DAYS_NEW_PRODUCT') ));
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
            $customer = Customer::with('customergroup')->findorfail($customer_id);

            if ( !($currency_id) ) 
                $currency_id = Context::getContext()->currency->id;

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


    public function scopeQualifyForSupplier($query, $supplier_id, $currency_id) 
    {
        // Filter Products by Supplier

        return $query;
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

        $query->orWhere('product_type', 'grouped');

        return $query;
    }



    public function scopeIsOrderable($query) 
    {
        if (Configuration::isTrue('SELL_ONLY_MANUFACTURED')) {
            $query->where('procurement_type', 'manufacture');

            // Products with stock
            $query->orWhere('quantity_onhand', '>', 0);
        } else {
            // Products with stock
            $query->where('quantity_onhand', '>', 0);
        }

        $query->orWhere(function ($query) {
                if ( Configuration::get('ABCC_OUT_OF_STOCK_PRODUCTS') == 'allow' )
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
                            $query->where('out_of_stock', 'allow');
                    });
        });

        $query->orWhere('product_type', 'grouped');

        return $query;
    }


    public function scopeCheckStock($query)
    {
        // Products with stock
        if ( Configuration::isTrue('ALLOW_SALES_WITHOUT_STOCK') ) 
            return $query;

//        return $query->where('quantity_onhand', '>', 0);

        return $query->where(function ($query) {
                            $query->where('quantity_onhand', '>', 0);
                            // Pack Products are assembled "on order", and stock is zero
                            $query->orWhere('product_type', 'grouped');
        });
    }
}
