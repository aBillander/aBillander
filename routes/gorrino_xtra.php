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


Route::get('migratethis_xtra', function()
{

	// 2019-12-11
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `invoice_sequence_id` INT(10) UNSIGNED NULL AFTER `bank_account_id`;");


	die('OK');

	// 2020-01-13
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `shipping_slip_template_id` INT(10) UNSIGNED NULL AFTER `invoice_template_id`;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `order_template_id` INT(10) UNSIGNED NULL AFTER `invoice_template_id`;");


	die('OK');

});


/* ********************************************************** */

