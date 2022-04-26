<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;
# use App\Traits\BillableIntrospectorTrait;

# use ReflectionClass;

class StockCountLine extends Model
{

    use ViewFormatterTrait;
#    use BillableIntrospectorTrait;
    // Good for Stock Counts and for Stock Adjustments (lines that not belong to any Stock Count)

    protected $dates = ['date'];
    
    protected $fillable = [ 'date', 'quantity', 'cost_price', 'cost_average', 'last_purchase_price', 
    						'product_id', 'combination_id', 'reference', 'name',
                            'user_id'
    						];

    public static $rules = array(
//                            'date' => 'date',
                            'product_id' => 'exists:products,id',
//                            'combination_id' => 'sometimes|exists:combinations,id',
                            'quantity'      => 'required|numeric|min:0', 
                            'cost_price'      => 'sometimes|numeric|min:0', 
//                           'warehouse_id' => 'exists:warehouses,id',
//                           'user_id' => 'exists:users,id',
    	);


    public static function boot()
    {
        parent::boot();
/*
        static::creating(function($corder)
        {
            $corder->secure_key = md5(uniqid(rand(), true));
            
            if ( $corder->shippingmethod )
                $corder->carrier_id = $corder->shippingmethod->carrier_id;
        });
*/
        static::saving(function($record)
        {
            $record->user_id = \Auth::id();
        });

    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    

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


    public function scopeFilter($query, $params)
    {
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('products.reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));

            /*
            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
            */
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('products.name', 'LIKE', '%' . trim($params['name'] . '%'));
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

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function stockcount()
    {
        return $this->belongsTo(StockCount::class, 'stock_count_id');
    }

    // Alias
    public function document()
    {
        return $this->stockcount();

        # abi_r($this->getParentClassName());
        # abi_r($this->getParentClassSnakeCase());

        # return $this->belongsTo($this->getParentClassName(), $this->getParentClassSnakeCase().'_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all of the stock count line's stock movements.
     */
    public function stockmovements()
    {
        return $this->morphMany(StockMovement::class, 'stockmovementable');
    }

    /**
     * Get the only of the stock count line's stock movements.
     * (if U R sure there is only one!)
     */
    public function stockmovement()
    {
        return $this->hasOne(StockMovement::class, 'stockmovementable_id','id')
                        ->where('stockmovementable_type', StockCountLine::class);
    }
}
