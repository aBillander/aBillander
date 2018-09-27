<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
	protected $table = 'F_ART';

	// protected $dates = ['deleted_at'];
	// protected $fillable = [ 'name', 'iso_code', 'active', 'country_id' ];
	// public static $rules = [];

	protected $guarded = ['CODART'];

	protected $primaryKey = 'CODART';
	protected $keyType = 'string';

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
	
	public function tipoiva() {
	// Calcular IVA function tiposiva($tipo)   // definida en func.php

	switch ($this->TIVART){
            case 0:
                  return \App\Configuration::get('FSOL_PIV1CFG');  // 16.0 normalmente, etc.
                  break;
            case 1:
                  return \App\Configuration::get('FSOL_PIV2CFG');
                  break;
            case 2:
                  return \App\Configuration::get('FSOL_PIV3CFG');
                  break; 
            case 3:
                  return 0.0;         // Exento
                  break; 
            default:
                  return -1;
                  break; 
           }   

	}
    
    public function precio( $tar = null )
    {
        $tar = ($tar !== null ?: \App\Configuration::get('FSOL_TCACFG'));

        $tar = is_object( $tar ) ?:
                    Tarifa::find($tar);

        $piva = $this->tipoiva();

        $iintar = $tar->IINTAR;
        $prelta = $tar->lineas()->where('ARTLTA', $this->CODART)->first()->PRELTA;

        $price = new \App\Price($prelta, $iintar);
        $price->applyTaxPercent($piva);

        return $price;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function familia()
    {
        return $this->belongsTo(Familia::class, 'FAMART', 'CODFAM');
    }
    
    public function stock()
    {
        return $this->hasOne(Stock::class, 'ARTSTO', 'CODART');
//        	->where('ALMSTO', \App\Configuration::get('FSOL_AUSCFG'));

//        $st = Stock::where('ALMSTO', \App\Configuration::get('FSOL_AUSCFG'))->find($this->CODART);

//        return $st->ACTSTO;
    }
    
    public function stock_actual()
    {
        return $this->stock->ACTSTO;
    }
    
    public function product()
    {
        return $this->hasOne('\App\Product', 'reference', 'CODART');
    }

}