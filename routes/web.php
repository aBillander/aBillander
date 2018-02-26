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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();

// Disable Registration Routes...
// See:
// https://stackoverflow.com/questions/29183348/how-to-disable-registration-new-user-in-laravel-5
// https://stackoverflow.com/questions/42695917/laravel-5-4-disable-register-route/42700000
if ( !env('ALLOW_USER_REGISTRATION', false) )
{
    Route::get('register', function()
        {
            return view('errors.404');
        })->name('register');

    Route::post('register', function()
        {
            return view('errors.404');
        });
}

Route::get('/', 'WelcomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');


// https://www.youtube.com/watch?v=Vb7G1Q2g66g&t=1931s
Route::get('clear-cache', function()
{
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    // If working with File Storage
    // Artisan::call('storage:link');
    // Or create simlink manually

    return '<h1>Cachés borradas</h1>';
});

Route::get('404', function()
{
    return view('errors.404');
});



/* ********************************************************** */


// Secure-Routes
Route::group(['middleware' =>  ['auth']], function()
{
        
        Route::resource('configurations',    'ConfigurationsController');

        Route::resource('languages', 'LanguagesController');

        Route::resource('sequences', 'SequencesController');

        Route::resource('users', 'UsersController');

        Route::resource('currencies', 'CurrenciesController');
        Route::get('currencies/{id}/exchange',   array('uses'=>'CurrenciesController@exchange', 
                                                                'as' => 'currencies.exchange' ) );  
        Route::post('currencies/ajax/rate_lookup', array('uses' => 'CurrenciesController@ajaxCurrencyRateSearch', 
                                                        'as' => 'currencies.ajax.rateLookup'));

        Route::resource('measureunits', 'MeasureUnitsController');

        Route::resource('workcenters', 'WorkCentersController');

        Route::resource('products', 'ProductsController');
        Route::get('product/searchbom', 'ProductsController@searchBOM')->name('product.searchbom');
//        Route::post('product/{id}/attachbom', 'ProductsController@attachBOM')->name('product.attachbom');

        Route::resource('ingredients', 'IngredientsController');

        Route::resource('productboms', 'ProductBOMsController');
        Route::get('productboms/{id}/getlines', 'ProductBOMsController@getBOMlines')->name('productbom.getlines');
        Route::get('productboms/{id}/getproducts', 'ProductBOMsController@getBOMproducts')->name('productbom.getproducts');
        Route::post('productboms/{id}/storeline', 'ProductBOMsController@storeBOMline')->name('productbom.storeline');
        Route::get('productboms/{id}/getline/{lid}', 'ProductBOMsController@getBOMline')->name('productbom.getline');
        Route::post('productboms/updateline/{lid}', 'ProductBOMsController@updateBOMline')->name('productbom.updateline');
        Route::post('productboms/deleteline/{lid}', 'ProductBOMsController@deleteBOMline')->name('productbom.deleteline');
        Route::get('productboms/line/searchproduct', 'ProductBOMsController@searchProduct')->name('productbom.searchproduct');
        Route::get('productboms/{id}/duplicate', 'ProductBOMsController@duplicateBOM')->name('productbom.duplicate');

});

/* ********************************************************** */


if (file_exists(__DIR__.'/gorrino_routes.php')) {
    include __DIR__.'/gorrino_routes.php';
}

/* ********************************************************** */


/* ********************************************************** */
