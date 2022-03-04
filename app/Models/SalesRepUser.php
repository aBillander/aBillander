<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Notifications\SalesRepResetPasswordNotification;

class SalesRepUser extends Authenticatable
{
    use Notifiable;
//    use SoftDeletes;

    /**
     * Configure guard.
     *
     */
    protected $guard = 'salesrep';

    /**
     * Always load relations.
     *
     */
    public $with = ['salesrep'];

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
        'active', 'allow_abcc_access', 
        'language_id', 'warehouse_id', 'sales_rep_id'
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
        'sales_rep_id' => 'exists:sales_reps,id', 
        'email' => 'required|email|unique:sales_rep_users,email',
        'password'    => array('required', 'min:6', 'max:32'),
//        'language_id' => 'exists:languages,id',
        'warehouse_id' => 'sometimes|nullable|exists:warehouses,id',        // https://stackoverflow.com/questions/52102021/laravel-validation-depending-on-the-value-of-field
        // https://laravel.com/docs/5.5/validation#rule-exists
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
        $this->notify(new SalesRepResetPasswordNotification($token));
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

    public function isActive()
    {
        return $this->active;

        // See: https://pusher.com/tutorials/multiple-authentication-guards-laravel#modify-how-our-users-are-redirected-if-authenticated
    }

    public function canGiveAbccAccess()
    {
        $can = $this->allow_abcc_access >= 0 ? $this->allow_abcc_access : Configuration::isTrue('ABSRC_ALLOW_ABCC_ACCESS') ; 

        return $can;
    }


    public function getAllowedWarehouseList()
    {
            $warehouseList = Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"));

            if ( $this->warehouse_id > 0 )
            {
                $warehouses = [$this->warehouse_id , Configuration::get('DEF_WAREHOUSE')];

                $warehouseList = $warehouseList->whereIn('id', $warehouses); 
            }

            return $warehouseList->pluck('full_name', 'id')->toArray();
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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function salesrep()
    {
        return $this->belongsTo(SalesRep::class, 'sales_rep_id');
    }
}
