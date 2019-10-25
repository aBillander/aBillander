<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
	protected $fillable = [ 'name', 'alias', 'active', 'accounting_code', ];

    public static $rules = [
        'name'    => ['required', 'min:2', 'max:64'],
        'alias'    => ['required', 'min:2', 'max:32'],
        'accounting_code'    => ['required', 'min:2', 'max:32'],
    ];
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}