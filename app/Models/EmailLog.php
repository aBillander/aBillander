<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{


    protected $fillable = [ 
                          'to', 'subject', 'body', 
                          'from', 'cc', 'bcc', 
                          'headers', 'attachments', 

                          'user_id' 
              ];

//    protected $appends = ['last_modified_at'];
    
    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getEmailDateAtAttribute()
    {
        return $this->created_at; 
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function userable()
    {
        return $this->morphTo();
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {

        if ($params['date_from'])
            // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('created_at', '>=', $params['date_from'].' 00:00:00');
        }

        if ($params['date_to'])
        {
            $query->where('created_at', '<=', $params['date_to']  .' 23:59:59');
        }


        if ( isset($params['email_from']) && trim($params['email_from']) != '' )
        {
            $query->where('from', 'LIKE', '%' . trim($params['email_from'] . '%'));
        }

        if ( isset($params['email_to']) && trim($params['email_to']) != '' )
        {
            $query->where('to', 'LIKE', '%' . trim($params['email_to'] . '%'));
        }


        if ( isset($params['subject']) && trim($params['subject']) != '' )
        {
            $query->where('subject', 'LIKE', '%' . trim($params['subject'] . '%'));
        }
        
        if ( isset($params['body']) && trim($params['body']) != '' )
        {
            $query->where('body', 'LIKE', '%' . trim($params['body'] . '%'));
        }

        return $query;
    }
}
