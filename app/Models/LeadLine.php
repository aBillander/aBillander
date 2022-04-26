<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class LeadLine extends Model
{

    public static $statuses = array(
            'open',			// 
            'completed',	// 
        );

    protected $dates = ['start_date', 'due_date', 'finish_date'];

//    protected $appends = ['percent'];
    
    protected $fillable = [ 'name', 'description', 'status', 'start_date', 'due_date', 'finish_date', 'results', 'position', 
                            'user_created_by_id', 'user_assigned_to_id', 'lead_id' 
    ];

    public static $rules = [
 //     'name'    => array('required', 'min:2', 'max:64'),
 //       'country_id' => 'exists:countries,id',
 //     'percent' => array('required', 'numeric', 'between:0,100')
        'name'        => 'required|min:2',
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
                $list[$status] = l(get_called_class().'.'.$status, 'leadlines');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            // return l(get_called_class().'.'.$status, [], 'appmultilang');
            return l(get_called_class().'.'.$status, 'leadlines');
    }

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            // return l(get_called_class().'.'.$this->status, 'appmultilang');
            return l(get_called_class().'.'.$this->status, 'leadlines');
    }


    public function getIsOverdueAttribute()
    {
        if ( !$this->finish_date && ($this->status == 'open') )
            return $this->due_date < Carbon::now();

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function lead()
    {
        return $this->belongsTo(Lead::class);
	}

    public function createdby()
    {
        return $this->belongsTo(User::class, 'user_created_by_id');
	}

    public function assignedto()
    {
        return $this->belongsTo(User::class, 'user_assigned_to_id');
	}
}
