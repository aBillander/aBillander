<?php

namespace App\Models;

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
       return $this->belongsTo(Product::class);
    }

    // Pack item product
    public function product()
    {
       return $this->belongsTo(Product::class, 'item_product_id');
    }
    
    public function combination()
    {
        return $this->belongsTo(Combination::class);
    }

    // Better: stock is kept in Product default Measure Unit
    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }

}
