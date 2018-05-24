<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCountLine extends Model
{
    // Good for Stock Counts and for Stock Adjustments (lines that not belong to any Stock Count)

    protected $dates = ['date'];
    
    protected $fillable = [ 'date', 'quantity', 'quantity_counted', 'price',  
    						'product_id', 'combination_id', 'warehouse_id', 'user_id'
    						];

    public static $rules = array(
                            'date' => 'date',
                            'product_id' => 'exists:products,id',
                            'combination_id' => 'sometimes|exists:combinations,id',
                            'warehouse_id' => 'exists:warehouses,id',
                            'user_id' => 'exists:users,id',
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function stockcount()
    {
        return $this->belongsTo('App\StockCount');
    }
}
