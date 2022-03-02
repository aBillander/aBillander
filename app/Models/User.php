<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'firstname', 'lastname', 
        'home_page', 'theme', 'is_admin', 'active', 'language_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Validation rules
     * 
     */
    public static $rules = array(
        'email'       => array('required', 'email'),
        'password'    => array('required', 'min:8', 'max:32'),
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
        
        return Configuration::getString('USE_CUSTOM_THEME');    // If 'USE_CUSTOM_THEME' == null, returns ''
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }


    public static function getUserList()
    {
            return User::select('id', \DB::raw("concat( firstname, ' ', lastname) as user_name"))->pluck('user_name', 'id')->toArray();
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


    public function emaillogs()
    {
        return $this->morphMany(EmailLog::class, 'userable');
    }
}
