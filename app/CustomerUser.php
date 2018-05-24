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
        'active', 'language_id', 'customer_id'
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
        'email'       => array('required', 'email'),
        'password'    => array('required', 'min:2', 'max:32'),
        'language_id' => 'exists:languages,id',
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
        return $this->belongsTo('App\Customer');
    }
}
