<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    public static $types = array(
            'Employee', 
            'Manager',
            'Secretary',
            'Technician',
            'Engineer',
            'Researcher',
            'Warehouse Keeper',
        );

//    protected $dates = ['deleted_at'];

//    protected $appends = ['percent'];
    
    protected $fillable = [ 'firstname', 'lastname', 'job_title', 'type', 'email', 'phone', 'phone_mobile', 'address_text', 
                            'website', 'is_primary', 'blocked', 'active', 'notes', 
                            'user_created_by_id', 'party_id', 'customer_id', 'address_id'
    ];

    public static $rules = [
 //   	'name'    => array('required', 'min:2', 'max:64'),
 //       'country_id' => 'exists:countries,id',
 //   	'percent' => array('required', 'numeric', 'between:0,100')
        'firstname'        => 'required|min:2|max:32',
 //       'email' => 'sometimes|unique:contacts,email',

// Let the Controller decide if apply these rules...
//        'party_id' => 'exists:parties,id',
//        'customer_id' => 'exists:customers,id',
//        'address_id' => 'exists:addresses,id',
        ];


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l(get_called_class().'.'.$type, [], 'appmultilang');
            }

            return $list;
    }

    public function getTypeNameAttribute()
    {
            return l(get_called_class().'.'.$this->type, 'appmultilang');
    }


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

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id')
                   ->where('addressable_type', Customer::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
//    public function leads()
//    {
//        return $this->hasMany(Lead::class);
//    }
}
