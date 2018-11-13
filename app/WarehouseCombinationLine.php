<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class WarehouseCombinationLine extends Model
{

    use ViewFormatterTrait;

    protected $table = 'combination_warehouse';

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

}
