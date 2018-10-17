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

    Route::group(['middleware' =>  ['auth:customer', 'abcccontext']], function()
    {
        Route::get( '/account/edit', 'AbccCustomerUserController@edit'  )->name('abcc.account.edit'  );
        Route::post('/account',      'AbccCustomerUserController@update')->name('abcc.account.update');

        Route::get('/catalogue', 'AbccCatalogueController@index'  )->name('abcc.catalogue');
//        Route::get('/catalogue/category/{id}', 'AbccCatalogueController@categoryShow')->name('abcc.catalogue.category.show');

        Route::get('/orders', 'AbccCustomerOrdersController@index')->name('abcc.orders.index');

        Route::get('/cart', 'AbccCustomerCartController@index')->name('abcc.cart');
        Route::get('/cart/line/searchproduct',        'AbccCustomerCartController@searchProduct' )->name('cart.searchproduct');
        Route::get('/cart/line/getproduct',           'AbccCustomerCartController@getProduct'    )->name('cart.getproduct');
        Route::get('/cart/add/{id}', 'AbccCustomerCartController@addItem')->name('abcc.addToCart');
        Route::post('cart/additem',  'AbccCustomerCartController@add'    )->name('abcc.cart.add'  );
        Route::get('cart/getlines',  'AbccCustomerCartController@getCartLines' )->name('abcc.cart.getlines');
        Route::post('customercart/deleteline/{lid}',  'AbccCustomerCartController@deleteCartLine'  )->name('cart.deleteline' );
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
