<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LotItem extends Model
{

	protected $fillable = ['lot_id', 'is_reservation', 'quantity'
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
        return $this->morphMany('App\Lot', 'lotable');
    }
/*
    // Alias
    public function lots()
    {
        return $this->lotitems();
    }
*/
}
