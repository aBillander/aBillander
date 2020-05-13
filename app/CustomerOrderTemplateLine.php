<?php

namespace App;

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
        return $this->belongsTo('App\CustomerOrderTemplate', 'customer_order_template_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
	}

    public function combination()
    {
        return $this->belongsTo('App\Combination');
	}

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    public function packagemeasureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'package_measure_unit_id');
    }
}
