<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackItem extends Model
{

    // The relationships which should be automatically eager loaded.
    // public $with = ['product'];

    protected $fillable = [
                    'line_sort_order', 'item_product_id', 'item_combination_id', 'reference', 'name', 
                    'quantity', 'measure_unit_id', 'package_measure_unit_id', 'pmu_conversion_rate', 
                    'notes',
    ];

    public static $rules = [
//        'product_id'    => 'required',
    ];


    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // "Parent" product
    public function packable()
    {
       return $this->belongsTo('App\Product');
    }

    // Pack item product
    public function product()
    {
       return $this->belongsTo('App\Product', 'item_product_id');
    }
    
    public function combination()
    {
        return $this->belongsTo('App\Combination');
    }

    // Better: stock is kept in Product default Measure Unit
    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

}
