<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

    public static $statuses = array(
            'open',			// 
            'completed',	// 
            'failed',		//
        );

    protected $dates = ['lead_date', 'lead_end_date'];

//    protected $appends = ['percent'];
    
    protected $fillable = [ 'name', 'description', 'status', 'lead_date', 'lead_end_date', 'notes', 
                            'user_created_by_id', 'user_assigned_to_id', 'party_id', 'contact_id' 
    ];

    public static $rules = [
 //     'name'    => array('required', 'min:2', 'max:64'),
 //       'country_id' => 'exists:countries,id',
 //     'percent' => array('required', 'numeric', 'between:0,100')
        'name'        => 'required|min:2',
        'party_id' => 'exists:parties,id',
        ];



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                // $list[$status] = l(get_called_class().'.'.$status, [], 'appmultilang');
                $list[$status] = l(get_called_class().'.'.$status, 'leads');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            // return l(get_called_class().'.'.$status, [], 'appmultilang');
            return l(get_called_class().'.'.$status, 'leads');
    }

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            // return l(get_called_class().'.'.$this->status, 'appmultilang');
            return l(get_called_class().'.'.$this->status, 'leads');
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function leadlines()
    {
        return $this->hasMany('App\LeadLine');
    }
    
    // Alias
    public function lines()
    {
        return $this->leadlines();
    }

    public function createdby()
    {
        return $this->belongsTo('App\User', 'user_created_by_id');
	}

    public function assignedto()
    {
        return $this->belongsTo('App\User', 'user_assigned_to_id');
	}

    public function party()
    {
        return $this->belongsTo('App\Party');
	}

    public function contact()
    {
        return $this->belongsTo('App\Contact');
	}
	
}
