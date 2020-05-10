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
  // 2020-02-09
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `shipment_service_type_tag` VARCHAR(32) NULL AFTER `shipment_status`;");


  die('OK');

  // 2020-01-26
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `production_orders` ADD `measure_unit_id` INT(10) UNSIGNED NULL AFTER `finished_quantity`;");

  Illuminate\Support\Facades\DB::statement("ALTER TABLE `production_order_lines` ADD `real_quantity` DECIMAL(20,6) NULL AFTER `required_quantity`;");

  // 2020-01-26
  Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS `stock_movements`;");

	Illuminate\Support\Facades\DB::statement("CREATE TABLE `stock_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stockmovementable_id` int(11) NOT NULL,
  `stockmovementable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_before_movement` decimal(20,6) NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `quantity_after_movement` decimal(20,6) NOT NULL,
  `cost_price_before_movement` decimal(20,6) DEFAULT NULL,
  `cost_price_after_movement` decimal(20,6) DEFAULT NULL,
  `price` decimal(20,6) DEFAULT NULL,
  `price_currency` decimal(20,6) DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `conversion_rate` decimal(20,6) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `warehouse_counterpart_id` int(10) UNSIGNED DEFAULT NULL,
  `movement_type_id` smallint(5) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `inventorycode` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`);");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `measure_unit_id` INT(10) UNSIGNED NOT NULL AFTER `quantity`;");


	// die('OK');


	// 2020-01-25
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `production_orders` ADD `finish_date` timestamp null DEFAULT NULL AFTER `schedule_sort_order`;");


	die('OK');

	// 2020-01-23
		
		$o = \App\CustomerOrder::find(716);

		$o->document_prefix = null;

		$o->document_id= 0;
		$o->save();


	die('OK');


	// 2020-01-20
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `delivery_routes` ADD `carrier_id` INT(10) UNSIGNED NOT NULL AFTER `notes`;");


	die('OK');

	// 2020-01-17
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `production_sheet_id` INT(10) UNSIGNED NULL AFTER `customer_viewed_at`;");


	die('OK');

	// 2019-12-11
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `invoice_sequence_id` INT(10) UNSIGNED NULL AFTER `bank_account_id`;");


	die('OK');

	// 2020-01-13
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `shipping_slip_template_id` INT(10) UNSIGNED NULL AFTER `invoice_template_id`;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `order_template_id` INT(10) UNSIGNED NULL AFTER `invoice_template_id`;");


	die('OK');

});


/* ********************************************************** */

