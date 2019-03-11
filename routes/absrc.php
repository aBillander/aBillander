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

        Route::resource('customers', 'AbsrcCustomersController');


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
