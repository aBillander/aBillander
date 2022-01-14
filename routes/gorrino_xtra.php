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

Route::get('tlot', function( )
{
	// abi_r(substr('z', -1), true);

	$a = null;
	$b = (string) $a;

	if ($b == '')
		echo 'OK';

	die();

	$date = \Carbon\Carbon::parse('2020-07-10 13:26:11.123789');

	$date2 = \Carbon\Carbon::parse($date)->addMonths(8);

	abi_r($date);
	abi_r($date2);

	$diff = $date2->diffInDays($date);
	abi_r($diff);

	abi_r(\App\Lot::ShortCaducity(\Carbon\Carbon::now()->subDays(20), null, '0d'));
});


/* ********************************************************** */

Route::get('cdate', function( )
{

	$date = \Carbon\Carbon::parse('2020-07-10');

	abi_r(\Carbon\Carbon::parse( \Carbon\Carbon::parse('2020-07-10') ));
});


/* ********************************************************** */

Route::get('arr', function( )
{
	abi_r(\Str::plural('child'));

	$array = [100, 200, 300];

	$first = \Arr::first($array, function ($value, $key) {
	    return $value >= 150;
	});

	abi_r($first);
});


/* ********************************************************** */

Route::get('f3', function( )
{
	$fs=[486, 205, 136];

	foreach ($fs as $fi)
	{
	    $f=\App\CustomerInvoice::find($fi);
	
	    echo $f->id.' - '.$f->open_balance.' - '.$f->payment_status;
	    
	    $f->checkPaymentStatus();
	
	    abi_r($f->open_balance);
	    abi_r($f->payment_status);
	    abi_r($f->payment_status_name);
	    abi_r('**********');
	}
});


/* ********************************************************** */


Route::get('xtra_state_id', function()
{
  // 2020-11-02

  die('OK');

});




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


Route::get('stid', function()
{


	if (file_exists(__DIR__.'/gorrino_routes_adata.php')) {
	    // $cdata
	    include __DIR__.'/gorrino_routes_adata.php';
	}

	// Customers
	$ads =\App\Address::where('addressable_type', 'App\Customer')->get();

	foreach ($adata as $key => $value) {
		# code...

		$c = $ads->where('id', $key)->first();

		$c->update(['state_id' => $value]);
		echo $key.' - '.$c->id.' :: '.$value.' - '.$c->state_id.'<br />';

	}


	die('OK');


	$ads =\App\Address::select('id', 'state_id')->where('addressable_type', 'App\Customer')->get();

	
	echo '$adata = [<br />';
	
	foreach ($ads as $ad) {
		# code...
		echo ' \''.$ad->id.'\''.' => '.'\''.$ad->state_id.'\''.',<br />';
	}

	echo '];<br />';

});




Route::get('wsid', function()
{

// laravel find duplicate records
// https://stackoverflow.com/questions/40888168/use-laravel-collection-to-get-duplicate-values
// https://laracasts.com/discuss/channels/general-discussion/finding-duplicate-data


	if (file_exists(__DIR__.'/gorrino_routes_cdata.php')) {
	    // $cdata
	    include __DIR__.'/gorrino_routes_cdata.php';
	}

	// Customers
	$cws=\App\Customer::get();

	foreach ($cdata as $key => $value) {
		# code...

		$c = $cws->where('id', $key)->first();

		$c->update(['webshop_id' => $value]);
		echo $key.' - '.$c->id.' :: '.$value.' - '.$c->webshop_id.'<br />';

	}


	die('OK');

	// 2020-01-16 Get raw data
	$cs=\App\Customer::select('id', 'reference_external', 'webshop_id')
						->orderBy('reference_external')
						->get();

	
	echo '$cdata = [<br />';
	
	foreach ($cs as $c) {
		# code...
		echo ' \''.$c->id.'\''.' => '.($c->webshop_id ? ('\''.$c->webshop_id.'\'') : 'null').',<br />';
	}

	echo '];<br />';

});


/* ********************************************************** */


/* ********************************************************** */


Route::get('migratethis_xtra', function()
{
	// 2022-01-14
	$date = '2022-01-14';

  \App\Configuration::updateValue('MRP_ONORDER_WITHOUT_REORDER', 1);

//  die('OK - '.$date);


	// 2021-07-13
	$date = '2021-07-13';
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `production_order_lines` ADD `line_sort_order` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `id`;");

	Illuminate\Support\Facades\DB::statement("ALTER TABLE `production_order_tool_lines` ADD `line_sort_order` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `id`;");

//  die('OK - '.$date);

  
  // 2021-04-27
	$date = '2021-04-27';
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `lot_policy` varchar(32) NOT NULL DEFAULT 'FIFO' AFTER `expiry_time`;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` ADD `lot_number_generator` VARCHAR(64) NOT NULL DEFAULT 'Default' AFTER `expiry_time`;");
	
	Illuminate\Support\Facades\DB::statement("ALTER TABLE `products` CHANGE `expiry_time` `expiry_time` VARCHAR(16) NULL DEFAULT NULL; ");

  die('OK - '.$date);


  // 2020-07-09
  Illuminate\Support\Facades\DB::statement("INSERT INTO `templates` ( `name`, `model_name`, `folder`, `file_name`, `paper`, `orientation`, `created_at`, `updated_at`, `deleted_at`) VALUES
( 'xtranat Albaranes', 'CustomerShippingSlipPdf', 'templates::', 'xtranat', 'A4', 'portrait', '2020-07-09 07:30:53', '2020-07-09 07:30:53', NULL);");

  $template = \App\Template::where('file_name', 'xtranat')->where('model_name', 'CustomerShippingSlipPdf')->first();

  \App\Configuration::updateValue('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE', $template->id);


  die('OK');

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

