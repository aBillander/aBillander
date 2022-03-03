<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkCenter extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['alias', 'name', 'notes', 'active'];

    // Add your validation rules here
    public static $rules = array(
        'alias' => 'required|min:2|max:32',
    	);


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    //
    
}