<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Price;

use App\Traits\ViewFormatterTrait;

class CartLine extends Model
{

    use ViewFormatterTrait;

    //

    protected $fillable = [
    						'line_sort_order', 'product_id', 'combination_id', 'reference', 'name', 
    						'quantity', 'measure_unit_id', 
    						'unit_customer_price', 'tax_percent', 'tax_id', 
    ];

    public static $rules = [
                            'product_id' => 'exists:products,id',
//                            'combination_id' => 'sometimes|exists:combinations,id',
                            'measure_unit_id' => 'exists:measure_units,id',
               ];
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function cart()
    {
        return $this->belongsTo('App\Cart');
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

    public function tax()
    {
        return $this->belongsTo('App\Tax');
    }

}
