<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model {

//    use SoftDeletes;

    protected $dates = ['mandate_date', 'first_recurring_date'];

	protected $fillable = ['iban', 'swift', 
                           'bank_name', 'ccc_entidad', 'ccc_oficina', 'ccc_control', 'ccc_cuenta',
                           'suffix', 'creditorid',
						   'mandate_reference', 'mandate_date', 'first_recurring_date',
                           'notes',
                        ];

	public static $rules = [
    	'bank_name'    			=> array('required', 'min:2', 'max:64'),
        'iban' => array('required', 'min:4', 'max:34'),

        'suffix'   => 'sometimes|nullable|min:3|max:3',
//        'swift' => array('min:8', 'max:11'),

        'bank_name'   => 'required|min:4|max:64',
        'ccc_entidad' => 'required|min:4|max:4',
        'ccc_oficina' => 'required|min:4|max:4',
        'ccc_control' => 'required|min:2|max:2',
        'ccc_cuenta'  => 'required|min:10|max:10',


        'mandate_reference' => 'sometimes|nullable|max:35',
        'mandate_date' => 'sometimes|nullable|date',
//        'first_recurring_date' => 'date',
	];
    


    public static function boot()
    {
        parent::boot();


        static::deleting(function ($client)
        {
            // before delete() method call this
            $relations = [
                    'sepadirectdebits',
            ];

            // load relations
            $client->load( $relations );

            // Check Relations
            foreach ($relations as $relation) {
                # code...
                if ( $client->{$relation}->count() > 0 )
                    throw new \Exception( l('Bank Account has :relation', ['relation' => $relation], 'exceptions') );
            }

            // To do: manage models: Supplier, Party

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    // Calculate Iban for Spain (es)
    // A more general approach: https://github.com/globalcitizen/php-iban
    // https://www.proinf.net/permalink/calculo_del_iban_a_partir_del_ccc

    /**
     * Funcion para calcular IBAN es correcta
     * @param 1 parámetro: el código de cuenta bancaria completo ($ccc_entidad . $ccc_oficina . $ccc_control . $ccc_cuenta)
              4 parámetros: $ccc_entidad , $ccc_oficina , $ccc_control , $ccc_cuenta
     * @return string
     */
    public static function esIbanCalculator($ccc_entidad, $ccc_oficina = '', $ccc_control = '', $ccc_cuenta = '')
    {
        //
        $codigoPais = 'ES';
        $ccc = $ccc_entidad . $ccc_oficina . $ccc_control . $ccc_cuenta;

        $digitoControl =  self::getCodigoControl_IBAN($codigoPais, $ccc); 

        return $codigoPais.$digitoControl.$ccc;
    }

    public static function getCodigoControl_IBAN($codigoPais, $ccc)
    {
        //
        // $codigoPais = 'ES';
        // $ccc = $ccc_entidad . $ccc_oficina . $ccc_control . $ccc_cuenta;

          $pesos = array(
                 'A' => '10',
                 'B' => '11',
                 'C' => '12',
                 'D' => '13',
                 'E' => '14',
                 'F' => '15',
                 'G' => '16',
                 'H' => '17',
                 'I' => '18',
                 'J' => '19',
                 'K' => '20',
                 'L' => '21',
                 'M' => '22',
                 'N' => '23',
                 'O' => '24',
                 'P' => '25',
                 'Q' => '26',
                 'R' => '27',
                 'S' => '28',
                 'T' => '29',
                 'U' => '30',
                 'V' => '31',
                 'W' => '32',
                 'X' => '33',
                 'Y' => '34',
                 'Z' => '35', 
             );
          
          $dividendo = $ccc.$pesos[substr($codigoPais, 0 , 1)].$pesos[substr($codigoPais, 1 , 1)].'00'; 

          $digitoControl =  98 - bcmod($dividendo, '97');

          if(strlen($digitoControl)==1) $digitoControl = '0'.$digitoControl;

          return $digitoControl;
    }

    /**
     * Funcion para verificar si una cuenta IBAN es correcta
     * @param string $iban
     * @return boolean
     */
    public static function esCheckIBAN($iban)
    {
        if(strlen($iban)==24)
        {
            $codigoPais = strtoupper(substr($iban,0,2));

            if ($codigoPais != 'ES')
                return false;

            $digitoControl = self::getCodigoControl_IBAN($codigoPais, substr($iban,4));

            if($digitoControl==substr($iban,2,2))
                return true;
        }

        return false;

    }




    //////////////////////////////////////////////////////////////////////////////////////


    // https://es.wikipedia.org/wiki/C%C3%B3digo_cuenta_cliente
    public function valcuenta_bancaria($cuenta1,$cuenta2,$cuenta3,$cuenta4)
    {
        if (strlen($cuenta1)!=4)  return false;
        if (strlen($cuenta2)!=4)  return false;
        if (strlen($cuenta3)!=2)  return false;
        if (strlen($cuenta4)!=10) return false;

        if ($this->mod11_cuenta_bancaria("00".$cuenta1.$cuenta2)!=$cuenta3[0]) return false;
        if ($this->mod11_cuenta_bancaria($cuenta4)!=$cuenta3[1]) return false;
        
        return true;
    }

    // 

    protected function mod11_cuenta_bancaria($numero)
    {
        if (strlen($numero)!=10) return "?";

        $cifras = Array(1,2,4,8,5,10,9,7,3,6);
        $chequeo=0;

        for ($i=0; $i < 10; $i++)
            $chequeo += substr($numero,$i,1) * $cifras[$i];

        $chequeo = 11 - ($chequeo % 11);
        if ($chequeo == 11) $chequeo = 0;
        if ($chequeo == 10) $chequeo = 1;

        return $chequeo;
    }
    
    //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get IBAN with or without blanks.
     * @param type $blanks
     * @return type
     */
    public function iban_presenter($blanks = FALSE)
    {
        $iban = str_replace(' ', '', $this->iban);

        if ($blanks) {
            $txt = '';
            
            for ($i = 0; $i < strlen($iban); $i += 4) {
                $txt .= substr($iban, $i, 4) . ' ';
            }
            return $txt;
        }

        return $iban;
    }

    
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get all of the owning bank_accountable models.
     */
    public function bankaccountable()
    {
        return $this->morphTo();
    }

    public function sepadirectdebits()
    {
        return $this->hasMany('aBillander\SepaSpain\SepaDirectDebit', 'bank_account_id');
    }
}