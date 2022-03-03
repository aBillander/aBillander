<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    //

    protected $appends = ['full_name'];
    
    protected $fillable = [ 'tool_type', 'name', 'reference', 'barcode', 

    						'description', 'location',		//  location 	varchar(64)
                          ];

    public static $rules = [
    						'name'    => 'required|min:2|max:128',
                           ];

    
        
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        $value = '[' . $this->reference . '] ' . $this->name;

        return $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Mmmmm! Should be Many to Many, boy!
    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
