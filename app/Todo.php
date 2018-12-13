<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

//    protected $dates = ['due_date'];

	protected $fillable = ['name', 'description', 'url', 'due_date', 'completed', 'user_id'];

    public static $rules = [
    	'name'    			=> 'required|min:2',
    	];
    
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function pending() 
    {
        return (int) self::where("completed", '0')->count();
    }

    public function isOverdue() 
    {
        return $this->due_date && ( \Carbon\Carbon::now()->toDateString() > $this->due_date );
    }

    public function shortUrl() 
    {
        return str_replace( url('/'), '', $this->url );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
}
