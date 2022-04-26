<?php

/*
|--------------------------------------------------------------------------
| Gorrino gmdis Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('migratethis_gmdis', function()
{
	
	// 2022-04-19
	$date = '2022-04-19';

  \App\Configuration::updateValue('ASIGNA_CARRIER_ID', 2);

  die('OK - '.$date);

});


/* ********************************************************** */




/* ********************************************************** */

