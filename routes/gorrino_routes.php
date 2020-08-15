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




Route::get('child', function( )
{
	$product = \App\Product::find( 5 );		// 1000 Pan integral de espelta 100% 500g ECO

	$needle_id = 190;		// 10601 Amasado de espelta integral
	 $needle_id = 189;		// 80000 Agua

	$result = $product->getChildProductQuantityWithChildren( $needle_id );

	abi_r( $result );

	$result = $product->getChildProductQuantity( $needle_id, 10 );

	abi_r( $result );
});

Route::get('childt', function( )
{
	$product = \App\Product::find( 8 );		// 1003 Pan integral de trigo 500g ECO

	$needle_id = 190;		// 10601 Amasado de espelta integral
	 $needle_id = 1;		// 80000 Agua

	$result = $product->getChildToolQuantityWithChildren( $needle_id );

	abi_r( $result );

	$result = $product->getChildToolQuantity( $needle_id );

	abi_r( $result );
});

/* ********************************************************** */




Route::get('segment', function( )
{
	$list = [617];
	$params = [];
	$params['customer_id'] = 586;
	$params['status'] = 'draft';

	$invoice = \App\CustomerShippingSlip::invoiceDocumentList( $list, $params );

	abi_r($invoice);
});

/* ********************************************************** */


Route::get('mprobe', 'MProbeController@send');

Route::get('mqueue', 'MProbeController@queue');

Route::get('mqueuer', 'MProbeController@queuer');






/* ********************************************************** */


/* ********************************************************** */


Route::get('migratethis', function()
{	
	// 2020-08-14
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `sales_rep_users` ADD `warehouse_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `language_id`;");

	die('OK');


	// 2020-07-30
	Illuminate\Support\Facades\DB::statement("INSERT INTO `templates` (`name`, `model_name`, `folder`, `file_name`, `paper`, `orientation`, `created_at`, `updated_at`, `deleted_at`) VALUES ('Albarán entre Almacenes', 'WarehouseShippingSlipPdf', 'templates::', 'default', 'A4', 'portrait', '2020-08-13 11:30:37', '2020-08-13 11:30:37', NULL);");

	Illuminate\Support\Facades\DB::statement("INSERT INTO `sequences` (`name`, `model_name`, `sequenceable_type`, `prefix`, `length`, `separator`, `next_id`, `last_date_used`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES ('Transferencias de Almacén', 'WarehouseShippingSlip', '', 'TRS', 4, '-', 1, NULL, 1, '2020-08-13 11:31:42', '2020-08-13 11:31:42', NULL);");

		Illuminate\Support\Facades\DB::statement("create table `warehouse_shipping_slips` (`id` int unsigned not null auto_increment primary key, `company_id` int unsigned not null default '0', `warehouse_id` int unsigned not null, `warehouse_counterpart_id` int unsigned not null, `user_id` int unsigned not null default '0', `sequence_id` int unsigned null, `document_prefix` varchar(8) null, `document_id` int unsigned not null default '0', `document_reference` varchar(64) null, `reference` varchar(191) null, `created_via` varchar(32) null default 'manual', `document_date` datetime not null, `validation_date` datetime null, `delivery_date` datetime null, `delivery_date_real` datetime null, `close_date` datetime null, `number_of_packages` smallint unsigned not null default '1', `volume` decimal(20, 6) null default '0', `weight` decimal(20, 6) null default '0', `shipping_conditions` text null, `tracking_number` varchar(191) null, `notes` text null, `notes_to_counterpart` text null, `status` varchar(32) not null default 'draft', `onhold` tinyint not null default '0', `locked` tinyint not null default '0', `shipping_method_id` int unsigned null, `carrier_id` int unsigned null, `template_id` int null, `shipment_status` varchar(32) not null default 'pending', `shipment_service_type_tag` varchar(32) null, `printed_at` date null, `edocument_sent_at` date null, `export_date` datetime null, `secure_key` varchar(32) not null, `import_key` varchar(16) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
		
		Illuminate\Support\Facades\DB::statement("create table `warehouse_shipping_slip_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `product_id` int unsigned null, `combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `quantity` decimal(20, 6) not null, `measure_unit_id` int unsigned not null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `notes` text null, `locked` tinyint not null default '0', `created_at` timestamp null, `updated_at` timestamp null, `warehouse_shipping_slip_id` int unsigned not null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

	die('OK');





	// 2020-07-14
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoice_lines` ADD `customer_shipping_slip_line_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `customer_shipping_slip_id`;");


	Illuminate\Support\Facades\DB::statement("create table `lot_items` (`id` int unsigned not null auto_increment primary key, `lot_id` int unsigned not null, `quantity` decimal(20, 6) not null default '1', `is_reservation` tinyint not null default '0', `lotable_id` int not null, `lotable_type` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	// 2020-07-11
	Illuminate\Support\Facades\DB::statement("create table `lots` (`id` int unsigned not null auto_increment primary key, `reference` varchar(32) null, `product_id` int unsigned not null, `combination_id` int unsigned null, `quantity_initial` decimal(20, 6) not null default '0', `quantity` decimal(20, 6) not null default '0', `measure_unit_id` int unsigned not null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `manufactured_at` timestamp null, `expiry_at` timestamp null, `notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `expiry_time` INT(10) UNSIGNED NULL AFTER `active`;");
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `lot_tracking` tinyint not null default '0' AFTER `active`;");

	die('OK');

	
	// 2020-07-01
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `payments` ADD `payment_type_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `paymentorable_type`;");


	// 2020-06-26
	Illuminate\Support\Facades\DB::statement("create table `cheque_details` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `name` varchar(128) not null, `amount` decimal(20, 6) not null default '0', `customer_invoice_id` int unsigned null, `customer_invoice_reference` varchar(64) null, `cheque_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	
	Illuminate\Support\Facades\DB::statement("create table `cheques` (`id` int unsigned not null auto_increment primary key, `document_number` varchar(32) not null, `place_of_issue` varchar(64) not null, `amount` decimal(20, 6) not null default '0', `date_of_issue` date null, `due_date` date null, `payment_date` date null, `posted_at` date null, `date_of_entry` date null, `memo` varchar(128) null, `notes` text null, `status` varchar(32) not null default 'pending', `currency_id` int unsigned not null, `customer_id` int unsigned not null, `drawee_bank_id` varchar(64) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

	Illuminate\Support\Facades\DB::statement("create table `banks` (`id` int unsigned not null auto_increment primary key, `alias` varchar(32) not null, `name` varchar(128) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	die('OK');

	

	// 2020-05-26
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoice_lines` ADD `customer_shipping_slip_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `customer_invoice_id`;");


	// 2020-05-25

	// $table->string('shipment_service_type_tag', 32)->nullable();
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `shipment_service_type_tag` varchar(32) NULL DEFAULT NULL AFTER `shipment_status`;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `customer_logo`;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `shipment_service_type_tag`;");



	// 2020-05-22
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoices` ADD `production_sheet_id` INT(10) UNSIGNED NULL AFTER `posted_at`;");


	die('OK');

	
	\App\Configuration::updateValue('ABCC_MAX_ORDER_VALUE', 10000);


	// 2020-05-14
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_order_templates` ADD `total_tax_excl` DECIMAL(20,6) NULL DEFAULT '0.0' AFTER `last_used_at`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_order_templates` ADD `total_tax_incl` DECIMAL(20,6) NULL DEFAULT '0.0' AFTER `last_used_at`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_order_templates` ADD `last_document_reference` varchar(16) NULL DEFAULT NULL AFTER `last_used_at`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_order_templates` ADD `last_customer_order_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `last_used_at`;");


	die('OK');


	// https://stackoverflow.com/questions/21047573/maintenance-mode-without-using-artisan


	// 2020-05-01
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_order_templates` ADD `shipping_address_id` INT(10) UNSIGNED NOT NULL AFTER `customer_id`;");


	die('OK');


	// 2020-04-26
	Illuminate\Support\Facades\DB::statement("create table `customer_order_templates` (`id` int unsigned not null auto_increment primary key, `alias` varchar(32) null, `name` varchar(128) not null, `document_discount_percent` decimal(20, 6) not null default '0', `document_ppd_percent` decimal(20, 6) not null default '0', `notes` text null, `active` tinyint not null default '1', `last_used_at` timestamp null, `customer_id` int unsigned not null, `template_id` int null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	
	Illuminate\Support\Facades\DB::statement("create table `customer_order_template_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `line_type` varchar(32) not null, `product_id` int unsigned null, `combination_id` int unsigned null, `quantity` decimal(20, 6) not null, `measure_unit_id` int unsigned not null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `pmu_label` varchar(128) null, `notes` text null, `active` tinyint not null default '1', `customer_order_template_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	
	
	// 2020-04-20
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `webshop_id` varchar(16) NULL DEFAULT NULL AFTER `publish_to_web`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `categories` ADD `description` TEXT NULL DEFAULT NULL AFTER `name`;");

	// Fix error; see below
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` CHANGE `transit_time` `transit_time` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `carriers` CHANGE `tracking_url` `tracking_url` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");


	die('OK');
	


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

