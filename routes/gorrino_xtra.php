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


Route::get('xtra_addrs', function()
{
  // 2020-05-31
  $addrs = \App\Address::get();


  foreach ($addrs as $addr) {
    # code...
    if ( $addr->email != trim($addr->email) ){
      abi_r( '*'.$addr->email .'* - '. trim($addr->email) );
      $addr->email = trim($addr->email);
      $addr->save();
    }
  }


  die('OK');

});


/* ********************************************************** */


Route::get('migratethis_xtra', function()
{

  // 2020-05-26
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoice_lines` ADD `customer_shipping_slip_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `customer_invoice_id`;");


  // 2020-05-25

  // $table->string('shipment_service_type_tag', 32)->nullable();
  
  // Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `shipment_service_type_tag` varchar(32) NULL DEFAULT NULL AFTER `shipment_status`;");
  
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `customer_logo`;");
  
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `shipment_service_type_tag`;");



  // 2020-05-22
    Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoices` ADD `production_sheet_id` INT(10) UNSIGNED NULL AFTER `posted_at`;");


  die('OK');


  // 2020-03-11
  \App\Configuration::updateValue('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY', '0');

  die('OK');


  // 2020-03-02
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `cart_lines` ADD `pmu_label` varchar(128) null AFTER `pmu_conversion_rate`;");

  $tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

  foreach ($tables as $table) {
    # code...
    Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `pmu_label` varchar(128) null AFTER `pmu_conversion_rate`;");

  }

  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_orders` ADD `onhold` TINYINT(4) NOT NULL DEFAULT '0' AFTER `status`;");




  Illuminate\Support\Facades\DB::statement("CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

  Illuminate\Support\Facades\DB::statement("ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);
");

  Illuminate\Support\Facades\DB::statement("ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
  ");
  


  Illuminate\Support\Facades\DB::statement("CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

  Illuminate\Support\Facades\DB::statement("ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);
");

  Illuminate\Support\Facades\DB::statement("ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
  ");


    Illuminate\Support\Facades\DB::statement("ALTER TABLE `measure_units` CHANGE `conversion_rate` `type_conversion_rate` DECIMAL(20,6) NOT NULL DEFAULT '1.000000';");

    
    Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `position` INT(10) NOT NULL DEFAULT '0' AFTER `name`;");



  die('OK');

});


/* ********************************************************** */

