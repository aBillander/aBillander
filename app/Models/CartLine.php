<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
                            'package_measure_unit_id', 'pmu_conversion_rate', 'pmu_label', 
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
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
        // https://laravel-news.com/laravel-model-caching
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

    public function package_measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'package_measure_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

}
