<?php

namespace App\Models;

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
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }
    
    // Alias
    public function document()
    {
        return $this->productionorder();
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get all of the Production Order line's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }


    public function lotitems()
    {
        return $this->morphMany(LotItem::class, 'lotable')->with('lot');
    }

    public function getLotsAttribute()
    {
        // Document line -> stock movements (one or more) -> lot (one per movement)
        // see: https://stackoverflow.com/questions/43285779/laravel-polymorphic-relations-has-many-through

        if (!$this->relationLoaded('lotitems.lot')) {
            $this->load('lotitems.lot');
        }

        return $this->lotitems->pluck('lot');
    }
}
