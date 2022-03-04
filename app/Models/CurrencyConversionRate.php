<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyConversionRate extends Model
{
    protected $guarded = array('id');

    protected $dates = ['date'];

    protected $fillable =  ['date', 'currency_id', 'conversion_rate', 'user_id'];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
