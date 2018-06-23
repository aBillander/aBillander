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

Route::group(['prefix' => 'abcc'], function ()
{
    Route::get('/login', 'Auth\CustomerLoginController@showLoginForm')->name('customer.login');
    Route::post('/login', 'Auth\CustomerLoginController@login')->name('customer.login.submit');
    Route::post('/logout', 'Auth\CustomerLoginController@customerLogout')->name('customer.logout');

// Password Reset Routes...
    Route::post('password/email', ['as' => 'customer.password.email', 'uses' => 'Auth\CustomerForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset', ['as' => 'customer.password.request', 'uses' => 'Auth\CustomerForgotPasswordController@showLinkRequestForm']);
    Route::post('password/reset', ['uses' => 'Auth\CustomerResetPasswordController@reset']);
    Route::get('password/reset/{token}', ['as' => 'customer.password.reset', 'uses' => 'Auth\CustomerResetPasswordController@showResetForm']);

    Route::get('/', ['uses' => 'CustomerCenter\CustomerHomeController@index', 'as' => 'customer.dashboard']);
});


/* ********************************************************** */


Route::group(['prefix' => 'abcc', 'namespace' => '\CustomerCenter'], function ()
{
//    Route::get('/', ['uses' => 'DashboardController@redirectToLogin']);

    Route::group(['middleware' =>  ['context', 'auth']], function()
    {
//        Route::get('/login', 'Auth\CustomerLoginController@showLoginForm')->name('customer,login');
//        Route::post('/login', 'Auth\CustomerLoginController@login')->name('customer,login.submit');
//        Route::get('/', ['uses' => 'CustomerHomeController@index', 'as' => 'customer.dashboard']);
    });

    Route::get('/invoices', 'AbccCustomerInvoicesController@index')->name('abcc.invoices.index');

    Route::get('invoice/{invoiceKey}', ['uses' => 'AbccCustomerInvoicesController@show', 'as' => 'abcc.invoice.show']);

//    Route::get('invoice/{invoiceKey}/pdf', ['uses' => 'AbccCustomerInvoicesController@pdf', 'as' => 'customerCenter.public.invoice.pdf']);
    Route::get('invoice/{invoiceKey}/pdf', ['uses' => 'AbccCustomerInvoicesController@pdf', 'as' => 'abcc.invoice.pdf']);

    Route::get('/vouchers', 'AbccCustomerVouchersController@index')->name('abcc.vouchers.index');

/*
    Route::group(['middleware' => 'auth.customerCenter'], function ()
    {
        Route::get('dashboard', ['uses' => 'CustomerCenterDashboardController@index', 'as' => 'customerCenter.dashboard']);
        Route::get('invoices' , ['uses' => 'CustomerCenterInvoiceController@index'  , 'as' => 'customerCenter.invoices']);
        Route::get('quotes'   , ['uses' => 'CustomerCenterQuoteController@index'    , 'as' => 'customerCenter.quotes']);
        Route::get('payments' , ['uses' => 'CustomerCenterPaymentController@index'  , 'as' => 'customerCenter.payments']);
    });
*/
});

/* ********************************************************** */