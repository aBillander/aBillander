<?php

namespace App\Models;

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
        'firstname'        => 'required|min:2|max:32',
 //       'email' => 'sometimes|unique:contacts,email',
        'party_id' => 'exists:parties,id',
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
        return $this->belongsTo(User::class, 'user_created_by_id');
	}

    public function party()
    {
        return $this->belongsTo(Party::class);
	}
    
//    public function leads()
//    {
//        return $this->hasMany(Lead::class);
//    }
}