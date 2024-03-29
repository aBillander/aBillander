<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    protected $fillable = [ 'name', 'alias',
        ];

    public static $rules = [
            'alias'   => array('required', 'min:2', 'max:32'),
            'name'    => array('required', 'min:2', 'max:128'),
        ];
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
}
