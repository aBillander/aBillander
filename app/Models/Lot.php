<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;
use App\Traits\ModelAttachmentableTrait;

use App\Traits\LotGeneratorTrait;

class Lot extends Model
{
    use ViewFormatterTrait;
    use ModelAttachmentableTrait;

    use LotGeneratorTrait;

    protected $dates = [
            'manufactured_at',
            'expiry_at',
    ];

    protected $fillable = ['reference', 'product_id', 'combination_id',
    					   'quantity_initial', 'quantity', 
    					   'measure_unit_id', 'package_measure_unit_id', 'pmu_conversion_rate',
    					   'manufactured_at', 'expiry_at', 'blocked', 
    					   'notes',
                           'warehouse_id',
    					];

    public static $rules = [
        'reference'         => 'required|min:2|max:32',

        'product_id'   => 'required|exists:products,id',
//        'combination_id'   => 'nullable|sometimes|exists:combinations,id',  // <= Ensure Combination belongs to Product!!

//    	'quantity_initial'   => 'numeric|min:0',
    	'quantity'   => 'required|numeric|min:0',

        'manufactured_at' => 'nullable|date',
        'expiry_at'  => 'nullable|date',

        'warehouse_id'     => 'exists:warehouses,id'
    	];



    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */   


    public function getWeight() 
    {
        return $this->quantity * $this->product->weight;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public  function product()
    {
       return $this->belongsTo('App\Product');
    }

    public function combination()
    {
       return $this->belongsTo('App\Combination');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    public function package_measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'package_measure_unit_id');
    }
    
    public  function warehouse()
    {
       return $this->belongsTo('App\Warehouse');
    }
    
    public function stockmovements()
    {
        return $this->hasMany('App\StockMovement');
    }


    
    public function lotitems()
    {
        return $this->hasMany('App\LotItem');
    }

    public function lotallocateditems()
    {
        return $this->hasMany('App\LotItem')->where('is_reservation', '>', 0);
    }

    // See: https://reinink.ca/articles/dynamic-relationships-in-laravel-using-subqueries
    // https://reinink.ca/articles/ordering-database-queries-by-relationship-columns-in-laravel
    public function allocatedQuantity()
    {
        return $this->lotitems()->where('is_reservation', '>', 0)->sum('quantity');
    }

    public function availableQuantity()
    {
        return $this->quantity - $this->allocatedQuantity();
    }

    public function allocatedByCustomerShippingSlipLineId( $line_id = 0 )
    {
        $lotitem = $this->lotitems()
                        ->where('lotable_type', CustomerShippingSlipLine::class )
                        ->where('lotable_id', $line_id )
                        ->first();
        // abi_r($lotitem );

        return $lotitem ? $lotitem->quantity : 0;
    }




    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {

        if ($params['date_from'])
            // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('manufactured_at', '>=', $params['date_from'].' 00:00:00');
        }

        if ($params['date_to'])
        {
            $query->where('manufactured_at', '<=', $params['date_to']  .' 23:59:59');
        }


        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
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


        if ( isset($params['product_reference']) && trim($params['product_reference']) !== '' )
        {
            $product_reference = $params['product_reference'];

            $query->whereHas('product', function($q) use ($product_reference) 
            {
                $q->where('reference', 'LIKE', '%' . $product_reference . '%');

            });
        }

        if ( isset($params['product_name']) && trim($params['product_name']) !== '' )
        {
            $product_name = $params['product_name'];

            $query->whereHas('product', function($q) use ($product_name) 
            {
                $q->where('name', 'LIKE', '%' . $product_name . '%');

            });
        }

        if ( isset($params['warehouse_id']) && $params['warehouse_id'] > 0 )
        {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }

        if ( isset($params['quantity']) )
        {
            $quantity = (float) $params['quantity'];

            $quantity_prefix = isset($params['quantity_prefix']) ? $params['quantity_prefix'] : 'eq';

            $quantity_prefixList = abi_quantity_prefixes();

            $quantity_operator = array_key_exists($quantity_prefix, $quantity_prefixList) ? $quantity_prefixList[$quantity_prefix] : '=';

            $query->where('quantity', $quantity_operator, $quantity);
        }

        return $query;
    }
}
