<?php

use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;
// use Queridiam\WooCommerce\Facades\WooCommerce;

/*
|--------------------------------------------------------------------------
| Gorrino Web Routes "sandbox"
|--------------------------------------------------------------------------
|
| Quick & dirty!
|
*/


/* ********************************************************** */


Route::get('/woo', function () {
        $settings = [];

        // Get Configurations from WooCommerce Shop
        try {

            $groups = WooCommerce::get('settings'); // Array
        }

        catch( WooHttpClientException $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            abi_r( $e->getMessage() );

        }

        abi_r($groups);
});


Route::get('/home0', function () {
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

