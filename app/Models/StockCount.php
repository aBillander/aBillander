<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    protected $dates = ['document_date'];
    
    protected $fillable = [ 'document_date', 'name', 
    						'warehouse_id', 'initial_inventory', 'notes' 
    						];

    public static $rules = array(
//                            'document_date' => 'date',
//                            'sequence_id' => 'exists:sequences,id',
                            'warehouse_id' => 'exists:warehouses,id',
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function stockcountlines()
    {
        return $this->hasMany(StockCountLine::class, 'stock_count_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
	}
}
