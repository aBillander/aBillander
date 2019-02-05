<?php

/*
|--------------------------------------------------------------------------
| Customer Center Routes
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

Route::group(['prefix' => 'abcc'], function ()
// Route::group(['domain' => env('ABCC_DOMAIN'), 'prefix' => 'abcc'], function ()
{
    Route::get('/login', 'Auth\CustomerLoginController@showLoginForm')->name('customer.login');
    Route::post('/login', 'Auth\CustomerLoginController@login')->name('customer.login.submit');
    Route::post('/logout', 'Auth\CustomerLoginController@customerLogout')->name('customer.logout');

    Route::get('/register', 'Auth\CustomerRegisterController@showRegistrationForm')->name('customer.register');
    Route::post('/register', 'Auth\CustomerRegisterController@register')->name('customer.register.submit');

// Password Reset Routes...
    Route::post('password/email', ['as' => 'customer.password.email', 'uses' => 'Auth\CustomerForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset', ['as' => 'customer.password.request', 'uses' => 'Auth\CustomerForgotPasswordController@showLinkRequestForm']);
    Route::post('password/reset', ['uses' => 'Auth\CustomerResetPasswordController@reset']);
    Route::get('password/reset/{token}', ['as' => 'customer.password.reset', 'uses' => 'Auth\CustomerResetPasswordController@showResetForm']);
});


/* ********************************************************** */


Route::group(['prefix' => 'abcc', 'namespace' => '\CustomerCenter'], function ()
// Route::group(['domain' => env('ABCC_DOMAIN'), 'prefix' => 'abcc', 'namespace' => '\CustomerCenter'], function ()
{
//    Route::get('/', ['uses' => 'DashboardController@redirectToLogin']);

    Route::group(['middleware' =>  ['context', 'auth']], function()
    {
//        Route::get('/login', 'Auth\CustomerLoginController@showLoginForm')->name('customer,login');
//        Route::post('/login', 'Auth\CustomerLoginController@login')->name('customer,login.submit');
//        Route::get('/', ['uses' => 'CustomerHomeController@index', 'as' => 'customer.dashboard']);
    });


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
        Route::get('/', 'CustomerHomeController@index')->name('customer.dashboard');

        Route::post('contact', 'AbccContactMessagesController@store')->name('abcc.contact');

        Route::get('/catalogue',             'AbccCatalogueController@index'      )->name('abcc.catalogue');
        Route::get('/catalogue/newproducts', 'AbccCatalogueController@newProducts')->name('abcc.catalogue.newproducts');
//        Route::get('/catalogue/category/{id}', 'AbccCatalogueController@categoryShow')->name('abcc.catalogue.category.show');

 //       Route::get( '/orders', 'AbccCustomerOrdersController@index')->name('abcc.orders.index');
 //       Route::post('/orders', 'AbccCustomerOrdersController@store')->name('abcc.orders.store');
        Route::resource('/orders',          'AbccCustomerOrdersController')->names('abcc.orders');
        Route::get('orders/{id}/duplicate', 'AbccCustomerOrdersController@duplicateOrder')->name('abcc.order.duplicate'  );
        Route::get('orders/{id}/pdf', 'AbccCustomerOrdersController@showPdf')->name('abcc.order.pdf'  );

        Route::resource('/shippingslips', 'AbccCustomerShippingSlipsController')->names('abcc.shippingslips');

        Route::resource('/invoices', 'AbccCustomerInvoicesController')->names('abcc.invoices');
        Route::get('/invoices/{invoiceKey}/vouchers', 'AbccCustomerInvoicesController@vouchers')->name('abcc.invoices.vouchers');

        Route::get('invoice/{invoiceKey}', ['uses' => 'AbccCustomerInvoicesController@show', 'as' => 'abcc.invoice.show']);

//    Route::get('invoice/{invoiceKey}/pdf', ['uses' => 'AbccCustomerInvoicesController@pdf', 'as' => 'customerCenter.public.invoice.pdf']);
        Route::get('invoice/{invoiceKey}/pdf', ['uses' => 'AbccCustomerInvoicesController@showPdf', 'as' => 'abcc.invoice.pdf']);


        Route::resource('/vouchers', 'AbccCustomerVouchersController')->names('abcc.vouchers');


        Route::get('/cart', 'AbccCustomerCartController@index')->name('abcc.cart');
        Route::get('/cart/line/searchproduct',        'AbccCustomerCartController@searchProduct' )->name('cart.searchproduct');
        Route::get('/cart/line/getproduct',           'AbccCustomerCartController@getProduct'    )->name('cart.getproduct');
// Deprecated
//        Route::get('/cart/add/{id}', 'AbccCustomerCartController@addItem')->name('abcc.addToCart');
        Route::post('cart/additem',  'AbccCustomerCartController@add'    )->name('abcc.cart.add'  );
        Route::post('cart/updateline',  'AbccCustomerCartController@updateLineQuantity'    )->name('abcc.cart.updateline');
        Route::get('/cart/getlines',  'AbccCustomerCartController@getCartLines' )->name('abcc.cart.getlines');
        Route::post('cart/deleteline/{lid}',  'AbccCustomerCartController@deleteCartLine'  )->name('cart.deleteline');


        Route::get( '/account/edit', 'AbccCustomerUserController@edit'  )->name('abcc.account.edit'  );
        Route::post('/account',      'AbccCustomerUserController@update')->name('abcc.account.update');

        Route::get( '/customer/edit', 'AbccCustomerController@edit')->name('abcc.customer.edit');
        Route::post('/customer',      'AbccCustomerController@update')->name('abcc.customer.update');

        Route::resource('/addresses',  'AbccCustomerAddressesController')->names('abcc.customer.addresses');
        Route::post('/addresses/default',  'AbccCustomerAddressesController@updateDefaultAddresses')->name('abcc.customer.addresses.default');

        Route::get('/customer/pricerules', 'AbccCustomerController@getQuantityPriceRules')->name('abcc.customer.pricerules');

        // Route::resource('customers.addresses', 'CustomerAddressesController');

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
