<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Notifications\ResetPasswordNotification;

use App\Configuration;

use DB;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

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
        'home_page', 'theme', 'is_admin', 'active', 'language_id'
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
        'password'    => array('required', 'min:6', 'max:32'),
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
        $this->notify(new ResetPasswordNotification($token));
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

    public function isAdmin()
    {
        return $this->is_admin;
    }


    public static function getUserList()
    {
            return User::select('id', DB::raw("concat( firstname, ' ', lastname) as user_name"))->pluck('user_name', 'id')->toArray();
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


    public function emaillogs()
    {
        return $this->morphMany('App\EmailLog', 'userable');
    }
}
