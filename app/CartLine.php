<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Price;

use App\Traits\ViewFormatterTrait;

class CartLine extends Model
{

    use ViewFormatterTrait;

    //
//    protected $with = ['cart'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['cart'];

    protected $fillable = [
    						'line_type', 'line_sort_order', 'product_id', 'combination_id', 'reference', 'name', 
    						'quantity', 'extra_quantity', 'extra_quantity_label', 'measure_unit_id', 
                            'package_measure_unit_id', 'pmu_conversion_rate', 
    						'unit_customer_price', 'unit_customer_final_price', 'sales_equalization', 
                            'total_tax_incl', 'total_tax_excl',
                            'tax_percent', 'tax_se_percent', 'tax_id', 
    ];

    public static $rules = [
                            'product_id' => 'exists:products,id',
//                            'combination_id' => 'sometimes|exists:combinations,id',
                            'measure_unit_id' => 'exists:measure_units,id',
               ];
    

    
    public function getImageAttribute()
    {
        if ($this->line_type == 'product') return $this->product->getFeaturedImage();

        return new Image();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function cart()
    {
        return $this->belongsTo('App\Cart', 'cart_id', 'id');
        // https://laravel-news.com/laravel-model-caching
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

    public function package_measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'package_measure_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo('App\Tax');
    }

}
