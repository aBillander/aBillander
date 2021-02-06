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

Route::get('f0', function( )
{
	// $f=\App\AssemblyOrder::find(3)->delete();
	// $f=\App\AssemblyOrder::find(2)->delete();

	// die();
	$product = \App\Product::with('packitems')
        					   ->with('packitems.product')
                               ->findOrFail( 322 );

        // if (!$bom) return NULL;

         $order_quantity = -12;
	
	$data = [
//            'created_via' => $data['created_via'] ?? 'manual',
//            'status'      => $data['status']      ?? 'released',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,

//            'required_quantity' => $order_required,
            'planned_quantity' => $order_quantity,
            // 'finished_quantity'

            'measure_unit_id' => $product->measure_unit_id,

            'due_date' => \Carbon\Carbon::now(),
            // 'finish_date'

//            'notes' => $data['notes'] ?? null,

            'work_center_id' => $product->work_center_id,

//            'manufacturing_batch_size' => $order_manufacturing_batch_size,
            'warehouse_id' => \App\Configuration::get('DEF_WAREHOUSE'),
        ];
	
    $f=\App\AssemblyOrder::createWithLines($data);

    $f->finish();

});


/* ********************************************************** */

Route::get('iban', function( )
{
	
    $iban = 'ES7620770024003102575766';

    $result = (int) \App\BankAccount::esCheckIBAN($iban);

    echo $result;
});


/* ********************************************************** */

Route::get('cc', function( )
{
	
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
});

/* ********************************************************** */

Route::get('lm', function( )
{
	// http://zetcode.com/php/carbon/

	// $p = \App\Product::find(141);
	$ps = \App\Product::with('latestStockmovement')->get();

	foreach ($ps as $p) {
		# code...
		$m = $p->latestStockmovement;
	
		if ($m)
		if ( \Carbon\Carbon::now()->subDays(2)->gte( $m->created_at ) ) {

			$m->cost_price_after_movement = $p->cost_average;
			$m->product_cost_price = $p->cost_price;
			$m->save();

			// abi_r($p);
			// abi_r($m);
		}
	}
});

/* ********************************************************** */


Route::get('tst', function( )
{
	$documents = \App\CustomerShippingSlip::whereIn('id', [4879, 4929, 4970, 5062])->get();

        foreach ($documents as $document)
        {
            # code...
            $document->payment_method_id = $document->getPaymentMethodId();
        }

	abi_r($documents->pluck('payment_method_id', 'id')->all());

        // Group by payment method
        $pmethods = $documents->unique('payment_method_id')->pluck('payment_method_id')->all();

    abi_r($pmethods);
});

/* ********************************************************** */


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

	// 2021-02-05


	Illuminate\Support\Facades\DB::statement("ALTER TABLE `cheque_details` ADD `payment_id` INT(10) UNSIGNED NULL AFTER `amount`;");

	die('OK');

	// 2021-01-28

	Illuminate\Support\Facades\DB::statement("create table `assembly_orders` (`id` int unsigned not null auto_increment primary key, `sequence_id` int unsigned null, `document_prefix` varchar(8) null, `document_id` int unsigned not null default '0', `document_reference` varchar(64) null, `reference` varchar(191) null, `created_via` varchar(32) null default 'manual', `status` varchar(32) not null default 'released', `product_id` int unsigned null, `combination_id` int unsigned null, `product_reference` varchar(32) null, `product_name` varchar(128) not null, `required_quantity` decimal(20, 6) not null, `planned_quantity` decimal(20, 6) not null, `finished_quantity` decimal(20, 6) not null, `measure_unit_id` int unsigned null, `due_date` date null, `finish_date` timestamp null, `notes` text null, `work_center_id` int unsigned null, `manufacturing_batch_size` int unsigned not null default '1', `warehouse_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

	Illuminate\Support\Facades\DB::statement("create table `assembly_order_lines` (`id` int unsigned not null auto_increment primary key, `product_id` int unsigned not null, `combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `pack_item_quantity` decimal(20, 6) not null, `required_quantity` decimal(20, 6) not null, `real_quantity` decimal(20, 6) not null, `measure_unit_id` int unsigned not null, `warehouse_id` int unsigned null, `assembly_order_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	// 2020-12-29	
	Illuminate\Support\Facades\DB::statement("create table `pack_items` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `item_product_id` int unsigned null, `item_combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `quantity` decimal(20, 6) not null, `measure_unit_id` int unsigned not null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `notes` text null, `product_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

	die('OK');


	// 2021-01-15

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `vat_regime` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `automatic_invoice`;");

	// 2021-01-12

	\App\Configuration::updateValue('CURRENCY_CONVERTER_API_KEY', 'b16ed3cf847d6dbed728');


	die('OK');
	

	// 2021-01-02

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `suppliers` ADD `outstanding_amount` DECIMAL(20,6) NOT NULL DEFAULT '0.0' AFTER `creditor`;");


	// 2020-12-29	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_shipping_slips` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `shipment_status`;");

	die('OK');


	// 2020-12-21
/*
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `contacts` CHANGE `email` `email` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `parties` CHANGE `email` `email` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `parties` CHANGE `name_commercial` `name_commercial` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `parties` CHANGE `name_fiscal` `name_fiscal` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
*/
	
	Illuminate\Support\Facades\DB::statement("drop table if exists `parties`;");
	Illuminate\Support\Facades\DB::statement("drop table if exists `contacts`;");
	Illuminate\Support\Facades\DB::statement("drop table if exists `leads`;");
	Illuminate\Support\Facades\DB::statement("drop table if exists `lead_lines`;");
	

	// 2020-12-21
	
	Illuminate\Support\Facades\DB::statement("create table `parties` (`id` int unsigned not null auto_increment primary key, `name_fiscal` varchar(128) null, `name_commercial` varchar(64) not null, `type` varchar(32) not null default 'partner', `identification` varchar(64) null, `email` varchar(191) null, `phone` varchar(32) null, `phone_mobile` varchar(32) null, `address` varchar(191) null, `website` varchar(128) null, `blocked` tinyint not null default '0', `active` tinyint not null default '1', `notes` text null, `user_created_by_id` int unsigned not null, `user_assigned_to_id` int unsigned null, `customer_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `contacts` (`id` int unsigned not null auto_increment primary key, `firstname` varchar(32) not null, `lastname` varchar(32) null, `job_title` varchar(191) null, `email` varchar(191) null, `phone` varchar(32) null, `phone_mobile` varchar(32) null, `address` varchar(191) null, `website` varchar(128) null, `blocked` tinyint not null default '0', `active` tinyint not null default '1', `notes` text null, `user_created_by_id` int unsigned not null, `party_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `leads` (`id` int unsigned not null auto_increment primary key, `name` varchar(191) not null, `description` text null, `status` varchar(32) not null default 'pending', `lead_date` datetime null, `lead_end_date` datetime null, `notes` text null, `user_created_by_id` int unsigned not null, `user_assigned_to_id` int unsigned null, `party_id` int unsigned not null, `contact_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `lead_lines` (`id` int unsigned not null auto_increment primary key, `name` varchar(191) not null, `description` text null, `status` varchar(32) not null default 'pending', `start_date` datetime null, `due_date` datetime null, `finish_date` datetime null, `results` text null, `position` int unsigned not null default '0', `user_created_by_id` int unsigned not null, `user_assigned_to_id` int unsigned null, `lead_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	// 2020-12-18

	\App\Configuration::updateValue('FILE_ALLOWED_EXTENSIONS', 'pdf,jpg,jpeg,png,docx');
	

	// 2020-12-17
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_orders` ADD `backordered_at` datetime NULL AFTER `fulfillment_status`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_orders` ADD `aggregated_at` datetime NULL AFTER `fulfillment_status`;");
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `supplier_orders` ADD `shipping_slip_at` datetime NULL AFTER `fulfillment_status`;");


	die('OK');


	// 2020-12-07
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `lot_quantity_after_movement` DECIMAL(20,6) NULL DEFAULT NULL AFTER `quantity_after_movement`;");

	// 2020-12-04
	
//	Illuminate\Support\Facades\DB::statement("drop table if exists `model_attachments`;");
	
	Illuminate\Support\Facades\DB::statement("create table `model_attachments` (`id` int unsigned not null auto_increment primary key, `name` varchar(128) null, `description` text null, `position` int unsigned not null default '0', `filename` varchar(128) not null, `attachmentable_id` int unsigned not null, `attachmentable_type` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	

	// 2020-11-29
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `lots` ADD `blocked` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `expiry_at`;");
	


	die('OK');

	// 2020-11-22

	$payment_type = \App\PaymentType::where('name', 'Remesa')->first();
	if ($payment_type)
		\App\Configuration::updateValue('DEF_SEPA_PAYMENT_TYPE', $payment_type->id);

abi_r($payment_type);
	die('OK');


	// 2020-11-18

/*
	CreateSupplierShippingSlipsTable: drop table if exists `supplier_shipping_slips`
CreateSupplierShippingSlipsTable: create table `supplier_shipping_slips` (`id` int unsigned not null auto_increment primary key, `company_id` int unsigned not null default '0', `supplier_id` int unsigned null, `user_id` int unsigned not null default '0', `sequence_id` int unsigned null, `document_prefix` varchar(8) null, `document_id` int unsigned not null default '0', `document_reference` varchar(64) null, `reference` varchar(191) null, `reference_supplier` varchar(32) null, `reference_external` varchar(32) null, `created_via` varchar(32) null default 'manual', `document_date` datetime not null, `payment_date` datetime null, `validation_date` datetime null, `delivery_date` datetime null, `delivery_date_real` datetime null, `close_date` datetime null, `document_discount_percent` decimal(20, 6) not null default '0', `document_discount_amount_tax_incl` decimal(20, 6) not null default '0', `document_discount_amount_tax_excl` decimal(20, 6) not null default '0', `document_ppd_percent` decimal(20, 6) not null default '0', `document_ppd_amount_tax_incl` decimal(20, 6) not null default '0', `document_ppd_amount_tax_excl` decimal(20, 6) not null default '0', `number_of_packages` smallint unsigned not null default '1', `volume` decimal(20, 6) null default '0', `weight` decimal(20, 6) null default '0', `shipping_conditions` text null, `tracking_number` varchar(191) null, `currency_conversion_rate` decimal(20, 6) not null default '1', `down_payment` decimal(20, 6) not null default '0', `total_discounts_tax_incl` decimal(20, 6) not null default '0', `total_discounts_tax_excl` decimal(20, 6) not null default '0', `total_products_tax_incl` decimal(20, 6) not null default '0', `total_products_tax_excl` decimal(20, 6) not null default '0', `total_shipping_tax_incl` decimal(20, 6) not null default '0', `total_shipping_tax_excl` decimal(20, 6) not null default '0', `total_other_tax_incl` decimal(20, 6) not null default '0', `total_other_tax_excl` decimal(20, 6) not null default '0', `total_lines_tax_incl` decimal(20, 6) not null default '0', `total_lines_tax_excl` decimal(20, 6) not null default '0', `total_currency_tax_incl` decimal(20, 6) not null default '0', `total_currency_tax_excl` decimal(20, 6) not null default '0', `total_currency_paid` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `commission_amount` decimal(20, 6) not null default '0', `notes_from_supplier` text null, `notes` text null, `notes_to_supplier` text null, `status` varchar(32) not null default 'draft', `onhold` tinyint not null default '0', `locked` tinyint not null default '0', `invoicing_address_id` int unsigned not null, `shipping_address_id` int unsigned null, `warehouse_id` int unsigned null, `shipping_method_id` int unsigned null, `carrier_id` int unsigned null, `sales_rep_id` int unsigned null, `currency_id` int unsigned not null, `payment_method_id` int unsigned not null, `template_id` int null, `export_date` datetime null, `secure_key` varchar(32) not null, `import_key` varchar(16) null, `created_at` timestamp null, `updated_at` timestamp null, `shipment_status` varchar(32) not null default 'pending', `invoiced_at` date null, `prices_entered_with_tax` tinyint not null default '0', `round_prices_with_tax` tinyint not null default '0') default character set utf8mb4 collate utf8mb4_unicode_ci
CreateSupplierShippingSlipLinesTable: drop table if exists `supplier_shipping_slip_lines`
CreateSupplierShippingSlipLinesTable: create table `supplier_shipping_slip_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `line_type` varchar(32) not null, `product_id` int unsigned null, `combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `quantity` decimal(20, 6) not null, `extra_quantity` decimal(20, 6) null default '0', `extra_quantity_label` varchar(128) null, `measure_unit_id` int unsigned not null, `lot_references` varchar(128) null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `pmu_label` varchar(128) null, `prices_entered_with_tax` tinyint not null default '0', `cost_price` decimal(20, 6) not null default '0', `cost_average` decimal(20, 6) not null default '0', `unit_price` decimal(20, 6) not null default '0', `unit_supplier_price` decimal(20, 6) not null default '0', `unit_supplier_final_price` decimal(20, 6) not null default '0', `unit_supplier_final_price_tax_inc` decimal(20, 6) not null default '0', `unit_final_price` decimal(20, 6) not null default '0', `unit_final_price_tax_inc` decimal(20, 6) not null default '0', `sales_equalization` tinyint not null default '0', `discount_percent` decimal(8, 3) not null default '0', `discount_amount_tax_incl` decimal(20, 6) not null default '0', `discount_amount_tax_excl` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `tax_percent` decimal(8, 3) not null default '0', `ecotax_amount` decimal(20, 6) not null default '0', `ecotax_total_amount` decimal(20, 6) not null default '0', `commission_percent` decimal(8, 3) not null default '0', `notes` text null, `locked` tinyint not null default '0', `tax_id` int unsigned not null, `ecotax_id` int unsigned null, `sales_rep_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null, `supplier_shipping_slip_id` int unsigned not null) default character set utf8mb4 collate utf8mb4_unicode_ci
*/

	// 2020-11-09

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `new_since_date` date NULL DEFAULT NULL AFTER `available_for_sale_date`;");


	// 2020-10-31

	Illuminate\Support\Facades\DB::statement("create table `supplier_orders` (`id` int unsigned not null auto_increment primary key, `company_id` int unsigned not null default '0', `supplier_id` int unsigned null, `user_id` int unsigned not null default '0', `sequence_id` int unsigned null, `document_prefix` varchar(8) null, `document_id` int unsigned not null default '0', `document_reference` varchar(64) null, `reference` varchar(191) null, `reference_supplier` varchar(32) null, `reference_external` varchar(32) null, `created_via` varchar(32) null default 'manual', `document_date` datetime not null, `payment_date` datetime null, `validation_date` datetime null, `delivery_date` datetime null, `delivery_date_real` datetime null, `close_date` datetime null, `document_discount_percent` decimal(20, 6) not null default '0', `document_discount_amount_tax_incl` decimal(20, 6) not null default '0', `document_discount_amount_tax_excl` decimal(20, 6) not null default '0', `document_ppd_percent` decimal(20, 6) not null default '0', `document_ppd_amount_tax_incl` decimal(20, 6) not null default '0', `document_ppd_amount_tax_excl` decimal(20, 6) not null default '0', `number_of_packages` smallint unsigned not null default '1', `volume` decimal(20, 6) null default '0', `weight` decimal(20, 6) null default '0', `shipping_conditions` text null, `tracking_number` varchar(191) null, `currency_conversion_rate` decimal(20, 6) not null default '1', `down_payment` decimal(20, 6) not null default '0', `total_discounts_tax_incl` decimal(20, 6) not null default '0', `total_discounts_tax_excl` decimal(20, 6) not null default '0', `total_products_tax_incl` decimal(20, 6) not null default '0', `total_products_tax_excl` decimal(20, 6) not null default '0', `total_shipping_tax_incl` decimal(20, 6) not null default '0', `total_shipping_tax_excl` decimal(20, 6) not null default '0', `total_other_tax_incl` decimal(20, 6) not null default '0', `total_other_tax_excl` decimal(20, 6) not null default '0', `total_lines_tax_incl` decimal(20, 6) not null default '0', `total_lines_tax_excl` decimal(20, 6) not null default '0', `total_currency_tax_incl` decimal(20, 6) not null default '0', `total_currency_tax_excl` decimal(20, 6) not null default '0', `total_currency_paid` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `commission_amount` decimal(20, 6) not null default '0', `notes_from_supplier` text null, `notes` text null, `notes_to_supplier` text null, `status` varchar(32) not null default 'draft', `onhold` tinyint not null default '0', `locked` tinyint not null default '0', `invoicing_address_id` int unsigned null, `shipping_address_id` int unsigned null, `warehouse_id` int unsigned null, `shipping_method_id` int unsigned null, `carrier_id` int unsigned null, `sales_rep_id` int unsigned null, `currency_id` int unsigned not null, `payment_method_id` int unsigned not null, `template_id` int null, `export_date` datetime null, `secure_key` varchar(32) not null, `import_key` varchar(16) null, `created_at` timestamp null, `updated_at` timestamp null, `fulfillment_status` varchar(32) not null default 'pending') default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `supplier_order_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `line_type` varchar(32) not null, `product_id` int unsigned null, `combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `quantity` decimal(20, 6) not null, `extra_quantity` decimal(20, 6) null default '0', `extra_quantity_label` varchar(128) null, `measure_unit_id` int unsigned not null, `lot_references` varchar(128) null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `pmu_label` varchar(128) null, `prices_entered_with_tax` tinyint not null default '0', `cost_price` decimal(20, 6) not null default '0', `cost_average` decimal(20, 6) not null default '0', `unit_price` decimal(20, 6) not null default '0', `unit_supplier_price` decimal(20, 6) not null default '0', `unit_supplier_final_price` decimal(20, 6) not null default '0', `unit_supplier_final_price_tax_inc` decimal(20, 6) not null default '0', `unit_final_price` decimal(20, 6) not null default '0', `unit_final_price_tax_inc` decimal(20, 6) not null default '0', `sales_equalization` tinyint not null default '0', `discount_percent` decimal(8, 3) not null default '0', `discount_amount_tax_incl` decimal(20, 6) not null default '0', `discount_amount_tax_excl` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `tax_percent` decimal(8, 3) not null default '0', `ecotax_amount` decimal(20, 6) not null default '0', `ecotax_total_amount` decimal(20, 6) not null default '0', `commission_percent` decimal(8, 3) not null default '0', `notes` text null, `locked` tinyint not null default '0', `tax_id` int unsigned not null, `ecotax_id` int unsigned null, `sales_rep_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null, `supplier_order_id` int unsigned not null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `supplier_order_line_taxes` (`id` int unsigned not null auto_increment primary key, `name` varchar(128) not null, `tax_rule_type` varchar(32) not null, `taxable_base` decimal(20, 6) not null default '0', `percent` decimal(8, 3) not null default '0', `amount` decimal(20, 6) not null default '0', `total_line_tax` decimal(20, 6) not null default '0', `position` int unsigned not null default '0', `tax_id` int unsigned not null, `tax_rule_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null, `supplier_order_line_id` int unsigned not null) default character set utf8mb4 collate utf8mb4_unicode_ci;");

	Illuminate\Support\Facades\DB::statement("create table `supplier_invoices` (`id` int unsigned not null auto_increment primary key, `company_id` int unsigned not null default '0', `supplier_id` int unsigned null, `user_id` int unsigned not null default '0', `sequence_id` int unsigned null, `document_prefix` varchar(8) null, `document_id` int unsigned not null default '0', `document_reference` varchar(64) null, `reference` varchar(191) null, `reference_supplier` varchar(32) null, `reference_external` varchar(32) null, `created_via` varchar(32) null default 'manual', `document_date` datetime not null, `payment_date` datetime null, `validation_date` datetime null, `delivery_date` datetime null, `delivery_date_real` datetime null, `close_date` datetime null, `document_discount_percent` decimal(20, 6) not null default '0', `document_discount_amount_tax_incl` decimal(20, 6) not null default '0', `document_discount_amount_tax_excl` decimal(20, 6) not null default '0', `document_ppd_percent` decimal(20, 6) not null default '0', `document_ppd_amount_tax_incl` decimal(20, 6) not null default '0', `document_ppd_amount_tax_excl` decimal(20, 6) not null default '0', `number_of_packages` smallint unsigned not null default '1', `volume` decimal(20, 6) null default '0', `weight` decimal(20, 6) null default '0', `shipping_conditions` text null, `tracking_number` varchar(191) null, `currency_conversion_rate` decimal(20, 6) not null default '1', `down_payment` decimal(20, 6) not null default '0', `total_discounts_tax_incl` decimal(20, 6) not null default '0', `total_discounts_tax_excl` decimal(20, 6) not null default '0', `total_products_tax_incl` decimal(20, 6) not null default '0', `total_products_tax_excl` decimal(20, 6) not null default '0', `total_shipping_tax_incl` decimal(20, 6) not null default '0', `total_shipping_tax_excl` decimal(20, 6) not null default '0', `total_other_tax_incl` decimal(20, 6) not null default '0', `total_other_tax_excl` decimal(20, 6) not null default '0', `total_lines_tax_incl` decimal(20, 6) not null default '0', `total_lines_tax_excl` decimal(20, 6) not null default '0', `total_currency_tax_incl` decimal(20, 6) not null default '0', `total_currency_tax_excl` decimal(20, 6) not null default '0', `total_currency_paid` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `commission_amount` decimal(20, 6) not null default '0', `notes_from_supplier` text null, `notes` text null, `notes_to_supplier` text null, `status` varchar(32) not null default 'draft', `onhold` tinyint not null default '0', `locked` tinyint not null default '0', `invoicing_address_id` int unsigned not null, `shipping_address_id` int unsigned null, `warehouse_id` int unsigned null, `shipping_method_id` int unsigned null, `carrier_id` int unsigned null, `sales_rep_id` int unsigned null, `currency_id` int unsigned not null, `payment_method_id` int unsigned not null, `template_id` int null, `export_date` datetime null, `secure_key` varchar(32) not null, `import_key` varchar(16) null, `created_at` timestamp null, `updated_at` timestamp null, `type` varchar(32) not null default 'invoice', `payment_status` varchar(32) not null default 'pending', `next_due_date` date null, `posted_at` date null, `open_balance` decimal(20, 6) not null default '0', `parent_id` int unsigned null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `supplier_invoice_lines` (`id` int unsigned not null auto_increment primary key, `line_sort_order` int null, `line_type` varchar(32) not null, `product_id` int unsigned null, `combination_id` int unsigned null, `reference` varchar(32) null, `name` varchar(128) not null, `quantity` decimal(20, 6) not null, `extra_quantity` decimal(20, 6) null default '0', `extra_quantity_label` varchar(128) null, `measure_unit_id` int unsigned not null, `lot_references` varchar(128) null, `package_measure_unit_id` int unsigned null, `pmu_conversion_rate` decimal(20, 6) null default '1', `pmu_label` varchar(128) null, `prices_entered_with_tax` tinyint not null default '0', `cost_price` decimal(20, 6) not null default '0', `cost_average` decimal(20, 6) not null default '0', `unit_price` decimal(20, 6) not null default '0', `unit_supplier_price` decimal(20, 6) not null default '0', `unit_supplier_final_price` decimal(20, 6) not null default '0', `unit_supplier_final_price_tax_inc` decimal(20, 6) not null default '0', `unit_final_price` decimal(20, 6) not null default '0', `unit_final_price_tax_inc` decimal(20, 6) not null default '0', `sales_equalization` tinyint not null default '0', `discount_percent` decimal(8, 3) not null default '0', `discount_amount_tax_incl` decimal(20, 6) not null default '0', `discount_amount_tax_excl` decimal(20, 6) not null default '0', `total_tax_incl` decimal(20, 6) not null default '0', `total_tax_excl` decimal(20, 6) not null default '0', `tax_percent` decimal(8, 3) not null default '0', `ecotax_amount` decimal(20, 6) not null default '0', `ecotax_total_amount` decimal(20, 6) not null default '0', `commission_percent` decimal(8, 3) not null default '0', `notes` text null, `locked` tinyint not null default '0', `tax_id` int unsigned not null, `ecotax_id` int unsigned null, `sales_rep_id` int unsigned null, `created_at` timestamp null, `updated_at` timestamp null, `supplier_invoice_id` int unsigned not null, `supplier_shipping_slip_id` int unsigned null, `supplier_shipping_slip_line_id` int unsigned null) default character set utf8mb4 collate utf8mb4_unicode_ci;");
	Illuminate\Support\Facades\DB::statement("create table `supplier_invoice_line_taxes` (`id` int unsigned not null auto_increment primary key, `name` varchar(128) not null, `tax_rule_type` varchar(32) not null, `taxable_base` decimal(20, 6) not null default '0', `percent` decimal(8, 3) not null default '0', `amount` decimal(20, 6) not null default '0', `total_line_tax` decimal(20, 6) not null default '0', `position` int unsigned not null default '0', `tax_id` int unsigned not null, `tax_rule_id` int unsigned not null, `created_at` timestamp null, `updated_at` timestamp null, `supplier_invoice_line_id` int unsigned not null) default character set utf8mb4 collate utf8mb4_unicode_ci;");


	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `purchase_measure_unit_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `main_supplier_id`;");

	$products = \App\Product::get();

	foreach ($products as $product) {
		# code...
		$product->update(['purchase_measure_unit_id' => $product->measure_unit_id]);
	}

	die('OK');

	// 2020-10-28

	\App\Configuration::updateValue('SW_VERSION', '0.10.23');
	\App\Configuration::updateValue('SW_DATABASE_VERSION', '0.10.23');

	// 2020-10-07

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `suppliers` ADD `approval_number` varchar(64) NULL DEFAULT NULL AFTER `identification`;");

	die('OK');

	// 2020-09-30

	$tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

	foreach ($tables as $table) {
		# code...
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `cost_average`  DECIMAL(20,6) NOT NULL DEFAULT '0.0' AFTER `cost_price`;");
	}

	// 2020-09-25

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `sales_reps` ADD `sales_rep_type` varchar(32) NOT NULL DEFAULT 'external' AFTER `id`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `sales_reps` ADD `accounting_id` varchar(32) NULL DEFAULT NULL AFTER `reference_external`;");

	die('OK');

	// 2020-09-16

	$tables = ['customer_invoice', 'customer_shipping_slip', 'customer_quotation', 'customer_order'];

	foreach ($tables as $table) {
		# code...
		Illuminate\Support\Facades\DB::statement("ALTER TABLE `".$table."_lines` ADD `lot_references` varchar(128) NULL DEFAULT NULL AFTER `measure_unit_id`;");
	}

	// 2020-09-15
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_count_lines` ADD `last_purchase_price` DECIMAL(20,6) NULL DEFAULT NULL AFTER `cost_price`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_count_lines` ADD `cost_average` DECIMAL(20,6) NULL DEFAULT NULL AFTER `cost_price`;");

	die('OK');

	// 2020-09-10
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `lots` ADD `warehouse_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `notes`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `lot_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `name`;");

	// 2020-08-18
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `stock_movements` ADD `product_cost_price` DECIMAL(20,6) NULL DEFAULT NULL AFTER `cost_price_after_movement`;");

	die('OK');


	// 2020-08-15
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `automatic_invoice` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `is_invoiceable`;");

	die('OK');


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

