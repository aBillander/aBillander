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


/* ********************************************************** */


Route::get('/conf', function () {
	App\Models\Configuration::get('POPO');
	// dd(App\Models\Configuration::loadConfiguration());
});


/* ********************************************************** */


Route::get('migratethis', function()
{
	// 2022-02-09
//	$date = '2022-02-09';

	//

//	die('OK - '.$date);

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

