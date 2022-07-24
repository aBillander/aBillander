<?php

$posGroup =
[
    'prefix' => 'pos',
    'as' => 'pos::',
    'namespace' => 'Queridiam\\POS\\Http\\Controllers',
//    'middleware' => ['web', 'auth', 'context'],
];

Route::group($posGroup, function () {

    Route::get('hello', function () {
        abi_r('Hello world of POS!'.' - '.route('pos::welcome').' - '.asset(''));
    })->name('welcome');
    
    
    // Route::resource('/', 'EnvManagerController')->names('envkeys');

    Route::get('/', 'EnvManagerController@envkeys')->name('envkeys');
    Route::post('/', 'EnvManagerController@envkeysUpdate')->name('envkeys.update');

});