<?php

use App\Models\Configuration;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Gorrino ninefy route
|--------------------------------------------------------------------------
|
| *********
| *******
| *****
|
*/


Route::get('ninefy', function()
{
	// 2022-05-26
	$date = '2022-05-26';

	// Watch out:
	// https://github.com/laravel/framework/issues/22900

	// Table document_ascriptions
	// Customers
	table_models('document_ascriptions', 'leftable_type' , ['CustomerQuotation']);

//	die('OK - '.$date);

	// Suppliers
	table_models('document_ascriptions', 'leftable_type' , ['SupplierOrder', 'SupplierShippingSlip']);
	table_models('document_ascriptions', 'rightable_type', ['SupplierShippingSlip', 'SupplierInvoice']);

	die('OK - '.$date);




	// 2022-05-11
	$date = '2022-05-11';
	
	DB::statement("ALTER TABLE `password_resets` ADD `model_name` varchar(64) not null default '".addslashes(App\Models\User::class)."' AFTER `token`;");

//	die('OK - '.$date);


	// 2022-04-27
	$date = '2022-04-27';

	Configuration::updateValue('ASIGNA_CARRIER_ID', '2');

	Configuration::updateValue('DB_COMPRESS_BACKUP', '1');

	Configuration::updateValue('DB_EMAIL_NOTIFY', '0');

//	die('OK - '.$date);


	// 2022-04-21
	$date = '2022-04-21';

	// Table stock_movements
	$models = ['StockCountLine', 'CustomerShippingSlipLine', 'CustomerInvoiceLine', 'SupplierShippingSlipLine', 'WarehouseShippingSlipLine', 'AssemblyOrder', 'AssemblyOrderLine'];
	foreach ($models as $model) {
		// code...
		Illuminate\Support\Facades\DB::statement("UPDATE `stock_movements` SET `stockmovementable_type` = 'App\\\\Models\\\\".$model."' WHERE `stockmovementable_type` = 'App\\\\".$model."';");

		abi_r("UPDATE `stock_movements` SET `stockmovementable_type` = 'App\\\\Models\\\\".$model."' WHERE `stockmovementable_type` = 'App\\\\".$model."';");
	}	

//	die('OK - '.$date);
  

	// Table addresses
	$models = ['Company', 'Customer', 'Supplier', 'Warehouse'];
	foreach ($models as $model) {
		// code...
		Illuminate\Support\Facades\DB::statement("UPDATE `addresses` SET `addressable_type` = 'App\\\\Models\\\\".$model."' WHERE `addressable_type` = 'App\\\\".$model."';");

		abi_r("UPDATE `addresses` SET `addressable_type` = 'App\\\\Models\\\\".$model."' WHERE `addressable_type` = 'App\\\\".$model."';");
	}	

//	die('OK - '.'addresses');

	// 2022-04-09
	$date = '2022-04-09';

	DB::statement("drop table if exists `action_types`;");

	DB::statement("create table `action_types` (`id` int unsigned not null auto_increment primary key, `alias` varchar(16) not null, `name` varchar(64) not null, `description` text null, `active` tinyint not null default '1', `position` int unsigned not null default '0', `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';");

	DB::statement("INSERT INTO `action_types` (`id`, `alias`, `name`, `description`, `active`, `position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CALL', 'Llamar por teléfono', NULL, 1, 0, NULL, NULL, NULL),
(2, 'MEET', 'Reunión', NULL, 1, 0, NULL, NULL, NULL),
(3, 'MEET-WEB', 'Reunión online', NULL, 1, 0, NULL, NULL, NULL);");

	DB::statement("drop table if exists `actions`;");

	DB::statement("create table `actions` (`id` int unsigned not null auto_increment primary key, `name` varchar(255) not null, `description` text null, `status` varchar(32) not null default 'pending', `priority` varchar(32) not null default 'low', `start_date` datetime null, `due_date` datetime null, `finish_date` datetime null, `results` text null, `position` int unsigned not null default '0', `user_created_by_id` int unsigned null, `user_assigned_to_id` int unsigned null, `action_type_id` int unsigned not null, `sales_rep_id` int unsigned null, `contact_id` int unsigned null, `customer_id` int unsigned null, `lead_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'");



	DB::statement("drop table if exists `contacts`;");

	DB::statement("create table `contacts` (`id` int unsigned not null auto_increment primary key, `firstname` varchar(32) not null, `lastname` varchar(32) null, `job_title` varchar(255) null, `type` varchar(32) null default 'Employee', `email` varchar(255) null, `phone` varchar(32) null, `phone_mobile` varchar(32) null, `address` varchar(255) null, `website` varchar(128) null, `is_primary` tinyint not null default '0', `blocked` tinyint not null default '0', `active` tinyint not null default '1', `notes` text null, `user_created_by_id` int unsigned not null, `party_id` int unsigned null, `customer_id` int unsigned null, `address_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'");


	DB::statement("ALTER TABLE `parties` CHANGE `email` `email` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");

//	die('OK - '.$date);



	// 2022-03-21

	$date = '2022-03-21';

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `reference_external_wrin` varchar(32) NULL AFTER `webshop_id`;");

	// die('OK - '.$date);
/*
	Illuminate\Support\Facades\DB::statement("INSERT INTO `templates` (`id`, `name`, `model_name`, `folder`, `file_name`, `paper`, `orientation`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'xtranat Albarán Gorillas', 'CustomerShippingSlipPdf', 'templates::', 'xtragorillas', 'A4', 'portrait', '2022-03-21 07:30:53', '2022-03-21 07:30:53', NULL);");
*/
//	die('OK - '.$date);



	// 2022-03-16
	$date = '2022-03-16';

	// SELECT DISTINCT column1, column2, ... FROM table_name;

	// Table email_logs
	table_models('email_logs', 'userable_type', ['CustomerUser', 'SalesRepUser', 'User']);

	abi_r('OK');

	// Table shipping_model_attachments
	table_models('model_attachments', 'attachmentable_type', ['Supplier', 'DownPayment', 'Product', 'Lot', 'Customer', 'Cheque']);

	// Table shipping_method_service_lines
	table_models('shipping_method_service_lines', 'tabulable_type', ['ShippingMethod']);

	// Table shipping_methods
	table_models('shipping_methods', 'class_name', ['ShippingMethods\\\\StorePickUpShippingMethod', 'ShippingMethods\\\\RegularDeliveryRouteShippingMethod', 'ShippingMethods\\\\TransportAgencyShippingMethod']);

	abi_r('OK');

	// Table stock_movements
	table_models('stock_movements', 'stockmovementable_type', ['CustomerShippingSlipLine']);

	abi_r('OK');

	// Table payments
	table_models('payments', 'paymentable_type'  , ['CustomerInvoice', 'SupplierInvoice']);
	table_models('payments', 'paymentorable_type', ['Customer', 'Supplier']);

//	die('OK');

	// Table images
	table_models('images', 'imageable_type', ['Product']);

	// Table document_ascriptions
	table_models('document_ascriptions', 'leftable_type' , ['CustomerOrder', 'CustomerShippingSlip']);
	table_models('document_ascriptions', 'rightable_type', ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice']);

//	die('OK');
	

	// Table bank_accounts
	$table = 'bank_accounts';
	$field = 'bank_accountable_type';
	$models = ['Company', 'Customer'];
	foreach ($models as $model) {
		// code...
		DB::statement("UPDATE `$table` SET `$field` = 'App\\\\Models\\\\$model' WHERE `$field` = 'App\\\\$model';");

		abi_r("UPDATE `$table` SET `$field` = 'App\\\\Models\\\\$model' WHERE `$field` = 'App\\\\$model';");
	}

//	die('OK - '.$date);


	// 2022-03-15
	$date = '2022-03-15';

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `images` CHANGE `imageable_type` `imageable_type` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `images` CHANGE `imageable_id` `imageable_id` INT(10) UNSIGNED NULL;");

	Illuminate\Support\Facades\DB::statement("RENAME TABLE `product_b_o_ms` TO `product_b_o_m_s`;");
/*	DB::statement("CREATE TABLE `product_b_o_m_lines` (
					  `id` int(10) UNSIGNED NOT NULL,
					  `line_sort_order` int(11) DEFAULT NULL,
					  `line_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
					  `product_id` int(10) UNSIGNED NOT NULL,
					  `quantity` decimal(20,6) NOT NULL DEFAULT 1.000000,
					  `measure_unit_id` int(10) UNSIGNED NOT NULL,
					  `scrap` decimal(8,3) NOT NULL DEFAULT 0.000,
					  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `product_bom_id` int(10) UNSIGNED NOT NULL,
					  `created_at` timestamp NULL DEFAULT NULL,
					  `updated_at` timestamp NULL DEFAULT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
	DB::statement("CREATE TABLE `product_b_o_m_s` (
					  `id` int(10) UNSIGNED NOT NULL,
					  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
					  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
					  `quantity` decimal(20,6) NOT NULL DEFAULT 1.000000,
					  `measure_unit_id` int(10) UNSIGNED NOT NULL,
					  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'certified',
					  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `created_at` timestamp NULL DEFAULT NULL,
					  `updated_at` timestamp NULL DEFAULT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
*/

	// Table addresses
	$models = ['Company', 'Customer', 'Supplier', 'Warehouse'];
	foreach ($models as $model) {
		// code...
		Illuminate\Support\Facades\DB::statement("UPDATE `addresses` SET `addressable_type` = 'App\\\\Models\\\\".$model."' WHERE `addressable_type` = 'App\\\\".$model."';");

		abi_r("UPDATE `addresses` SET `addressable_type` = 'App\\\\Models\\\\".$model."' WHERE `addressable_type` = 'App\\\\".$model."';");
	}	

//	die('OK - '.$date);


	// 2022-03-04
	$date = '2022-03-04';

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `users` ADD `email_verified_at` timestamp NULL DEFAULT NULL AFTER `email`;");

	die('OK - '.$date);

});


/* ********************************************************** */




/* ********************************************************** */

