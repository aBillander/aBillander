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


Route::get('migratethis_cnflnc', function()
{

// Illuminate\Support\Facades\DB::statement("CREATE TABLE `payment_documents` (
	// Comprobar tablas payments & sepa_direct_debits & bank_accounts en enatural

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_users` ADD `is_principal` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `active`;");


		Illuminate\Support\Facades\DB::statement("UPDATE `customer_users` SET `is_principal` = '1' WHERE `customer_users`.`id` > 0;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_users` ADD `address_id` INT(10) UNSIGNED NULL AFTER `customer_id`;");


	// 2019-11-15
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_users` ADD `use_default_min_order_value` INT(10) NOT NULL DEFAULT '1' AFTER `enable_min_order`;");


	// create table `email_logs`

		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `shipment_status` VARCHAR(32) NOT NULL DEFAULT 'pending' AFTER `import_key`;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_users` ADD `display_prices_tax_inc` INT(10) NOT NULL DEFAULT '0' AFTER `min_order_value`;");

		// Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_users` CHANGE `display_prices_tax_inc` `display_prices_tax_inc` INT(10) NOT NULL DEFAULT '0';");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_count_lines` CHANGE `product_id` `product_id` INT(10) UNSIGNED NULL;");


		//
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` CHANGE `machine_capacity` `machine_capacity` VARCHAR(16) NULL DEFAULT NULL;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` ADD `alias` VARCHAR(32) NULL AFTER `id`;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `shipping_methods` ADD `alias` VARCHAR(16) NULL AFTER `user_id`;");

		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `bank_accounts` ADD `creditorid` VARCHAR(30) NULL AFTER `swift`;");

		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `bank_accounts` ADD `suffix` VARCHAR(3) NOT NULL DEFAULT '000' AFTER `swift`;");


		Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` CHANGE `manufacturing_batch_size` `manufacturing_batch_size` INT(10) UNSIGNED NOT NULL DEFAULT '1';");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` CHANGE `units_per_tray` `units_per_tray` INT(10) UNSIGNED NULL DEFAULT NULL;");



		\App\Configuration::updateValue('ABCC_ORDERS_NEED_VALIDATION', \App\Configuration::get('CUSTOMER_ORDERS_NEED_VALIDATION'));

		\App\Configuration::updateValue('ABCC_DISPLAY_PRICES_TAX_INC', '0');


		// enatural ???
		Illuminate\Support\Facades\DB::statement("create table `commission_settlements` (`id` int unsigned not null auto_increment primary key, `name` varchar(128) null, `document_date` datetime not null, `date_from` datetime not null, `date_to` datetime not null, `paid_documents_only` tinyint not null default '0', `status` varchar(32) not null default 'pending', `onhold` tinyint not null default '0', `total_commissionable` decimal(20, 6) not null default '0', `total_settlement` decimal(20, 6) not null default '0', `notes` text null, `close_date` datetime null, `posted_at` date null, `sales_rep_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci
	;");

		// enatural ???
		Illuminate\Support\Facades\DB::statement("create table `commission_settlement_lines` (`id` int unsigned not null auto_increment primary key, `commissionable_id` int not null, `commissionable_type` varchar(191) not null, `document_reference` varchar(64) null, `document_date` datetime not null, `document_commissionable_amount` decimal(20, 6) not null default '0', `commission_percent` decimal(8, 3) not null default '0', `commission` decimal(20, 6) not null default '0', `commission_settlement_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `sales_rep_users` ADD `allow_abcc_access` INT(10) NOT NULL DEFAULT '0' AFTER `active`;");


		// enatural ???
	$tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

	foreach ($tables as $table) {
		# code...

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `ecotax_total_amount` DECIMAL(20,6) NOT NULL DEFAULT '0.0' AFTER `ecotax_amount`;");

	}
		

		Illuminate\Support\Facades\DB::statement("create table `payment_types` (`id` int unsigned not null auto_increment primary key, `alias` varchar(32) not null, `name` varchar(64) not null, `active` tinyint not null default '1', `accounting_code` varchar(32) null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	 

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `payment_methods` CHANGE `payment_document_id` `payment_type_id` INT(10) UNSIGNED NULL DEFAULT NULL;");


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



		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `invoice_sequence_id` INT(10) UNSIGNED NULL AFTER `bank_account_id`;");


		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `cost_price_after_movement`  DECIMAL(20,6) NULL AFTER `quantity_after_movement`;");

		// enatural ???
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `cost_price_before_movement` DECIMAL(20,6) NULL AFTER `quantity_after_movement`;");



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

