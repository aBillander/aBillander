<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

//    protected $dates = ['deleted_at'];

//    protected $appends = ['percent'];
    
    protected $fillable = [ 'firstname', 'lastname', 'job_title', 'email', 'phone', 'phone_mobile', 'address', 
                            'website', 'blocked', 'active', 'notes', 
                            'user_created_by_id', 'party_id'
    ];

    public static $rules = [
 //   	'name'    => array('required', 'min:2', 'max:64'),
 //       'country_id' => 'exists:countries,id',
 //   	'percent' => array('required', 'numeric', 'between:0,100')
        ];

    


    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;

    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function createdby()
    {
        return $this->belongsTo('App\User', 'user_created_by_id');
	}

    public function party()
    {
        return $this->belongsTo('App\Party');
	}
    
//    public function leads()
//    {
//        return $this->hasMany('App\Lead');
//    }
}
