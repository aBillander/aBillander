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
	// 2022-03-15
	$date = '2022-03-15';

	Illuminate\Support\Facades\DB::statement("RENAME TABLE `product_b_o_ms` TO `xtra1225`.`product_b_o_m_s`;");

	$tables = ['Company', 'Customer', 'Supplier', 'Warehouse'];

	foreach ($tables as $table) {
		// code...
		Illuminate\Support\Facades\DB::statement("UPDATE `addresses` SET `addressable_type` = 'App\\Models\\'.$table WHERE `addressable_type` = 'App\\'.$table;");
	}	

	die('OK - '.$date);


	// 2022-03-04
	$date = '2022-03-04';

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `users` ADD `email_verified_at` timestamp NULL DEFAULT NULL AFTER `email`;");

	die('OK - '.$date);

});


/* ********************************************************** */


if (file_exists(__DIR__.'/gorrino_xtra.php')) {
    include __DIR__.'/gorrino_xtra.php';
}


if (file_exists(__DIR__.'/gorrino_gmdis.php')) {
    include __DIR__.'/gorrino_gmdis.php';
}


if (file_exists(__DIR__.'/gorrino_sandbox.php')) {
    include __DIR__.'/gorrino_sandbox.php';
}


/* ********************************************************** */




/* ********************************************************** */

