<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesRep extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
	
    protected $fillable = ['alias', 'identification', 'notes', 
    					   'commission_percent', 'max_discount_allowed', 'irpf', 'active'];

    public static $rules = array(
        'alias' => array('required|min:2|max:32'),
    	);


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function address()
    {
        return $this->hasOne('App\Address', 'owner_id')->where('model_name', '=', 'SalesRep');
    }
}