<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class WarehouseCombinationLine extends Model
{

    use ViewFormatterTrait;

    protected $table = 'combination_warehouse';

    // The relationships which should be automatically eager loaded.
    public $with = ['combination'];

    protected $fillable = [
                    'combination_id', 'quantity', 'warehouse_id',
    ];

    public static $rules = [
//        'combination_id'    => 'required',
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
