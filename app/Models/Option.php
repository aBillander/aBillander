<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'name', 'position' ];

    public static $rules = array(
    	'name'     => array('required'),
        'position' => array('numeric')      // , 'min:0')   Allow negative in case starts on 0
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function optiongroup()
    {
        return $this->belongsTo('App\OptionGroup', 'option_group_id');
	}
    
    public function combinations()
    {
        return $this->belongsToMany('App\Combination')->withTimestamps();
    }
}