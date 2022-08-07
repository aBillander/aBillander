<?php

namespace Queridiam\POS\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegisterSession extends Model
{
//    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'starting_cash_denominations' => 'array', 
        'closing_cash_denominations'  => 'array', 
    ];
}
