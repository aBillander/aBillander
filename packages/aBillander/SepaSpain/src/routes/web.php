<?php

use AbcAeffchen\SepaUtilities\SepaUtilities;
use AbcAeffchen\Sephpa\SephpaDirectDebit;

// github.com/gocanto/gocanyo-pkg


Route::group([

    'middleware' =>  ['web', 'auth', 'context'],
	'namespace' => 'aBillander\SepaSpain\Http\Controllers',
	'prefix'    => 'sepasp'

], function () {

    Route::resource('directdebits', 'SepaDirectDebitsController')->names('sepasp.directdebits');
    Route::get('directdebits/{id}/xml', 'SepaDirectDebitsController@exportXml')->name('sepasp.directdebit.xml');

    Route::get( 'directdebits/voucher/{id}/add', 'SepaDirectDebitsController@addVoucherForm')->name('sepasp.directdebit.add.voucher.form');
    Route::post('directdebits/voucher/add',      'SepaDirectDebitsController@addVoucher'    )->name('sepasp.directdebit.add.voucher'     );




    

//	Route::get('/', 'WooConfigurationKeysController@hello');

	Route::get('/', function () {

 // generate a SepaDirectDebit object (pain.008.002.02).
$collectionData = [
                    // needed information about the payer
                        'pmtInfId'      => 'PaymentID-1235',        // ID of the payment collection
                        'lclInstrm'     => SepaUtilities::LOCAL_INSTRUMENT_CORE_DIRECT_DEBIT,
                        'seqTp'         => SepaUtilities::SEQUENCE_TYPE_RECURRING,
                        'cdtr'          => 'Name of Creditor',      // (max 70 characters)
                        'iban'          => 'DE87200500001234567890',// IBAN of the Creditor
                        'bic'           => 'BELADEBEXXX',           // BIC of the Creditor
                        'ci'            => 'DE98ZZZ09999999999',    // Creditor-Identifier
                    // optional
                        'ccy'           => 'EUR',                   // Currency. Default is 'EUR'
                        'btchBookg'     => 'true',                  // BatchBooking, only 'true' or 'false'
                        //'ctgyPurp'      => ,                      // Do not use this if you not know how. For further information read the SEPA documentation
                        'ultmtCdtr'     => 'Ultimate Creditor Name',// just an information, this do not affect the payment (max 70 characters)
                        'reqdColltnDt'  => '2013-11-25'             // Date: YYYY-MM-DD
];

$directDebitFile = new SephpaDirectDebit('Initiator Name', 'MessageID-1235',
                                         SephpaDirectDebit::SEPA_PAIN_008_003_02,
                                         $collectionData);

// at least one in every SEPA file. No limit.

// at least one in every DirectDebitCollection. No limit.
$directDebitFile->addPayment([
                    // needed information about the 
                        'pmtId'         => 'TransferID-1235-1',     // ID of the payment (EndToEndId)
                        'instdAmt'      => 2.34,                    // amount
                        'mndtId'        => 'Mandate-Id',            // Mandate ID
                        'dtOfSgntr'     => '2010-04-12',            // Date of signature
                        'bic'           => 'BELADEBEXXX',           // BIC of the Debtor
                        'dbtr'          => 'Name of Debtor',        // (max 70 characters)
                        'iban'          => 'DE87200500001234567890',// IBAN of the Debtor
                    // optional
                        'amdmntInd'     => 'false',                 // Did the mandate change
                        //'elctrncSgntr'  => 'tests',                  // do not use this if there is a paper-based mandate
                        'ultmtDbtr'     => 'Ultimate Debtor Name',  // just an information, this do not affect the payment (max 70 characters)
                        //'purp'        => ,                        // Do not use this if you not know how. For further information read the SEPA documentation
                        'rmtInf'        => 'Remittance Information',// unstructured information about the remittance (max 140 characters)
                        // only use this if 'amdmntInd' is 'true'. at least one must be used
                        'orgnlMndtId'           => 'Original-Mandat-ID',
                        'orgnlCdtrSchmeId_nm'   => 'Creditor-Identifier Name',
                        'orgnlCdtrSchmeId_id'   => 'DE98AAA09999999999',
                        'orgnlDbtrAcct_iban'    => 'DE87200500001234567890',// Original Debtor Account
                        'orgnlDbtrAgt'          => 'SMNDA'          // only 'SMNDA' allowed if used
                     ]);
		

		$directDebitFile->download();


		// return ['Hello', 'world!'];
	});

});
