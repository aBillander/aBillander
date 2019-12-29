<?php

namespace aBillander\SepaSpain;

use Illuminate\Database\Eloquent\Model;

use App\Sequence;

use AbcAeffchen\SepaUtilities\SepaUtilities;
use AbcAeffchen\Sephpa\SephpaDirectDebit;

use App\Traits\ViewFormatterTrait;

// Direct Debit Bank Order or Remittance
class SepaDirectDebit extends Model
{

    use ViewFormatterTrait;

    protected $error_code    =  0;
    protected $error_message = '';

    public static $schemes = [
            'CORE',
            'B2B',
        ];

    public static $statuses = array(
            'pending',
            'confirmed',        // Girada (en trámite)
            'closed',           // Cargado en Cuenta (realizada)
        );

    protected $dates = ['document_date', 'validation_date', 'payment_date', 'posted_at'];
	
    protected $fillable = [ 'sequence_id', 'iban', 'swift', 'creditorid', 
                            'currency_iso_code', 'currency_conversion_rate', 

                            'scheme', 'status', 'onhold', 'group_vouchers', 'discount_dd', 'notes',

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

    public function updateTotal()
    {
         $total = $this->vouchers()->where('status', '<>', 'bounced')->sum('amount');

         return $this->update( ['total' => $total] );
    }

    public function nbrItems()
    {
        return $this->vouchers()->count();
    }



    public static function getSchemeList()
    {
            $list = [];
            foreach (static::$schemes as $scheme) {
                $list[$scheme] = l(get_called_class().'.'.$scheme, 'sepasp');
            }

            return $list;
    }

    public static function getSchemeName( $scheme )
    {
            return l(get_called_class().'.'.$scheme, 'sepasp');
    }


    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, 'sepasp');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l(get_called_class().'.'.$status, 'sepasp');
    }

    public function getStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->status, 'sepasp');
    }

    public function checkStatus()
    {
        if ( ( $this->vouchers()->count() > 0 ) && ( $this->vouchers()->where('status', 'pending')->count() == 0 ) )
            $this->close();

        $this->updateTotal();

        return true;
    }

    public function confirm()
    {
        // Can I?
        if ( $this->status == 'closed' ) return false;

        // onhold?
        if ( $this->onhold ) return false;


        $this->status = 'confirmed';
        $this->validation_date = \Carbon\Carbon::now();

        $this->save();

        return true;
    }

    public function close()
    {
        // Can I ...?
        if ( $this->status == 'closed' ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Do stuf...
        $this->status = 'closed';
        $this->payment_date = \Carbon\Carbon::now();

        $this->save();

        return true;
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
    


    /*
    |--------------------------------------------------------------------------
    | SEPA XML :: File
    |--------------------------------------------------------------------------
    */

    // https://github.com/AbcAeffchen/Sephpa
    // https://github.com/AbcAeffchen/SepaUtilities
    // https://github.com/AbcAeffchen/SepaDocumentor

    // http://www.clubdelphi.com/foros/showthread.php?p=491802
    // http://www.sepaeditor.com/es/Gegenstand-von-SEPAeditor.html
    // http://www.infosepa.es/utilidades.aspx

    // https://www.mobilefish.com/services/sepa_xml_validation/sepa_xml_validation.php

    public function toXML()
    {
        // Comprobar y sanear valores, permite evitar validación de IBAN, útil para pruebas con IBANs falsos
        $checkAndSanitize = FALSE;

        // generate a SepaDirectDebit object (pain.008.001.02).
        $paymentInfoId = \App\Context::getContext()->company->identification . '_' . $this->document_reference;

        if ( $this->discount_dd > 0 )
            $paymentInfoId = 'FSDD' . $paymentInfoId;

        /**
         * normal direct debit : LOCAL_INSTRUMENT_CORE_DIRECT_DEBIT = 'CORE';
         * urgent direct debit : LOCAL_INSTRUMENT_CORE_DIRECT_DEBIT_D_1 = 'COR1';
         * business direct debit : LOCAL_INSTRUMENT_BUSINESS_2_BUSINESS = 'B2B';
         */
        switch ( $this->scheme ) {
            case 'B2B':
                # code...
                $localInstrument = SepaUtilities::LOCAL_INSTRUMENT_BUSINESS_2_BUSINESS;
                break;
            
            case 'CORE':
                # code...
                // break;
            
            default:
                # code...
                $localInstrument = SepaUtilities::LOCAL_INSTRUMENT_CORE_DIRECT_DEBIT;
                break;
        }

        /**
         * first direct debit : SEQUENCE_TYPE_FIRST = 'FRST';
         * recurring direct debit : SEQUENCE_TYPE_RECURRING = 'RCUR';
         * one time direct debit : SEQUENCE_TYPE_ONCE = 'OOFF';
         * final direct debit : SEQUENCE_TYPE_FINAL = 'FNAL';
         */
        $sequenceType = SepaUtilities::SEQUENCE_TYPE_RECURRING;

        // Start the whole thing
        $collectables = $this->vouchers()->where('status', 'pending')->get();

        // Maybe a waste of time? Lets see:
        if ( $collectables->count() == 0 )
        {
            $code = 10;
            $message = 'No se han encontrado recibos pendientes de pago';

            $this->setError($code, $message);

            return false;
        }
        
        // Let's do some sorting
        $collectables = $collectables->groupBy(function ($item, $key) {
            // does not like Carbon Object as a key, only strinfs, please:
            return $item->due_date->format('Y-m-d');
        });


        // Lets rock
        $directDebitFile = new SephpaDirectDebit(
                                         $this->sanitize_name(\App\Context::getContext()->company->name_fiscal),
                                         $paymentInfoId,        // Download file is named afther this value
                                         SephpaDirectDebit::SEPA_PAIN_008_001_02,
                                         $checkAndSanitize
                                     );

        // $collectionData template
        $collectionData = [
            // needed information about the payer
                'pmtInfId'      => $paymentInfoId,          // ID of the payment collection
                'lclInstrm'     => $localInstrument,
                'seqTp'         => $sequenceType,
                'cdtr'          => $this->sanitize_name(\App\Context::getContext()->company->name_fiscal),      // (max 70 characters)
                'iban'          => $this->sanitize_iban( $this->iban ),            // IBAN of the Creditor
                'bic'           => $this->swift,           // BIC of the Creditor
                'ci'            => $this->calculateCreditorID( \App\Context::getContext()->company, $this->bankaccount ),    // Creditor-Identifier
            // optional
                'ccy'           => $this->currency_iso_code ?: 'EUR',                   // Currency. Default is 'EUR'
//                'btchBookg'     => 'true',                  // BatchBooking, only 'true' or 'false'
                //'ctgyPurp'      => ,                      // Do not use this if you not know how. For further information read the SEPA documentation
//                'ultmtCdtr'     => 'Ultimate Creditor Name',// just an information, this do not affect the payment (max 70 characters)
//                'reqdColltnDt'  => '2013-11-25'             // Date: YYYY-MM-DD. Due date for ALL vouchers inside
        ];

        // Each group will be a Collection (keyed with "due_date")
        foreach ($collectables as $key => $collection) 
        {
            // Need to group vouchers?
            if ( $this->group_vouchers > 0 )
            {
                // Let's do some sorting (by customer, this time)
                $sorted_collection = $collection->groupBy(function ($item, $key) {
                    return $item->paymentorable_id;
                });

                foreach ($sorted_collection as $kkey => $group) {
                    # code...
                    if ( $group->count() <= 1 )
                        continue;

                    // Group vouchers by Customer into one
                    // https://stackoverflow.com/questions/54522098/group-collection-by-multiple-column-laravel
                    $grouped = $group->reduce(function ($carry, $item) {

                            if(empty($carry[$item->paymentorable_id])){ //Not on the final array
                               //Create it
                               $carry[$item->paymentorable_id] = $item;
                            } else { //Exists, then update >paymentorable_id adding amount
                               $carry[$item->paymentorable_id]->amount += $item->amount; 
                            }

                            return $carry;
                    }, []);

                    $sorted_collection->put($kkey, collect($grouped));
                }

                $collection = $sorted_collection->flatten(1);
            }

            // at least one in every SEPA file. No limit.
            $collectionData['pmtInfId']     = $paymentInfoId.'-'.$key;
            $collectionData['reqdColltnDt'] = $key;

            $directDebitCollection = $directDebitFile->addCollection( $collectionData );

            // Add Vouchers now
            foreach ($collection as $voucher) {
                # code...

                // Maybe a waste of time? Lets see:
                if ( !optional($voucher->customer)->bankaccount )
                {
                    $code = 20;
                    $message = 'El Cliente ['.$voucher->customer->id.'] '.$voucher->customer->name_fiscal.' no tiene cuenta bancaria asociada';

                    $this->setError($code, $message);

                    return false;
                }


                if ( $voucher->amount <= 0.0 )
                {
                    $code = 20;
                    $message = 'El subtotal para el Cliente ['.$voucher->customer->id.'] '.$voucher->customer->name_fiscal.' es negativo';

                    $this->setError($code, $message);

                    return false;
                }



                $payment = [
                // needed information about the 
                    'pmtId'         => $voucher->reference,     // ID of the payment (EndToEndId)
                    'instdAmt'      => $voucher->amount,                    // amount
    //                'mndtId'        => 'Mandate-Id',            // Mandate ID
    //                'dtOfSgntr'     => '2010-04-12',            // Date of signature
                    'mndtId'        => $voucher->reference,            // Mandate ID
                    'dtOfSgntr'     => $voucher->created_at->toDateString(),            // Date of signature
    //                'bic'           => $voucher->customer->bankaccount->swift,           // BIC of the Debtor
                    'dbtr'          => $this->sanitize_name( $voucher->customer->name_fiscal ),        // (max 70 characters)
                    'iban'          => $this->sanitize_iban( $voucher->customer->bankaccount->iban ),     // IBAN of the Debtor
                // optional
    //                'amdmntInd'     => 'false',                 // Did the mandate change
                    //'elctrncSgntr'  => 'tests',                  // do not use this if there is a paper-based mandate
    //                'ultmtDbtr'     => 'Ultimate Debtor Name',  // just an information, this do not affect the payment (max 70 characters)
                    //'purp'        => ,                        // Do not use this if you not know how. For further information read the SEPA documentation
    //                'rmtInf'        => 'Remittance Information',// unstructured information about the remittance (max 140 characters)
                    'rmtInf'        => $voucher->reference,
                    // only use this if 'amdmntInd' is 'true'. at least one must be used
    //                'orgnlMndtId'           => 'Original-Mandat-ID',
    //                'orgnlCdtrSchmeId_nm'   => 'Creditor-Identifier Name',
    //                'orgnlCdtrSchmeId_id'   => 'DE98AAA09999999999',
    //                'orgnlDbtrAcct_iban'    => 'DE87200500001234567890',// Original Debtor Account
    //                'orgnlDbtrAgt'          => 'SMNDA'          // only 'SMNDA' allowed if used
                ];

                if (!empty($voucher->fmandato)) {
                    // $payment['dtOfSgntr'] = date('Y-m-d', strtotime($voucher->fmandato));
                }

                // So far, so good
                $directDebitCollection->addPayment( $payment );
            }
        }

 
 /* Old (not so good) stuff       
        $directDebitFile = new SephpaDirectDebit(
                                         $this->sanitize_name(\App\Context::getContext()->company->name_fiscal),
                                         $paymentInfoId,        // Download file is named afther this value
                                         SephpaDirectDebit::SEPA_PAIN_008_001_02,
                                         $collectionData,
                                         [],
                                         $checkAndSanitize
                                     );


        // at least one in every DirectDebitCollection. No limit.
        $collectables = $this->vouchers()->where('status', 'pending')->get();

        // Maybe a waste of time? Lets see:
        if ( $collectables->count() == 0 )
            return false;

        foreach ( $collectables as $voucher ) {
            # code...

            // Maybe a waste of time? Lets see:
            if ( !optional($voucher->customer)->bankaccount )
                return false;



            $payment = [
            // needed information about the 
                'pmtId'         => $voucher->reference,     // ID of the payment (EndToEndId)
                'instdAmt'      => $voucher->amount,                    // amount
//                'mndtId'        => 'Mandate-Id',            // Mandate ID
//                'dtOfSgntr'     => '2010-04-12',            // Date of signature
                'mndtId'        => $voucher->reference,            // Mandate ID
                'dtOfSgntr'     => $voucher->created_at->toDateString(),            // Date of signature
//                'bic'           => $voucher->customer->bankaccount->swift,           // BIC of the Debtor
                'dbtr'          => $this->sanitize_name( $voucher->customer->name_fiscal ),        // (max 70 characters)
                'iban'          => $this->sanitize_iban( $voucher->customer->bankaccount->iban ),     // IBAN of the Debtor
            // optional
//                'amdmntInd'     => 'false',                 // Did the mandate change
                //'elctrncSgntr'  => 'tests',                  // do not use this if there is a paper-based mandate
//                'ultmtDbtr'     => 'Ultimate Debtor Name',  // just an information, this do not affect the payment (max 70 characters)
                //'purp'        => ,                        // Do not use this if you not know how. For further information read the SEPA documentation
//                'rmtInf'        => 'Remittance Information',// unstructured information about the remittance (max 140 characters)
                'rmtInf'        => $voucher->reference,
                // only use this if 'amdmntInd' is 'true'. at least one must be used
//                'orgnlMndtId'           => 'Original-Mandat-ID',
//                'orgnlCdtrSchmeId_nm'   => 'Creditor-Identifier Name',
//                'orgnlCdtrSchmeId_id'   => 'DE98AAA09999999999',
//                'orgnlDbtrAcct_iban'    => 'DE87200500001234567890',// Original Debtor Account
//                'orgnlDbtrAgt'          => 'SMNDA'          // only 'SMNDA' allowed if used
            ];

            if (!empty($voucher->fmandato)) {
                // $payment['dtOfSgntr'] = date('Y-m-d', strtotime($voucher->fmandato));
            }

            $directDebitFile->addPayment( $payment );

            // abi_r($voucher);
        }
*/
        // $directDebitFile->store(__DIR__);

        // $directDebitFile->download();

        
        
        


        // abi_r($directDebitFile, true);

        return $directDebitFile;
    }

    public function sanitize_name($name)
    {
        $from = ['&', 'ñ', 'Ñ', 'ç', 'Ç'];
        $to = ['&amp;', 'n', 'N', 'c', 'C'];
        return substr(str_replace($from, $to, $name), 0, 70);
    }

    public function calculateCreditorID( \App\Company $company = null, $bankaccount = null )
    {
        // https://inza.wordpress.com/2013/10/25/como-preparar-los-mandatos-sepa-identificador-del-acreedor/

        if ($company == null)
            $company = \App\Context::getContext()->company;

        if ($bankaccount == null)
            $bankaccount = \App\Context::getContext()->company->bankaccount;

        if ( $bankaccount->creditorid )
            return $bankaccount->creditorid;


        $suffix = $bankaccount->suffix ?: '000';

        $creditorid = '';

        if ($company == null)
            return $creditorid;

        // Country ISO Code
        $country = $company->address->country;
        $codiso = $country->iso_code;

        // Company Identification
        $identification = str_replace(array(' ', '-'), array('', ''), strtoupper($company->identification));

        // Calculate Control Digits
        $cif_aux = $this->letters2numbers($identification . $codiso . '00');
        $total = 98 - ($cif_aux % 97);

        $creditorid = $codiso . sprintf('%02s', $total) . $suffix . $identification;

        return $creditorid;
    }

    private function letters2numbers($txt)
    {
        $data = array(
            'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17,
            'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25,
            'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33,
            'Y' => 34, 'Z' => 35
        );

        $nuevo = '';
        $i = 0;
        while ($i < strlen($txt)) {
            $t = substr($txt, $i, 1);

            if (isset($data[$t])) {
                $nuevo .= $data[$t];
            } else {
                $nuevo .= $t;
            }

            $i++;
        }

        return $nuevo;
    }

    /**
     * Get IBAN with or without blanks.
     * @param type $blanks
     * @return type
     */
    public function sanitize_iban($iban, $blanks = FALSE)
    {
        $iban = str_replace(' ', '', $iban);

        if ($blanks) {
            $txt = '';
            
            for ($i = 0; $i < strlen($iban); $i += 4) {
                $txt .= substr($iban, $i, 4) . ' ';
            }
            return $txt;
        }

        return $iban;
    }


/* *********************************************************************************************** */

    private function setError($code, $message)
    {
        $this->error_code    = $code;
        $this->error_message = $message;
    }


    public function getErrorMessage()
    {
        $message = $this->error_message ?? 'Unknown';

        return  $message;
    }
}
