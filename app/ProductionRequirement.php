<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ProductionRequirement extends Model
{
    use ViewFormatterTrait;
    
    protected $fillable = [ 'line_sort_order', 'type', 'created_via', 'product_id', 'reference', 'name', 
                            'product_bom_id', 'measure_unit_id', 'required_quantity', 'manufacturing_batch_size', 
                            'notes', 
                            'warehouse_id', 'work_center_id', 'production_sheet_id'
                          ];

    public static $rules = array(
        'product_id'    => 'required',
        );
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productionsheet()
    {
        return $this->belongsTo('App\ProductionSheet', 'production_sheet_id');
    }
    
    // Alias
    public function document()
    {
        return $this->productionsheet();
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }
    
    public function workcenter()
    {
        return $this->belongsTo('App\WorkCenter', 'work_center_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function productbom()
    {
        // Same as: $this->product->product_bom_id  <= but this IS NOT a relation!!!
        return $this->belongsTo('App\ProductBOM', 'product_bom_id')->with('measureunit');
    }

    // Alias
    public function bom()
    {
        return $this->productbom();
    }
}
