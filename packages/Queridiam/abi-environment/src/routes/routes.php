<?php

$envmanagerGroup =
[
    'prefix' => 'envmanager',
    'as' => 'envmanager::',
    'namespace' => 'Queridiam\\EnvManager\\Controllers',
    'middleware' => ['web', 'auth', 'context'],
];

Route::group($envmanagerGroup, function () {

    Route::get('hello', function () {
        abi_r('Hello world!');
    })->name('welcome');
    
    
    // Route::resource('/', 'EnvManagerController')->names('envkeys');

    Route::get('/', 'EnvManagerController@index')->name('index');
    Route::post('/', 'WelcomeController@setLocale')->name('update');

});