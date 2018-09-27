<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
	protected $table = 'F_FPA';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

	protected $guarded = ['CODFPA'];

	protected $primaryKey = 'CODFPA';
    protected $keyType = 'string';

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public static function codigo()
    {
        // return \App\Configuration::get('FSOL_TCACFG');
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function lineas()
    {
        // return $this->hasMany(LineaTarifa::class, 'TARLTA', 'CODTAR');
    }

}