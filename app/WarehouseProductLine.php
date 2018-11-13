<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class WarehouseProductLine extends Model
{

    use ViewFormatterTrait;

    protected $table = 'product_warehouse';

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

}
