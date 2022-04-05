<?php

use Illuminate\Support\Facades\DB;

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



function table_models($table, $field, $models)
{
/*
SELECT
  category,
  COUNT(*) AS `num`
FROM
  posts
GROUP BY
  category
*/
	foreach ($models as $model) {
		// code...
		$sql = "UPDATE `$table` SET `$field` = 'App\\\\Models\\\\$model' WHERE `$field` = 'App\\\\$model';";
		DB::statement($sql);
		abi_r($sql);
	}
  
  	return true;
}

Route::get('migratethis', function()
{

	// 2022-03-21

	$date = '2022-03-21';

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `reference_external_wrin` varchar(32) NULL AFTER `webshop_id`;");

	// die('OK - '.$date);

	Illuminate\Support\Facades\DB::statement("INSERT INTO `templates` (`id`, `name`, `model_name`, `folder`, `file_name`, `paper`, `orientation`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'xtranat Albarán Gorillas', 'CustomerShippingSlipPdf', 'templates::', 'xtragorillas', 'A4', 'portrait', '2022-03-21 07:30:53', '2022-03-21 07:30:53', NULL);");

	die('OK - '.$date);


	// 2022-03-16
	$date = '2022-03-16';

	// SELECT DISTINCT column1, column2, ... FROM table_name;

	// Table email_logs
	table_models('email_logs', 'userable_type', ['CustomerUser', 'SalesRepUser', 'User']);

	die('OK');

	// Table shipping_model_attachments
	table_models('model_attachments', 'attachmentable_type ', ['Supplier', 'DownPayment', 'Product', 'Lot', 'Customer', 'Cheque']);

	// Table shipping_method_service_lines
	table_models('shipping_method_service_lines', 'tabulable_type', ['ShippingMethod']);

	// Table shipping_methods
	table_models('shipping_methods', 'class_name', ['ShippingMethods\\\\StorePickUpShippingMethod', 'ShippingMethods\\\\RegularDeliveryRouteShippingMethod', 'ShippingMethods\\\\TransportAgencyShippingMethod']);

	die('OK');

	// Table stock_movements
	table_models('stock_movements', 'stockmovementable_type', ['CustomerShippingSlipLine']);

	die('OK');

	// Table payments
	table_models('payments', 'paymentable_type'  , ['CustomerInvoice', 'SupplierInvoice']);
	table_models('payments', 'paymentorable_type', ['Customer', 'Supplier']);

	die('OK');

	// Table images
	table_models('images', 'imageable_type', ['Product']);

	// Table document_ascriptions
	table_models('document_ascriptions', 'leftable_type' , ['CustomerOrder', 'CustomerShippingSlip']);
	table_models('document_ascriptions', 'rightable_type', ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice']);

	die('OK');

	// Table bank_accounts
	$table = 'bank_accounts';
	$field = 'bank_accountable_type';
	$models = ['Company', 'Customer'];
	foreach ($models as $model) {
		// code...
		DB::statement("UPDATE `$table` SET `$field` = 'App\\\\Models\\\\$model' WHERE `$field` = 'App\\\\$model';");
	}

	die('OK - '.$date);


	// 2022-03-15
	$date = '2022-03-15';

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `images` CHANGE `imageable_type` `imageable_type` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `images` CHANGE `imageable_id` `imageable_id` INT(10) UNSIGNED NULL;");

	Illuminate\Support\Facades\DB::statement("RENAME TABLE `product_b_o_m_s` TO `product_b_o_m_s`;");

	// Table addresses
	$models = ['Company', 'Customer', 'Supplier', 'Warehouse'];
	foreach ($models as $model) {
		// code...
		Illuminate\Support\Facades\DB::statement("UPDATE `addresses` SET `addressable_type` = 'App\\Models\\'.$model WHERE `addressable_type` = 'App\\'.$model;");
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

