<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
	protected $table = 'F_TAR';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

	protected $guarded = ['CODTAR'];

	protected $primaryKey = 'CODTAR';
    protected $keyType = 'int';

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public static function tarifa_codigo()
    {
        return \App\Configuration::get('FSOL_TCACFG');
    }
    
    public static function tarifa_nombre()
    {
        return \DB::table( 'F_TAR' )->where('CODTAR', \App\Configuration::get('FSOL_TCACFG'))->first()->DESTAR;
    }
    
    public function precio( $art )
    {
        // Same as: return $art->precio( $this );

        $artlta = is_object( $art ) ?
                    $art :
                    Articulo::find($art);
        
        $piva = $artlta->tipoiva();

        $iintar = $this->IINTAR;
        $prelta = $this->lineas()->where('ARTLTA', $artlta->CODART)->first()->PRELTA;

        $price = new \App\Price($prelta, $iintar);
        $price->applyTaxPercent($piva);

        return $price;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function lineas()
    {
        return $this->hasMany(LineaTarifa::class, 'TARLTA', 'CODTAR');
    }
    
    public function pricelist()
    {
        return $this->hasOne('\App\PriceList', 'reference_external', 'CODTAR');
    }

}