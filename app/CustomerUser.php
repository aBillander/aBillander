<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Notifications\CustomerResetPasswordNotification;

class CustomerUser extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * Configure guard.
     *
     */
    protected $guard = 'customer';

    /**
     * Always load relations.
     *
     */
    public $with = ['customer'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'firstname', 'lastname', 
//        'home_page', 'is_admin', 
        'active', 'enable_quotations', 'enable_min_order', 'min_order_value', 
        'language_id', 'customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Validation rules
     * 
     */
    public static $rules = array(
//        'email'       => array('required', 'email'),
        'password'    => array('required', 'min:2', 'max:32'),
//        'language_id' => 'exists:languages,id',
//        'customer_id' => 'exists:customers,id',
    );

    /**  trait CanResetPassword
     *
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPasswordNotification($token));
    }


    /**
     * Handy methods
     * 
     */
    public function getFullName()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function isActive()
    {
        return $this->active;

        // See: https://pusher.com/tutorials/multiple-authentication-guards-laravel#modify-how-our-users-are-redirected-if-authenticated
    }

    public function canQuotations()
    {
        $can = $this->enable_quotations >= 0 ? $this->enable_quotations : Configuration::isTrue('ABCC_ENABLE_QUOTATIONS') ; 

        return $can;
    }

    public function canMinOrder()
    {
        $can = $this->enable_min_order >= 0 ? $this->enable_min_order : Configuration::isTrue('ABCC_ENABLE_MIN_ORDER') ; 

        return $can;
    }

    public function canMinOrderValue()
    {
        if( !$this->canMinOrder() ) return 0.0;

        $can = $this->min_order_value > 0 ? $this->min_order_value : Configuration::getNumber('ABCC_MIN_ORDER_VALUE') ; 

        return $can;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}
