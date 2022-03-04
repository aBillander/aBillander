<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderTemplateLine extends Model
{

    protected $fillable = ['line_sort_order', 'line_type', 'product_id', 'combination_id',
    						'quantity', 'measure_unit_id', 'package_measure_unit_id', 'pmu_conversion_rate', 'pmu_label', 
    						'notes', 'active',
    					];

    public static $rules = array(
//        'carrier_id'   => 'required|exists:carriers,id',
        'product_id'   => 'nullable|sometimes|exists:products,id',
        'combination_id'   => 'nullable|sometimes|exists:combinations,id',
        'measure_unit_id'   => 'nullable|sometimes|exists:measure_units,id',
        'package_measure_unit_id'   => 'nullable|sometimes|exists:measure_units,id',

    	'quantity'   => 'numeric|min:0',
    	);


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerordertemplate()
    {
        return $this->belongsTo(CustomerOrderTemplate::class, 'customer_order_template_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
	}

    public function combination()
    {
        return $this->belongsTo(Combination::class);
	}

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }

    public function packagemeasureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'package_measure_unit_id');
    }
}
