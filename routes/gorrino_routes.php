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
	
	// 2020-04-20
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `webshop_id` varchar(16) NULL DEFAULT NULL AFTER `publish_to_web`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `categories` ADD `description` TEXT NULL DEFAULT NULL AFTER `name`;");

	// Fix error; see below
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` CHANGE `transit_time` `transit_time` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` CHANGE `tracking_url` `tracking_url` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");


	// 2020-04-15
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `suppliers` CHANGE `customer_logo` `supplier_logo` VARCHAR(128) NULL DEFAULT NULL;");


	die('OK');


	// 2020-03-14
	Illuminate\Support\Facades\DB::statement("create table `shipping_method_services` (`id` int unsigned not null auto_increment primary key, `name` varchar(64) null, `billing_type` varchar(32) not null, `transit_time` varchar(16) null, `free_shipping_from` decimal(20, 6) not null default '0', `tax_id` int unsigned not null, `position` int unsigned not null default '0', `shipping_method_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	
	Illuminate\Support\Facades\DB::statement("create table `shipping_method_service_lines` (`id` int unsigned not null auto_increment primary key, `country_id` int unsigned null, `state_id` int unsigned null, `postcode` varchar(12) null, `from_amount` decimal(20, 6) not null default '0', `price` decimal(20, 6) not null default '0', `tax_id` int unsigned not null, `tabulable_id` int not null, `tabulable_type` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");



	Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` ADD `transit_time` varchar(128) null AFTER `name`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` ADD `tracking_url` varchar(16) null AFTER `name`;");


	Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `position` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `active`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `tax_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `active`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `free_shipping_from` DECIMAL(20,6) NULL DEFAULT '0.0' AFTER `active`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `billing_type` varchar(32) NOT NULL DEFAULT 'price' AFTER `active`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `transit_time` varchar(16) NULL DEFAULT null AFTER `active`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `type` varchar(32) NOT NULL DEFAULT 'basic' AFTER `active`;");



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

