<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Notifications\CustomerResetPasswordNotification;

class CustomerUser extends Authenticatable
{
    use Notifiable;
//    use SoftDeletes;

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
        'active', 'enable_quotations', 'enable_min_order', 'min_order_value', 'display_prices_tax_inc',
        'language_id', 'customer_id', 'address_id'
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
        'customer_id' => 'exists:customers,id', 
        'email' => 'required|email|unique:customer_users,email',
        'password'    => 'required|min:6|max:32',
//        'language_id' => 'exists:languages,id',
//        'customer_id' => 'exists:customers,id',
//        'address_id' => 'exists:addresses,id',
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


    public function getIsActiveAttribute()
    {
        return $this->active;
    }

    public function getIsPrincipalAttribute()
    {
        return !( $this->address_id > 0 );

    }

    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;

    }

    /**
     * Handy methods
     * 
     */
    public function getFullName()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getAllowedAddresses()
    {

        if ( $this->address_id )
        {
            return $this->customer->addresses->where('id', $this->address_id);
        }

        return $this->customer->addresses;
    }

    public function getAllowedAddressList()
    {

        if ( $this->address_id )
        {
            return $this->customer->addresses()->where('id', $this->address_id)->pluck( 'alias', 'id' )->toArray();
        }

        return $this->customer->getAddressList();
    }

    public function getTheme()
    {
        if ( $this->theme )
        {
            return $this->theme;
        }

        if ( Configuration::isNotEmpty('USE_CUSTOM_THEME') )
        {
            return Configuration::get('USE_CUSTOM_THEME');
        }

        return '';
    }

    // Alias
    public function isActive()
    {
        return $this->is_active;

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

    public function canDisplayPricesTaxInc()
    {
        return $this->display_prices_tax_inc >= 0 ? $this->display_prices_tax_inc : Configuration::getNumber('ABCC_DISPLAY_PRICES_TAX_INC');
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

    public function address()
    {
        return $this->belongsTo('App\Address', 'address_id');
    }


    public function emaillogs()
    {
        return $this->morphMany('App\EmailLog', 'userable');
    }

    public function cart()
    {
        return $this->hasOne('App\Cart');
    }
}
