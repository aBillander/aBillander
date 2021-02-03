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


    public static function boot()
    {
        parent::boot();


        static::deleting(function ($method)
        {
            // before delete() method call this
            $relations = [
                    'paymentmethods',
                    'payments',
            ];

            // load relations
            $method->load( $relations );

            // Check Relations
            foreach ($relations as $relation) {
                # code...
                if ( $method->{$relation}->count() > 0 )
                    throw new \Exception( l('Payment Type has :relation', ['relation' => $relation], 'exceptions') );
            }

        });
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function paymentmethods()
    {
        return $this->hasMany('App\PaymentMethod');
    }
    
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}