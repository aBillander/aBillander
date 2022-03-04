<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Notifications\CustomerResetPasswordNotification;

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
        'active', 'enable_quotations', 'enable_min_order', 'use_default_min_order_value', 'min_order_value', 'display_prices_tax_inc',
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
        'language_id' => 'exists:languages,id', 
        'email' => 'required|email|unique:customer_users,email',
        'password'    => 'required|min:6|max:32',
//        'language_id' => 'exists:languages,id',
//        'customer_id' => 'exists:customers,id',
//        'address_id' => 'exists:addresses,id',
    );


    public static function boot()
    {
        parent::boot();

        static::deleting(function ($cuser) {
            // before delete() method call this

            // Cart
            if ( $cart = $cuser->cart )
                $cart->delete();

            // emaillogs
            foreach ($cuser->emaillogs as $line) {
                $line->delete();
            }
        });

    }

/* Use "can" functions instead
    public function getCustomEnableQuotationsAttribute()
    {
        if ( $this->enable_quotations < 0 ) return Configuration::get('ABCC_ENABLE_QUOTATIONS');

        return $this->enable_quotations;
    }

    public function getCustomEnableMinOrderAttribute()
    {
        if ( $this->enable_min_order < 0 ) return Configuration::get('ABCC_ENABLE_MIN_ORDER');

        return $this->enable_min_order;
    }

    public function getCustomMinOrderValueAttribute()
    {
        if ( $this->use_default_min_order_value > 0 ) return Configuration::get('ABCC_MIN_ORDER_VALUE');

        return $this->min_order_value;
    }

    public function getCustomDisplayPricesTaxIncAttribute()
    {
        if ( $this->display_prices_tax_inc < 0 ) return Configuration::get('ABCC_DISPLAY_PRICES_TAX_INC');

        return $this->display_prices_tax_inc;
    }
*/



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


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
        // Is Customer Active?
        // Maybe a global scope is applied to Customers
        if ( !$this->customer )
            return false;
        
        // Customer is not Active
        if ( !$this->customer->is_active )
            return false;
        
        return $this->active > 0;
    }

    public function getIsPrincipalAttribute()
    {
        return !( $this->address_id > 0 );

    }

    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;

    }

    // Default Shipping Address for CustomerUser
    public function getShippingaddressAttribute()
    {
        return $this->address_id > 0
                    ? $this->address
                    : $this->customer->shipping_address();

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
        // Accessor
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

        $can = $this->use_default_min_order_value > 0 ? Configuration::get('ABCC_MIN_ORDER_VALUE') : $this->min_order_value;

        // $can = $this->min_order_value > 0 ? $this->min_order_value : Configuration::getNumber('ABCC_MIN_ORDER_VALUE') ; 

        return $can;
    }

    public function canDisplayPricesTaxInc()
    {
        return $this->display_prices_tax_inc >= 0 ?
            (bool)$this->display_prices_tax_inc :
            (bool)Configuration::get('ABCC_DISPLAY_PRICES_TAX_INC');
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function address()
    {
        // Makes sense when CustomerUser is allowed to only one address
        return $this->hasOne(Address::class, 'id', 'address_id')
                   ->where('addressable_type', Customer::class);


        // Won't work: should return ALWAYS a relation instance
        // When CustomerUser is allowed to only one address (address_id>0) returns Address $address
        // When CustomerUser is allowed to all addresses (address_id=0) returns null (unless Customer has nly one address!!!)
        if ( $this->address_id > 0 )
            return $this->hasOne(Address::class, 'id', 'address_id')
                   ->where('addressable_type', Customer::class);

        if ( $this->customer->addresses()->count() == 1 )
            return $this->customer->address;

        return null;    // CustomerUser is allowed to more than one addresses

        // Convenient default when $this->address_id == 0 ??
        // return $this->customer->shipping_address();    
    }


    public function emaillogs()
    {
        return $this->morphMany(EmailLog::class, 'userable');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
