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


Route::get('migratethis_gmdis', function()
{

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `measure_unit_id` INT(10) UNSIGNED NULL AFTER `quantity`;");

	die('OK');

});


/* ********************************************************** */

