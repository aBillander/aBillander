<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Action extends Model
{

    public static $statuses = array(
            'not_started',      // 
            'in_progress',      // 
            'completed',        // 
            'pending_input',    // 
            'deferred',         // 
        );

    public static $priorities = array(
            'low',      // 
            'medium',   // 
            'high',     // 
        );

    protected $dates = ['start_date', 'due_date', 'finish_date'];

//    protected $appends = ['percent'];
    
    protected $fillable = [ 'name', 'description', 'status', 'priority', 
                            'start_date', 'due_date', 'finish_date', 'results', 'position', 
                            'user_created_by_id', 'user_assigned_to_id', 'action_type_id', 
                            'sales_rep_id', 'contact_id', 'customer_id', 'lead_id' 
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
                $list[$status] = l(get_called_class().'.'.$status, 'actions');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            // return l(get_called_class().'.'.$status, [], 'appmultilang');
            return l(get_called_class().'.'.$status, 'actions');
    }

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            // return l(get_called_class().'.'.$this->status, 'appmultilang');
            return l(get_called_class().'.'.$this->status, 'actions');
    }


    public static function getPriorityList()
    {
            $list = [];
            foreach (self::$priorities as $type) {
                $list[$type] = l(get_called_class().'.'.$type, [], 'actions');
            }

            return $list;
    }

    public function getPriorityNameAttribute()
    {
            return l(get_called_class().'.'.$this->priority, 'actions');
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

    public function actiontype()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }

    public function salesrep()
    {
        return $this->belongsTo(SalesRep::class, 'sales_rep_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

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


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */


    public function scopeFilter($query, $params)
    {

        if (array_key_exists('date_from', $params) && $params['date_from'])
        {
            $query->where('due_date', '>=', $params['date_from'].' 00:00:00');
        }

        if (array_key_exists('date_to', $params) && $params['date_to'])
        {
            $query->where('due_date', '<=', $params['date_to']  .' 23:59:59');
        }

        if (array_key_exists('auto_direct_debit', $params) && $params['auto_direct_debit'] > 0)
        {
            $query->where('auto_direct_debit', '>', 0);
        }

        if (array_key_exists('auto_direct_debit', $params) && $params['auto_direct_debit'] == 0)
        {
            $query->where('auto_direct_debit', 0);
        }

        if (array_key_exists('status', $params) && $params['status'] && self::isStatus($params['status']))
        {
            $query->where('status', $params['status']);

        } else {

            $query->where('status', '<>', 'uncollectible');
        }

        if (array_key_exists('customer_id', $params) && $params['customer_id'])
        {
            $id = $params['customer_id'];

            $query->whereHas('customer', function ($query) use ($id) {
                    $query->where('id', $id);
                });
        }

        if (array_key_exists('supplier_id', $params) && $params['supplier_id'])
        {
            $id = $params['supplier_id'];

            $query->whereHas('supplier', function ($query) use ($id) {
                    $query->where('id', $id);
                });
        }

        if (array_key_exists('payment_type_id', $params) && $params['payment_type_id'])
        {
            $query->where('payment_type_id', $params['payment_type_id']);
        }

        if (isset($params['name']) && $params['name'] != '') {
            $name = $params['name'];

            $query->whereHas('customer', function ($query) use ($name) {
                    $query->where(function ($query1) use ($name) {
                        $query1->where('name_fiscal', 'LIKE', '%' . $name . '%')
                               ->OrWhere('name_commercial', 'LIKE', '%' . $name . '%');
                });

            });
        }

        if ( array_key_exists('amount', $params) && $params['amount']  != '' )
        {
            $query->where('amount', floatval( str_replace(',','.', $params['amount']) ));
        }

/*
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));
/ *
            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
* /
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['warehouse_id']) && $params['warehouse_id'] > 0 )
        {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }
*/
        return $query;
    }
}
