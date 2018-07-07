<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];

    public static $rules = array(
    	'name'     => array('required', 'min:2', 'max:64'),
    	'iso_code' => array('max:7'),
    	);

    
    /**
     * Find ISO Code
     * 
     */
    public static function findByIsoCode( $state = '', $country = '' )
    {
        if ($country)
            $code = (strpos($state, '-') ? '' : $country.'-').$state;
        else
            $code = $state;

        return self::where('iso_code', $code)->first();
    }
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
	}
}
