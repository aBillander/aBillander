<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class AssemblyOrderLine extends Model
{
    use ViewFormatterTrait;

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'product_id', 'reference', 'name', 
    						'pack_item_quantity', 'required_quantity', 'real_quantity', 'measure_unit_id', 
    						'warehouse_id'
                          ];

    public static $rules = array(
    	'product_id'    => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function assemblyorder()
    {
        return $this->belongsTo('App\AssemblyOrder', 'assembly_order_id');
    }
    
    // Alias
    public function document()
    {
        return $this->assemblyorder();
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    /**
     * Get all of the Production Order line's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }
}
