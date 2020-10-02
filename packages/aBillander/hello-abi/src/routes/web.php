<?php

// https://wisdmlabs.com/blog/create-package-laravel/

// github.com/gocanto/gocanyo-pkg


Route::group([

    'middleware' =>  ['web', 'auth', 'context'],
	'namespace' => 'aBillander\Installer\Http\Controllers',
	'prefix'    => 'hello-abi'

], function () {

    //

    Route::get('/hello', function () {

        return abi_r([
        
                'facebook_id' => 2234554654665654,
        
                'facebook_secret_key' => '5fdgjnkjndfkgj897644358vtjktret',
        
                'callback_url' => 'https://think201.com/facebook_callback',
        
                ]);

    });


/*
    Route::resource('directdebits', 'SepaDirectDebitsController')->names('sepasp.directdebits');
    Route::get('directdebits/{id}/xml', 'SepaDirectDebitsController@exportXml')->name('sepasp.directdebit.xml');

    Route::get( 'directdebits/voucher/{id}/add', 'SepaDirectDebitsController@addVoucherForm')->name('sepasp.directdebit.add.voucher.form');
    Route::post('directdebits/voucher/add',      'SepaDirectDebitsController@addVoucher'    )->name('sepasp.directdebit.add.voucher'     );

*/

});
