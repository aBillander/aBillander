<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!

php artisan route:clear
php artisan route:cache

|
*/


/* ********************************************************** */


// Route::get('/', 'WelcomeController@index');     // ->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');



/* ********************************************************** */


require __DIR__.'/auth.php';


/* ********************************************************** */


if (file_exists(__DIR__.'/common.php')) {
    include __DIR__.'/common.php';
}


/* ********************************************************** */


require __DIR__.'/web_5.php';


/* ********************************************************** */


if (file_exists(__DIR__.'/gorrino_routes.php')) {
    include __DIR__.'/gorrino_routes.php';
}


/* ********************************************************** */

