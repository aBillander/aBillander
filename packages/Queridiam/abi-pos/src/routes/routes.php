<?php

// use App\Http\Controllers\Auth\CashierNewPasswordController;
// use App\Http\Controllers\Auth\CashierPasswordResetLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* */

// https://blog.pusher.com/laravel-subdomain-routing/

// https://stackoverflow.com/questions/13647890/prevent-access-to-folder-of-subdomain-on-main-domain

// https://gist.github.com/kosinix/8267252

// RedirectMatch ^/subdomain/(.*)$ http://subdomain.mysite.com/$1

/*
RewriteEngine on
RewriteBase / 

#if not already blog.website.com
RewriteCond %{HTTP_HOST} !^blog\.website\.com$ [NC] 
#if request is for blog/, go to blog.website.com
RewriteRule ^blog/$ http://blog.website.com [L,NC,R=301]
*/

/* */

$posGroup =
[
    'prefix' => 'pos',
    'as' => 'pos::',
    'namespace' => 'Queridiam\\POS\\Http\\Controllers',
//    'middleware' => ['restrictIp', 'auth', 'context'],
//    'middleware' => ['poscontext'],
];

Route::group($posGroup, function () {

    Route::get('hello', function () {
        abi_r('Hello world of POS!'.' - '.route('pos::welcome').' - '.asset(''));
    })->name('welcome');

});

// https://www.youtube.com/watch?v=PqAaBo_I_a4


/* ********************************************************** */


// Not-Secure POS-Routes
Route::group($posGroup, function ()
{
    Route::group(['middleware' => ['web', 'guest:cashier', 'guestcontext']], function ()
    {
        Route::get('/login', 'Auth\CashierLoginController@showLoginForm')->name('cashier.login');
        Route::post('/login', 'Auth\CashierLoginController@login')->name('cashier.login.submit');
        Route::post('/logout', 'Auth\CashierLoginController@cashierLogout')->name('cashier.logout');

        Route::resource('pos', 'POSController')->only('create', 'store');

        Route::get('/get-product-suggestion', 'POSController@getProductSuggestion');

/*
        Route::get('/register', 'Auth\CustomerRegisterController@showRegistrationForm')->name('customer.register');
        Route::post('/register', 'Auth\CustomerRegisterController@register')->name('customer.register.submit');


        Route::get('forgot-password', [CustomerPasswordResetLinkController::class, 'create'])
                    ->name('customer.password.request');

        Route::post('forgot-password', [CustomerPasswordResetLinkController::class, 'store'])
                    ->name('customer.password.email');

        Route::get('reset-password/{token}', [CustomerNewPasswordController::class, 'create'])
                    ->name('customer.password.reset');

        Route::post('reset-password', [CustomerNewPasswordController::class, 'store'])
                    ->name('customer.password.update');

*/
/*
// Password Reset Routes...
    Route::post('password/email', ['as' => 'customer.password.email', 'uses' => 'Auth\CustomerForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset', ['as' => 'customer.password.request', 'uses' => 'Auth\CustomerForgotPasswordController@showLinkRequestForm']);
    Route::post('password/reset', ['uses' => 'Auth\CustomerResetPasswordController@reset']);
    Route::get('password/reset/{token}', ['as' => 'customer.password.reset', 'uses' => 'Auth\CustomerResetPasswordController@showResetForm']);
*/

    });

});


/* ********************************************************** */


// Secure POS-Routes 
Route::group($posGroup, function ()
{
    Route::group(['middleware' => ['auth:cashier', 'context', 'poscontext:cashier']], function ()
    {


    });

});


/* ********************************************************** */


// Secure Admin-Routes
Route::group($posGroup, function ()
{
    Route::group(['middleware' =>  ['restrictIp', 'web', 'auth', 'context']], function()
    {
        Route::resource('cashregisters', 'CashRegistersController');


    });

});


/* ********************************************************** */

