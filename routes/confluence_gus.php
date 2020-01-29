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


Route::get('migratethis_confluence', function()
{


	// 2019-11-15
	$cs = \App\Category::get();

	foreach ($cs as $c) {
		# code...
		$c->position = $c->id;
		$c->save();
	}


	die('OK');

		
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `mrp_type` varchar(32) NOT NULL DEFAULT 'onorder' AFTER `procurement_type`;");


// Illuminate\Support\Facades\DB::statement("CREATE TABLE `payment_documents` (

/* */

	// 2019-11-15
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_users` ADD `use_default_min_order_value` INT(10) NOT NULL DEFAULT '1' AFTER `enable_min_order`;");

/* */


		\App\Configuration::updateValue('ABCC_ORDERS_NEED_VALIDATION', \App\Configuration::get('CUSTOMER_ORDERS_NEED_VALIDATION'));

		\App\Configuration::updateValue('ABCC_DISPLAY_PRICES_TAX_INC', '0');






		Illuminate\Support\Facades\DB::statement("ALTER TABLE `price_lists` ADD `last_imported_at` timestamp null DEFAULT NULL AFTER `currency_id`;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `price_rules` ADD `extra_quantity` DECIMAL(20,6) NOT NULL DEFAULT '0.0' AFTER `from_quantity`;");


		Illuminate\Support\Facades\DB::statement("DROP TABLE `cart_lines`;");

		Illuminate\Support\Facades\DB::statement("create table `cart_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `line_type` varchar(32) not null default 'product', `product_id` int unsigned null, `combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `quantity` decimal(20, 6) not null, `measure_unit_id` int unsigned not null, `unit_customer_price` decimal(20, 6) not null default '0', `unit_customer_final_price` decimal(20, 6) not null default '0', `sales_equalization` tinyint not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `tax_percent` decimal(8, 3) not null default '0', `tax_se_percent` decimal(8, 3) not null default '0', `cart_id` int unsigned not null, `tax_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `cart_lines` ADD `extra_quantity` DECIMAL(20,6) NOT NULL DEFAULT '0.0' AFTER `quantity`;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `price_rules` ADD `name` varchar(128) null AFTER `id`;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `cart_lines` ADD `extra_quantity_label` varchar(128) null AFTER `extra_quantity`;");



		Illuminate\Support\Facades\DB::statement("DROP TABLE `carts`;");

		Illuminate\Support\Facades\DB::statement("create table `carts` (`id` int unsigned not null auto_increment primary key, `customer_user_id` int unsigned not null, `customer_id` int unsigned not null, `notes_from_customer` text null, `date_prices_updated` timestamp null, `total_items` int unsigned not null default '0', `total_products_tax_incl` decimal(20, 6) not null default '0', `total_products_tax_excl` decimal(20, 6) not null default '0', `total_shipping_tax_incl` decimal(20, 6) not null default '0', `total_shipping_tax_excl` decimal(20, 6) not null default '0', `document_discount_percent` decimal(20, 6) not null default '0', `document_discount_amount_tax_incl` decimal(20, 6) not null default '0', `document_discount_amount_tax_excl` decimal(20, 6) not null default '0', `document_ppd_percent` decimal(20, 6) not null default '0', `document_ppd_amount_tax_incl` decimal(20, 6) not null default '0', `document_ppd_amount_tax_excl` decimal(20, 6) not null default '0', `total_currency_tax_incl` decimal(20, 6) not null default '0', `total_currency_tax_excl` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `invoicing_address_id` int unsigned null, `shipping_address_id` int unsigned null, `shipping_method_id` int unsigned null, `carrier_id` int unsigned null, `currency_id` int unsigned not null, `payment_method_id` int unsigned null, `secure_key` varchar(32) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `carts` ADD `sub_tax_excl` DECIMAL(20,6) DEFAULT '0.0' AFTER `total_shipping_tax_excl`;");
	 
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `carts` ADD `sub_tax_incl` DECIMAL(20,6) DEFAULT '0.0' AFTER `total_shipping_tax_excl`;");



		$tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

		foreach ($tables as $table) {
			# code...
			Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `extra_quantity_label` varchar(128) null AFTER `quantity`;");

			Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `extra_quantity` DECIMAL(20,6) NULL DEFAULT '0.0' AFTER `quantity`;");

		}


	// 2019-12-02
	$tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

	foreach ($tables as $table) {
		# code...
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `pmu_conversion_rate` DECIMAL(20,6) NULL DEFAULT '1.0' AFTER `measure_unit_id`;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `package_measure_unit_id` INT(10) UNSIGNED NULL AFTER `measure_unit_id`;");

	}
	
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `product_measure_units` CHANGE `base_measure_unit_id` `stock_measure_unit_id` INT(10) UNSIGNED NOT NULL;");
		
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `cart_lines` ADD `pmu_conversion_rate` DECIMAL(20,6) NULL DEFAULT '1.0' AFTER `measure_unit_id`;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `cart_lines` ADD `package_measure_unit_id` INT(10) UNSIGNED NULL AFTER `measure_unit_id`;");



		Illuminate\Support\Facades\DB::statement("ALTER TABLE `price_rules` ADD `conversion_rate` DECIMAL(20,6) NULL DEFAULT '1.0' AFTER `date_to`;");
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `price_rules` ADD `measure_unit_id` INT(10) UNSIGNED NULL AFTER `date_to`;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `measure_units` CHANGE `conversion_rate` `type_conversion_rate` DECIMAL(20,6) NOT NULL DEFAULT '1.000000';");





		Illuminate\Support\Facades\DB::statement("create table `delivery_route_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `delivery_route_id` int unsigned not null, `customer_id` int unsigned null, `address_id` int unsigned null, `notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


		Illuminate\Support\Facades\DB::statement("create table `delivery_sheet_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `delivery_sheet_id` int unsigned not null, `customer_id` int unsigned null, `address_id` int unsigned null, `route_notes` text null, `notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


		Illuminate\Support\Facades\DB::statement("create table `delivery_routes` (`id` int unsigned not null auto_increment primary key, `alias` varchar(32) not null, `name` varchar(64) not null, `driver_name` varchar(128) null, `active` tinyint not null default '1', `notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


		Illuminate\Support\Facades\DB::statement("create table `delivery_sheets` (`id` int unsigned not null auto_increment primary key, `sequence_id` int unsigned null, `document_prefix` varchar(8) null, `document_id` int unsigned not null default '0', `document_reference` varchar(64) null, `name` varchar(64) not null, `driver_name` varchar(128) null, `due_date` date not null, `active` tinyint not null default '1', `route_notes` text null, `driver_notes` text null, `notes` text null, `delivery_route_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
		
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `delivery_route_lines` ADD `active` INT(10) NOT NULL DEFAULT '1' AFTER `address_id`;");


		
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `class_name` varchar(64) DEFAULT NULL AFTER `webshop_id`;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `addresses` ADD `shipping_method_id` INT(10) UNSIGNED NULL AFTER `country_id`;");


	 abi_r('OK');

});


/* ********************************************************** */

