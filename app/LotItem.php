<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LotItem extends Model
{

	protected $fillable = ['product_id', 'product_bom_id', 'quantity'
    ];

    public static $rules = [
        'lot_id' => 'exists:lots,id',
        'quantity' => 'required|numeric'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function lot()
    {
        return $this->belongsTo('App\Lot');
    }
    
    public function lotable()
    {
        return $this->morphTo();
    }

    // Reverse relation
    public function lotitems()
    {
        return $this->morphMany('App\Lots', 'lotable');
    }

    // Alias
    public function lots()
    {
        return $this->lotitems();
    }
}
