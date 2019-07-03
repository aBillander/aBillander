<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ActivityLoggerLine;

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
        return $this->belongsTo('App\User');
    }
    
    public function userable()
    {
        return $this->morphTo();
    }
}
