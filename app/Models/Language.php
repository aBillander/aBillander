<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

	protected $fillable = ['name', 'iso_code', 'language_code', 
                           'date_format_lite', 'date_format_full',
                           'date_format_lite_view', 'date_format_full_view', 
                           'active'];

    public static $rules = array(
    	'name'    			=> array('required', 'min:2', 'max:32'),
    	'iso_code'    		=> array('required', 'min:2', 'max:2'),
    	'language_code'		=> array('required', 'min:2', 'max:5'),
    	
        'date_format_lite'  => array('required', 'min:3', 'max:32'),
        'date_format_full'  => array('required', 'min:3', 'max:32'),
        
        'date_format_lite_view'  => array('required', 'min:3', 'max:32'),
        'date_format_full_view'  => array('required', 'min:3', 'max:32'),
    	);
    

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