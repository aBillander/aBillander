<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model {

//    use SoftDeletes;

    protected $dates = ['mandate_date', 'first_recurring_date'];

	protected $fillable = ['iban', 'swift', 
                           'bank_name', 'ccc_entidad', 'ccc_oficina', 'ccc_control', 'ccc_cuenta',
						   'mandate_reference', 'mandate_date', 'first_recurring_date'];

	public static $rules = [
    	'bank_name'    			=> array('required', 'min:2', 'max:64'),
        'iban' => array('required', 'min:4', 'max:34'),
        'swift' => array('min:8', 'max:11'),
/*
        'bank_name'   => 'required|min:4|max:64',
        'ccc_entidad' => 'required|min:4|max:4',
        'ccc_oficina' => 'required|min:4|max:4',
        'ccc_control' => 'required|min:2|max:2',
        'ccc_cuenta'  => 'required|min:10|max:10',
*/

//        'mandate_reference' => 'max:35',
//        'mandate_date' => 'date',
//        'first_recurring_date' => 'date',
	];
    


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function valcuenta_bancaria($cuenta1,$cuenta2,$cuenta3,$cuenta4)
    {
        if (strlen($cuenta1)!=4)  return false;
        if (strlen($cuenta2)!=4)  return false;
        if (strlen($cuenta3)!=2)  return false;
        if (strlen($cuenta4)!=10) return false;

        if ($this->mod11_cuenta_bancaria("00".$cuenta1.$cuenta2)!=$cuenta3{0}) return false;
        if ($this->mod11_cuenta_bancaria($cuenta4)!=$cuenta3{1}) return false;
        
        return true;
    }

    //////////////////////////////////////////////////////////////////////////////////////

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
}