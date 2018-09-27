<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Stock extends Model
{
	protected $table = 'F_STO';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

	protected $guarded = ['ARTSTO'];

	protected $primaryKey = 'ARTSTO';
    protected $keyType = 'string';


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Apply global scope
        static::addGlobalScope(new almacenScope);
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public static function almacen_codigo()
    {
        return \App\Configuration::get('FSOL_AUSCFG');
    }
    
    public static function almacen_nombre()
    {
        return \DB::table( 'F_ALM' )->where('CODALM', \App\Configuration::get('FSOL_AUSCFG'))->first()->NOMALM;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'ARTSTO', 'CODART');
    }

    
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

}



class almacenScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('ALMSTO', \App\Configuration::get('FSOL_AUSCFG'));
    }
}