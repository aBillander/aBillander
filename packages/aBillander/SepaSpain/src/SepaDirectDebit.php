<?php

namespace aBillander\SepaSpain;

use Illuminate\Database\Eloquent\Model;

use App\Sequence;

use App\Traits\ViewFormatterTrait;

// Direct Debit Bank Order or Remittance
class SepaDirectDebit extends Model
{

    use ViewFormatterTrait;

    public static $schemes = [
            'CORE',
            'B2B',
        ];

    public static $statuses = array(
            'pending',
            'validated',        // Girada (en trámite)
            'closed',           // Cargado en Cuenta (realizada)
        );

    protected $dates = ['document_date', 'validation_date', 'payment_date', 'posted_at'];
	
    protected $fillable = [ 'sequence_id', 'iban', 'swift', 'creditorid', 
                            'currency_iso_code', 'currency_conversion_rate', 

                            'scheme', 'status', 'notes',

                            'document_date', 'validation_date', 'payment_date', 'posted_at',

                            'total', 'bank_account_id',
                          ];

    public static $rules = array(
    	'document_date' => 'required|date',
//        'iban'          => 'required|min:4|max:34',
        'sequence_id'  => 'sometimes|exists:sequences,id',
        'bank_account_id'  => 'required',
        'scheme' => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getSchemeList()
    {
            $list = [];
            foreach (static::$schemes as $scheme) {
                $list[$scheme] = l(get_called_class().'.'.$scheme);
            }

            return $list;
    }

    public static function getSchemeName( $scheme )
    {
            return l(get_called_class().'.'.$scheme);
    }


    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status);
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l(get_called_class().'.'.$status);
    }

    
    public static function sequences()
    {
        $class = get_called_class();    // $class contains namespace!!!

        return ( new $class() )->sequenceList();
    }

    public function sequenceList()
    {
        return Sequence::listFor( SepaDirectDebit::class );
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function bankaccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'bank_account_id')
                   ->where('bank_accountable_type', \App\Company::class);
    }

    public function vouchers()
    {
        return $this->hasMany('App\Payment', 'bank_order_id');
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
