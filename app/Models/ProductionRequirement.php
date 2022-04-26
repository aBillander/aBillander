<?php

namespace App\Models;

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
        return $this->belongsTo(ProductionSheet::class, 'production_sheet_id');
    }
    
    // Alias
    public function document()
    {
        return $this->productionsheet();
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }
    
    public function workcenter()
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productbom()
    {
        // Same as: $this->product->product_bom_id  <= but this IS NOT a relation!!!
        return $this->belongsTo(ProductBOM::class, 'product_bom_id')->with('measureunit');
    }

    // Alias
    public function bom()
    {
        return $this->productbom();
    }
}
