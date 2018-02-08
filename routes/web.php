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

});

/* ********************************************************** */


if (file_exists(__DIR__.'/gorrino_routes.php')) {
    include __DIR__.'/gorrino_routes.php';
}

/* ********************************************************** */


/* ********************************************************** */
