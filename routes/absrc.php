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

    Route::post('mail', 'MailController@store');
});


/* ********************************************************** */

// COMMON

Route::group(['middleware' =>  ['auth:web,salesrep', 'context', 'absrccontext:salesrep']], function()
{

        Route::get('countries/{countryId}/getstates',   'CountriesController@getStates')->name('countries.getstates');
});


/* ********************************************************** */


Route::group(['middleware' =>  ['auth:salesrep', 'context', 'absrccontext:salesrep']], function()
{
//    Route::resource('products.images', 'ProductImagesController');

    Route::get('absrc/products/ajax/name_lookup'  , array('uses' => 'ProductsController@ajaxProductSearch', 
                                                        'as'   => 'absrc.products.ajax.nameLookup' ));
    
    Route::get('absrc/countries/{countryId}/getstates',   array('uses'=>'CountriesController@getStates', 
                                                                'as' => 'absrc.countries.getstates' ) );
    
    Route::post('absrc/currencies/ajax/rate_lookup', array('uses' => 'CurrenciesController@ajaxCurrencyRateSearch', 
                                                        'as' => 'absrc.currencies.ajax.rateLookup'));

    // Sales Reps routes here

    Route::group(['prefix' => 'absrc', 'namespace' => '\SalesRepCenter'], function ()
    {

        Route::get('/', 'SalesRepHomeController@index')->name('salesrep.dashboard');

        Route::get('catalogue', 'AbsrcCatalogueController@index')->name('absrc.catalogue');

        Route::resource('products', 'AbsrcProductsController')->names('absrc.products');
        Route::get('products/{id}/stockmovements',   'AbsrcProductsController@getStockMovements'  )->name('absrc.products.stockmovements');
        Route::get('products/{id}/pendingmovements', 'AbsrcProductsController@getPendingMovements')->name('absrc.products.pendingmovements');
        Route::get('products/{id}/stocksummary',     'AbsrcProductsController@getStockSummary'    )->name('absrc.products.stocksummary');

        Route::get('products/{id}/recentsales',  'AbsrcProductsController@getRecentSales')->name('absrc.products.recentsales');

//        Route::resource('products.images', 'ProductImagesController');

//        Route::get('product/searchbom', 'ProductsController@searchBOM')->name('product.searchbom');
//        Route::post('product/{id}/attachbom', 'ProductsController@attachBOM')->name('product.attachbom');

//        Route::get('products/{id}/duplicate',     'ProductsController@duplicate'   )->name('product.duplicate'  );

//        Route::post('products/{id}/combine', array('as' => 'products.combine', 'uses'=>'ProductsController@combine'));

//        Route::get('products/ajax/name_lookup'  , array('uses' => 'ProductsController@ajaxProductSearch', 
//                                                        'as'   => 'absrc.products.ajax.nameLookup' ));
        
//        Route::post('products/ajax/options_lookup'  , array('uses' => 'ProductsController@ajaxProductOptionsSearch', 
//                                                        'as'   => 'products.ajax.optionsLookup' ));
//        Route::post('products/ajax/combination_lookup'  , array('uses' => 'ProductsController@ajaxProductCombinationSearch', 
//                                                        'as'   => 'products.ajax.combinationLookup' ));

        Route::resource('customers', 'AbsrcCustomersController')->names('absrc.customers');
        Route::resource('customers.addresses', 'AbsrcCustomerAddressesController')->names('absrc.customers.addresses');

        Route::resource('customerusers', 'AbsrcCustomerUsersController')->names('absrc.customerusers');
        Route::get('customerusers/create/withcustomer/{customer}', 'AbsrcCustomerUsersController@createWithCustomer')->name('absrc.customer.createuser');
        Route::get('customerusers/{customer}/impersonate', 'AbsrcCustomerUsersController@impersonate')->name('absrc.customer.impersonate');

/*        Route::resource('customers', 'CustomersController');
        Route::get('customerorders/create/withcustomer/{customer}', 'CustomerOrdersController@createWithCustomer')->name('customerorders.create.withcustomer');
        Route::get('customers/ajax/name_lookup', array('uses' => 'CustomersController@ajaxCustomerSearch', 'as' => 'customers.ajax.nameLookup'));
        Route::get('customers/{id}/getorders',             'CustomersController@getOrders'    )->name('customer.getorders');
        Route::get('customers/{id}/getpricerules',         'CustomersController@getPriceRules')->name('customer.getpricerules');
        Route::post('customers/invite', 'CustomersController@invite')->name('customers.invite');

        Route::get('customers/{id}/product/{productid}/consumption', 'CustomersController@productConsumption' )->name('customer.product.consumption');

        Route::get('customers/{id}/recentsales',  'CustomersController@getRecentSales')->name('customer.recentsales');
*/


        $pairs = [
                [
                    'controller' => 'AbsrcCustomerOrdersController',
                    'path' => 'orders',
                ],
        ];


foreach ($pairs as $pair) {

        $controller = $pair['controller'];
        $path = $pair['path'];
        $routepath = 'absrc.'.$path;

        Route::resource($path, $controller)->names($routepath);

        Route::get($path.'/create/withcustomer/{customer_id}', $controller.'@createWithCustomer')->name($routepath.'.create.withcustomer');

        Route::get($path.'/ajax/customer_lookup', $controller.'@ajaxCustomerSearch')->name($routepath.'.ajax.customerLookup');
        Route::get($path.'/ajax/customer/{id}/adressbook_lookup', $controller.'@customerAdressBookLookup')->name($routepath.'.ajax.customer.AdressBookLookup');

        Route::get($path.'/{id}/getlines',             $controller.'@getDocumentLines'  )->name($routepath.'.getlines' );
        Route::get($path.'/{id}/getheader',            $controller.'@getDocumentHeader' )->name($routepath.'.getheader');
        Route::get($path.'/line/productform/{action}', $controller.'@FormForProduct')->name($routepath.'.productform');
        Route::get($path.'/line/serviceform/{action}', $controller.'@FormForService')->name($routepath.'.serviceform');
        Route::get($path.'/line/commentform/{action}', $controller.'@FormForComment')->name($routepath.'.commentform');
        Route::get($path.'/line/searchproduct',        $controller.'@searchProduct' )->name($routepath.'.searchproduct');
        Route::get($path.'/line/searchservice',        $controller.'@searchService' )->name($routepath.'.searchservice');
        Route::get($path.'/line/getproduct',           $controller.'@getProduct'    )->name($routepath.'.getproduct');

        // ?? Maybe only for Invoices ??
        Route::get($path.'/{id}/getpayments',          $controller.'@getDocumentPayments' )->name($routepath.'.getpayments');


        Route::post($path.'/{id}/storeline',    $controller.'@storeDocumentLine'   )->name($routepath.'.storeline'  );
        Route::post($path.'/{id}/updatetotal',  $controller.'@updateDocumentTotal' )->name($routepath.'.updatetotal');
        Route::get($path.'/{id}/getline/{lid}', $controller.'@getDocumentLine'     )->name($routepath.'.getline'    );
        Route::post($path.'/updateline/{lid}',  $controller.'@updateDocumentLine'  )->name($routepath.'.updateline' );
        Route::post($path.'/deleteline/{lid}',  $controller.'@deleteDocumentLine'  )->name($routepath.'.deleteline' );
        Route::get($path.'/{id}/duplicate',     $controller.'@duplicateDocument'   )->name($routepath.'.duplicate'  );
        Route::get($path.'/{id}/profit',        $controller.'@getDocumentProfit'   )->name($routepath.'.profit'     );
        Route::get($path.'/{id}/availability',  $controller.'@getDocumentAvailability' )->name($routepath.'.availability' );
        
        Route::get($path.'/{id}/availability/modal',  $controller.'@getDocumentAvailabilityModal' )->name($routepath.'.availability.modal' );

        Route::post($path.'/{id}/quickaddlines',    $controller.'@quickAddLines'   )->name($routepath.'.quickaddlines'  );

        Route::post($path.'/sortlines', $controller.'@sortLines')->name($routepath.'.sortlines');

        Route::get($path.'/{document}/confirm',   $controller.'@confirm'  )->name($routepath.'.confirm'  );
        Route::get($path.'/{document}/unconfirm', $controller.'@unConfirm')->name($routepath.'.unconfirm');

        Route::get($path.'/{id}/pdf',         $controller.'@showPdf'       )->name($routepath.'.pdf'        );
        Route::get($path.'/{id}/invoice/pdf', $controller.'@showPdfInvoice')->name($routepath.'.invoice.pdf');
        Route::match(array('GET', 'POST'), 
                   $path.'/{id}/email',       $controller.'@sendemail'     )->name($routepath.'.email'      );

        Route::get($path.'/{document}/onhold/toggle', $controller.'@onholdToggle')->name($routepath.'.onhold.toggle');

        Route::get($path.'/{document}/close',   $controller.'@close'  )->name($routepath.'.close'  );
        Route::get($path.'/{document}/unclose', $controller.'@unclose')->name($routepath.'.unclose');

        Route::get($path.'/customers/{id}',  $controller.'@indexByCustomer')->name('absrc.customer.'.str_replace('customer', '', $path));
}


        Route::resource('/shippingslips', 'AbsrcCustomerShippingSlipsController')->names('absrc.shippingslips');
        Route::get('shippingslips/{id}/pdf', 'AbsrcCustomerShippingSlipsController@showPdf')->name('absrc.shippingslip.pdf'  );

        Route::resource('/invoices', 'AbsrcCustomerInvoicesController')->names('absrc.invoices');
        Route::get('/invoices/{invoiceKey}/shippingslips', 'AbsrcCustomerInvoicesController@shippingslips')->name('absrc.invoices.shippingslips');
//        Route::get('/invoices/{invoiceKey}/vouchers', 'AbsrcCustomerInvoicesController@vouchers')->name('absrc.invoices.vouchers');

        Route::get('invoice/{invoiceKey}', ['uses' => 'AbsrcCustomerInvoicesController@show', 'as' => 'absrc.invoice.show']);

//    Route::get('invoice/{invoiceKey}/pdf', ['uses' => 'AbsrcCustomerInvoicesController@pdf', 'as' => 'customerCenter.public.invoice.pdf']);
        Route::get('invoice/{invoiceKey}/pdf', ['uses' => 'AbsrcCustomerInvoicesController@showPdf', 'as' => 'absrc.invoice.pdf']);


//        Route::resource('/vouchers', 'AbsrcCustomerVouchersController')->names('absrc.vouchers');

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
