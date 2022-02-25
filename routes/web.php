<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return view('welcome');
    return view('home.home');
});

Route::get('/v', function () {
    return 'Laravel v'.abi_laravel_version() . ' (PHP v' . abi_php_version() . ')';
});

// Route::get('/home', 'HomeController@index')->name('home');
