<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionGroup extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'name', 'public_name', 'position', ];

    public static $rules = array(
    	'name'     => 'required|unique:option_groups,name,',
        'position' => 'numeric'                                     // , 'min:0')   Allow negative in case starts on 0
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function options()
    {
        return $this->hasMany('App\Option', 'option_group_id')->orderby('position', 'asc');
    }

}