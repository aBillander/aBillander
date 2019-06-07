<?php

namespace aBillander\SepaSpain;

use Illuminate\Database\Eloquent\Model;

class SepaDirectDebit extends Model
{

    public static $local_instruments = [
            'CORE',
            'B2B',
        ];

    protected $dates = ['document_date', 'validation_date', 'payment_date', 'posted_at'];
	
    protected $fillable = [ 'sequence_id', 'iban', 'swift', 'creditorid', 
                            'currency_iso_code', 'currency_conversion_rate', 

                            'local_instrument', 'status', 

                            'document_date', 'validation_date', 'payment_date', 'posted_at',

                            'total', 'bank_account_id',
                          ];

    public static $rules = array(
    	'document_date' => 'required|date',
//        'iban'          => 'required|min:4|max:34',
        'bank_account_id'  => 'required',
        'local_instrument' => 'required',
        '' => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function calculateProductionOrders()
    {
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function bankaccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'bank_account_id')
                   ->where('bank_accountable_type', SepaDirectDebit::class);
    }
    


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsOpen($query)
    {
        return $query->where( 'due_date', '>=', \Carbon\Carbon::now()->toDateString() );
    }
}
