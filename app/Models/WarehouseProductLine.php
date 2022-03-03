<?php

// See: https://pineco.de/easy-role-management-pivot-models/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class WarehouseProductLine extends Model
{

    use ViewFormatterTrait;

    protected $table = 'product_warehouse';

    // The relationships which should be automatically eager loaded.
    public $with = ['product'];

    protected $fillable = [
                    'product_id', 'quantity', 'warehouse_id',
    ];

    public static $rules = [
//        'product_id'    => 'required',
    ];


    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }

    // Better: stock is kept in Product default Measure Unit
    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

}
