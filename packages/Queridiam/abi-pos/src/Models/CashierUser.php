<?php

namespace Queridiam\POS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Configuration;
use App\Models\Language;
use App\Models\SalesRep;

use Illuminate\Database\Eloquent\SoftDeletes;

// use App\Notifications\CashierResetPasswordNotification;

class CashierUser extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * Configure guard.
     *
     */
    protected $guard = 'cashier';

    /**
     * Always load relations.
     *
     */
//    public $with = ['customer'];

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
        'active', 'status', 
        'language_id', 'cash_regiter_id', 'sales_rep_id'
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
        'language_id' => 'exists:languages,id', 
        'email' => 'required|email|unique:cashier_users,email',
        'password'    => 'required|min:8|max:32',
//        'language_id' => 'exists:languages,id',
//        'customer_id' => 'exists:customers,id',
//        'address_id' => 'exists:addresses,id',
    );



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
        $this->notify(new CashierResetPasswordNotification($token));
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

/*
    public function canDisplayPricesTaxInc()
    {
        return $this->display_prices_tax_inc >= 0 ?
            (bool)$this->display_prices_tax_inc :
            (bool)Configuration::get('ABCC_DISPLAY_PRICES_TAX_INC');
    }
*/


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function cashregister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function salesrep()
    {
        return $this->belongsTo(SalesRep::class, 'sales_rep_id');
    }


    public function cashregistersessions() 
    {
        return $this->hasMany(CashRegisterSession::class, 'cashier_user_id');
    }
}
