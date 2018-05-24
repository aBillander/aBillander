<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

	protected $fillable = ['name', 'model_name', 
						   'folder', 'file_name', 
						   'paper', 'orientation'
						  ];

    public static $rules = array(
        'name' => array('required', 'min:2', 'max:128'),
        'model_name' => array('required'),
        'file_name' => array('required'),
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerinvoices()
    {
        return $this->hasMany('App\Customerinvoice');
    }

}