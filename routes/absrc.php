<?php

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

/* */

Route::group(['prefix' => 'absrc'], function ()
{
    // Auth routes here
    Route::get('/login', 'Auth\SalesRepLoginController@showLoginForm')->name('salesrep.login');
    Route::post('/login', 'Auth\SalesRepLoginController@login')->name('salesrep.login.submit');
    Route::post('/logout', 'Auth\SalesRepLoginController@salesrepLogout')->name('salesrep.logout');

    Route::get('/register', 'Auth\SalesRepRegisterController@showRegistrationForm')->name('salesrep.register');
    Route::post('/register', 'Auth\SalesRepRegisterController@register')->name('salesrep.register.submit');

// Password Reset Routes...
    Route::post('password/email', ['as' => 'salesrep.password.email', 'uses' => 'Auth\SalesRepForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset', ['as' => 'salesrep.password.request', 'uses' => 'Auth\SalesRepForgotPasswordController@showLinkRequestForm']);
    Route::post('password/reset', ['uses' => 'Auth\SalesRepResetPasswordController@reset']);
    Route::get('password/reset/{token}', ['as' => 'salesrep.password.reset', 'uses' => 'Auth\SalesRepResetPasswordController@showResetForm']);
});


/* ********************************************************** */


Route::group(['prefix' => 'absrc', 'namespace' => '\SalesRepCenter'], function ()
{
    // Sales Reps routes here

    Route::group(['middleware' =>  ['auth:salesrep', 'absrccontext']], function()
    {

        Route::get('/', 'SalesRepHomeController@index')->name('salesrep.dashboard');

    });
});

/* ********************************************************** */

/*
carriers                | carriers.index   | App\Http\Controllers\CarriersController@index
carriers                | carriers.store   | App\Http\Controllers\CarriersController@store
carriers/create         | carriers.create  | App\Http\Controllers\CarriersController@create
carriers/{carrier}      | carriers.destroy | App\Http\Controllers\CarriersController@destroy
carriers/{carrier}      | carriers.update  | App\Http\Controllers\CarriersController@update
carriers/{carrier}      | carriers.show    | App\Http\Controllers\CarriersController@show
carriers/{carrier}/edit | carriers.edit    | App\Http\Controllers\CarriersController@edit         
*/
