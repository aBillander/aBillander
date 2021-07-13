<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ProductionOrderLine extends Model
{
    use ViewFormatterTrait;

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'line_sort_order', 'type', 'product_id', 'reference', 'name', 
    						'bom_line_quantity', 'bom_quantity', 'required_quantity', 'real_quantity', 'warehouse_id'
                          ];

    public static $rules = array(
    	'product_id'    => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productionorder()
    {
        return $this->belongsTo('App\ProductionOrder', 'production_order_id');
    }
    
    // Alias
    public function document()
    {
        return $this->productionorder();
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    /**
     * Get all of the Production Order line's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }
}
