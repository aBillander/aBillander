<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BOMItem extends Model
{

	protected $fillable = ['product_id', 'product_bom_id', 'quantity'
    ];

    public static $rules = [
        'product_id' => 'exists:products,id',
        'product_bom_id' => 'exists:product_b_o_ms,id',
        'quantity' => 'required|numeric'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function productbom()
    {
        return $this->belongsTo('App\ProductBOM', 'product_bom_id');
    }
}
