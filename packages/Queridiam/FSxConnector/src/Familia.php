<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
	protected $table = 'F_FAM';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

	protected $guarded = ['CODFAM'];

	protected $primaryKey = 'CODFAM';
    protected $keyType = 'string';

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'SECFAM', 'CODSEC');
    }
    
    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'FAMART', 'CODFAM')->orderby('DESART', 'asc');
    }
    
    public function products()
    {
        return $this->hasManyThrough(
            'App\Product',
            Articulo::class,
            'FAMART', // Foreign key on users table...
            'reference', // Foreign key on posts table...
            'CODFAM', // Local key on countries table...
            'CODART' // Local key on users table...
        );

        return $this->hasOne('\App\Product', 'reference', 'CODART');
    }
    
    public function category()
    {
        $rel = $this->hasOne('\App\Category', 'reference_external', 'CODFAM');

        if ( \App\Configuration::isTrue('ALLOW_PRODUCT_SUBCATEGORIES') )
            return $rel->where('parent_id', '>', 0);
        else
            return $rel->where('parent_id',      0);
    }

}