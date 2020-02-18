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


Route::get('migratethis_gmdis', function()
{
  // 2020-02-18
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `cart_lines` ADD `pmu_label` varchar(128) null AFTER `pmu_conversion_rate`;");

  $tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

  foreach ($tables as $table) {
    # code...
    Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `pmu_label` varchar(128) null AFTER `pmu_conversion_rate`;");

  }


  die('OK'); 


  // 2020-02-17
    
    Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `position` INT(10) NOT NULL DEFAULT '0' AFTER `name`;");


  // die('OK'); 

    
  $cs = \App\Category::where('parent_id', '>', '0')
                        ->with('products')
                        ->get();

  foreach ($cs as $c) {
    # code...
    $position = 10;
    foreach ($c->products->sortBy('name') as $product) {
      # code...
      $product->position = $position;
      $product->save();

      $position += 10;
    }
  }

 

  die('OK'); 


  Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `measure_unit_id` INT(10) UNSIGNED NULL AFTER `quantity`;");
 

  die('OK'); 

	// 2020-02-04
		Illuminate\Support\Facades\DB::statement("drop table if exists `supplier_price_list_lines`;");

		Illuminate\Support\Facades\DB::statement("create table `supplier_price_list_lines` (`id` int unsigned not null auto_increment primary key, `supplier_id` int unsigned not null, `product_id` int unsigned not null, `supplier_reference` varchar(32) null, `currency_id` int unsigned null, `price` decimal(20, 6) not null default '0', `discount_percent` decimal(8, 3) not null default '0', `discount_amount` decimal(20, 6) not null default '0', `from_quantity` decimal(20, 6) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	// die('OK');



	// 2020-01-31
	 \App\Configuration::updateValue('BUSINESS_NAME_TO_SHOW', 'commercial');


	// die('OK');


	// 2020-01-30
		Illuminate\Support\Facades\DB::statement("drop table if exists `supplier_shipping_slips`");

		Illuminate\Support\Facades\DB::statement("CREATE TABLE `supplier_shipping_slips` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sequence_id` int(10) UNSIGNED DEFAULT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_customer` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_external` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_via` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'manual',
  `document_date` datetime NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `validation_date` datetime DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `delivery_date_real` datetime DEFAULT NULL,
  `close_date` datetime DEFAULT NULL,
  `document_discount_percent` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_discount_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_discount_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_ppd_percent` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_ppd_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_ppd_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `number_of_packages` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `volume` decimal(20,6) DEFAULT '0.000000',
  `weight` decimal(20,6) DEFAULT '0.000000',
  `shipping_conditions` text COLLATE utf8mb4_unicode_ci,
  `tracking_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `down_payment` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_other_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_other_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_lines_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_lines_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_currency_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_currency_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_currency_paid` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `commission_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `notes_from_customer` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `notes_to_supplier` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `onhold` tinyint(4) NOT NULL DEFAULT '0',
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `invoicing_address_id` int(10) UNSIGNED NOT NULL,
  `shipping_address_id` int(10) UNSIGNED DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `shipping_method_id` int(10) UNSIGNED DEFAULT NULL,
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `export_date` datetime DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_key` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipment_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `invoiced_at` date DEFAULT NULL,
  `prices_entered_with_tax` tinyint(4) NOT NULL DEFAULT '0',
  `round_prices_with_tax` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_shipping_slips`
  ADD PRIMARY KEY (`id`);");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_shipping_slips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

		Illuminate\Support\Facades\DB::statement("drop table if exists `supplier_shipping_slip_lines`");

		Illuminate\Support\Facades\DB::statement("CREATE TABLE `supplier_shipping_slip_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_sort_order` int(11) DEFAULT NULL,
  `line_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `extra_quantity` decimal(20,6) DEFAULT '0.000000',
  `extra_quantity_label` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `package_measure_unit_id` int(10) UNSIGNED DEFAULT NULL,
  `pmu_conversion_rate` decimal(20,6) DEFAULT '1.000000',
  `prices_entered_with_tax` tinyint(4) NOT NULL DEFAULT '0',
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_supplier_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_supplier_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_supplier_final_price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_final_price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `sales_equalization` tinyint(4) NOT NULL DEFAULT '0',
  `discount_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `discount_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `discount_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `tax_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `ecotax_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `ecotax_total_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `commission_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `tax_id` int(10) UNSIGNED NOT NULL,
  `ecotax_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supplier_shipping_slip_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_shipping_slip_lines`
  ADD PRIMARY KEY (`id`);");

		Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_shipping_slip_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;");


	die('OK');


	// 2020-01-28
		Illuminate\Support\Facades\DB::statement("drop table if exists `suppliers`;");

		Illuminate\Support\Facades\DB::statement("create table `suppliers` (`id` int unsigned not null auto_increment primary key, `alias` varchar(32) null, `name_fiscal` varchar(128) not null, `name_commercial` varchar(64) null, `website` varchar(128) null, `customer_center_url` varchar(128) null, `customer_center_user` varchar(128) null, `customer_center_password` varchar(16) null, `identification` varchar(64) null, `reference_external` varchar(32) null, `accounting_id` varchar(32) null, `discount_percent` decimal(20, 6) not null default '0', `discount_ppd_percent` decimal(20, 6) not null default '0', `payment_days` varchar(16) null, `delivery_time` tinyint not null default '0', `notes` text null, `customer_logo` varchar(128) null, `creditor` tinyint not null default '0', `sales_equalization` tinyint not null default '0', `approved` tinyint not null default '1', `blocked` tinyint not null default '0', `active` tinyint not null default '1', `customer_id` int unsigned null, `currency_id` int unsigned not null, `language_id` int unsigned not null, `payment_method_id` int unsigned null, `bank_account_id` int unsigned null, `invoice_sequence_id` int unsigned null, `invoicing_address_id` int unsigned not null, `secure_key` varchar(32) not null, `import_key` varchar(16) null, `created_at` timestamp null, `updated_at` timestamp null, `deleted_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	die('OK');



});


/* ********************************************************** */

