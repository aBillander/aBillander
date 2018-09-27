<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class LineaTarifa extends Model
{
	protected $table = 'F_LTA';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

//	protected $guarded = ['CODFAM'];

//	protected $primaryKey = 'CODFAM';
//    protected $keyType = 'string';

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class, 'TARLTA', 'CODTAR');
    }
    
    public function articulo()
    {
        // return $this->hasMany(Articulo::class, 'FAMART', 'CODFAM')->orderby('DESART', 'asc');
    }

}