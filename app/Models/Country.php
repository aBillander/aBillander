<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    
    public function hasState( $id )
    {
        return $this->hasMany(State::class, 'country_id')->where('id', intval($id))->first();
    }

    
    public function checkIdentification( $identification )
    {
        $method = 'checkIdentification_'.strtoupper($this->iso_code);
        
        if ( method_exists($this, $method) ) 
            return $this->{$method}( $identification );
        else
            return true;
    }
    
    public function checkIdentification_ES( $identification )
    {
        // Poor man...
        return preg_match("/^[a-zA-Z0-9-]+$/", $identification);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function states()
    {
        return $this->hasMany(State::class, 'country_id')->orderby('name', 'asc');
    }

}
