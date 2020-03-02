<?php

// Most suitable way to go about this is listen to db queries. You can do
/*
\DB::listen(function ($query) {
    dump($query->sql);
    dump($query->bindings);
    dump($query->time);
});
*/

/*
|--------------------------------------------------------------------------
| Gorrino Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('segment', function( )
{
	return '';
});

/* ********************************************************** */


Route::get('mprobe', 'MProbeController@send');

Route::get('mqueue', 'MProbeController@queue');

Route::get('mqueuer', 'MProbeController@queuer');






/* ********************************************************** */


/* ********************************************************** */


Route::get('migratethis', function()
{

	// 2020-02-04
	//	Illuminate\Support\Facades\DB::statement("");

	die('OK');

});


/* ********************************************************** */


if (file_exists(__DIR__.'/gorrino_xtra.php')) {
    include __DIR__.'/gorrino_xtra.php';
}


if (file_exists(__DIR__.'/gorrino_gmdis.php')) {
    include __DIR__.'/gorrino_gmdis.php';
}



/* ********************************************************** */




/* ********************************************************** */

