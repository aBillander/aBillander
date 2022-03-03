<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class WarehouseShippingSlipLine extends Model
{
    use ViewFormatterTrait;

/*
    public static $types = array(
            'product',
            'service', 
            'shipping', 
            'discount', 
            'comment',
            'account',      // Something to charge to an accounting account
        );
*/
    

    protected $fillable = ['line_sort_order', 
                    'product_id', 'combination_id', 'reference', 'name', 
                    'quantity', 'measure_unit_id',
                    'package_measure_unit_id', 'pmu_conversion_rate', 'notes', 'locked',
    ];


    protected static function boot()
    {
        parent::boot();

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        // https://laracasts.com/discuss/channels/eloquent/laravel-delete-model-with-all-relations
        static::deleting(function ($line)
        {
            // Unlink Stock Movements
            foreach( $line->stockmovements as $mvt ) {
                // $mvt->delete();

                $mvt->stockmovementable_id   = 0;
                $mvt->stockmovementable_type = '';
                $mvt->save();
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */




    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document()
    {
       // return $this->belongsTo($this->getParentClassName(), $this->getParentClassSnakeCase().'_id');
       return $this->belongsTo('App\WarehouseShippingSlip', 'warehouse_shipping_slip_id');
    }
    
    


    public function product()
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

    public function packagemeasureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'package_measure_unit_id');
    }

    /**
     * Get all of the billable's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }
}
