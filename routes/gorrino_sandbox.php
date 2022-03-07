<?php

/*
|--------------------------------------------------------------------------
| Gorrino Web Routes "sandbox"
|--------------------------------------------------------------------------
|
| Quick & dirty!
|
*/


/* ********************************************************** */


Route::get('/home', function () {
    // 
    return view('home');
});

Route::get('/soon', function () {
    // 
    return view('soon');
});


/* ********************************************************** */


Route::get('/invu', function () {
    $id=1182;
    $invu = \App\Models\CustomerInvoice::find($id);
    abi_r( $invu->leftAscriptions );
    abi_r( '**********************************' );
    abi_r( $invu->leftShippingSlips() );
});


Route::get('/langu', function () {
    // Changing The Default Language At Runtime
    App::setLocale('es');
    abi_r( l('Yes', 'layouts') );
});


/* ********************************************************** */




/* ********************************************************** */




/* ********************************************************** */

