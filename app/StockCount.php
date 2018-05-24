<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    protected $dates = ['document_date'];
    
    protected $fillable = [ 'document_date', 'sequence_id'. 'document_prefix', 'document_id', 'document_reference', 
    						'warehouse_id', 'initial_inventory', 'notes' 
    						];

    public static $rules = array(
                            'document_date' => 'date',
                            'sequence_id' => 'exists:sequences,id',
                            'warehouse_id' => 'exists:warehouses,id',
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function stockcountlines()
    {
        return $this->hasMany('App\StockCountLine');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
	}
}
