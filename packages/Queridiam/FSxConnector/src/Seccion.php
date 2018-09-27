<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
	protected $table = 'F_SEC';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

	protected $guarded = ['CODSEC'];

	protected $primaryKey = 'CODSEC';
    protected $keyType = 'string';

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function familias()
    {
        return $this->hasMany(Familia::class, 'SECFAM', 'CODSEC')->orderby('DESFAM', 'asc');
    }
    
    public function category()
    {
        return $this->hasOne('\App\Category', 'reference_external', 'CODSEC')->where('parent_id', 0);
    }

}