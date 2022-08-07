<?php

namespace Queridiam\POS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * Coins / Bills.
     *
     * Set of Coins / Bills that will be used in opening and closing a Cash Register
     */

class CashDenominationSet extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
