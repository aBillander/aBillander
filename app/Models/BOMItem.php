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
        return $this->belongsTo(Product::class);
    }

    public function productbom()
    {
        return $this->belongsTo(ProductBOM::class, 'product_bom_id');
    }
}
