<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\State as State;

class Country extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'name', 'iso_code', 'contains_states', 'active' ];

    public static $rules = array(
    	'name'     => array('required', 'min:2', 'max:64'),
    	'iso_code' => array('required', 'max:3'),
    	);


    /**
     * Normalize ISO Code on Model update
     * 
     */
    public function setIsoCodeAttribute($value)
    {
        $this->attributes['iso_code'] = strtoupper($value);
    }
	
    /**
     * Find ISO Code
     * 
     */
    public static function findByIsoCode($code)
    {
        return self::where('iso_code', $code)->first();
    }
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function states()
    {
        return $this->hasMany('App\State', 'country_id')->orderby('name', 'asc');
    }

}
