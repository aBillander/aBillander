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


Route::get('/langu', function () {
    // Changing The Default Language At Runtime
    App::setLocale('es');
	abi_r( l('Yes', 'layouts') );
});


/* ********************************************************** */




/* ********************************************************** */




/* ********************************************************** */

