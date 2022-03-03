<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{

    public static $types = array(
            'partner', 
            'prescriptor',
            'reseller',
            'prospect',
            'agency',
            'developer',
            'other',
        );

//    protected $dates = ['deleted_at'];

//    protected $appends = ['percent'];
    
    protected $fillable = [ 'name_fiscal', 'name_commercial', 'type', 'identification', 
                            'email', 'phone', 'phone_mobile', 'address', 
                            'website', 'blocked', 'active', 'notes', 
                            'user_created_by_id', 'user_assigned_to_id', 'customer_id' ];

    public static $rules = [
        'name_fiscal'        => 'nullable|min:2|max:128',
        'name_commercial'    => 'required|min:2|max:64',
 //       'country_id' => 'exists:countries,id',
 //   	'percent' => array('required', 'numeric', 'between:0,100')
 //       'email' => 'sometimes|unique:parties,email',
    	];


    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                // $list[$type] = l(get_called_class().'.'.$type, [], 'appmultilang');
                $list[$type] = l(get_called_class().'.'.$type);
            }

            return $list;
    }

    public static function getTypeName( $type )
    {
            // return l(get_called_class().'.'.$type, [], 'appmultilang');
            return l(get_called_class().'.'.$type);
    }

    public function getTypeNameAttribute()
    {
            // return l(get_called_class().'.'.$this->type, 'appmultilang');
            return l(get_called_class().'.'.$this->type);
    }
	
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function contacts()
    {
        return $this->hasMany('App\Contact')->orderBy('active', 'desc');
    }
    
    public function leads()
    {
        return $this->hasMany('App\Lead');
    }

    public function createdby()
    {
        return $this->belongsTo('App\User', 'user_created_by_id');
	}

    public function assignedto()
    {
        return $this->belongsTo('App\User', 'user_assigned_to_id');
	}

    public function customer()
    {
        return $this->belongsTo('App\Customer');
	}
	
}
