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


Route::get('notify', function( )
{
  // $user->notify(new InvoicePaid($invoice));

  // Notification::send($users, new InvoicePaid($invoice));

  $user = \App\User::findOrFail(6);

  $user->notify(new \App\Notifications\AbccCustomerOrderPlacedNotification());

  abi_r('Done.');


});


/* ********************************************************** */


Route::get('migratethis_gmdis', function()
{

  
  // 2020-06-02

  // 2020-05-26
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoice_lines` ADD `customer_shipping_slip_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `customer_invoice_id`;");


  // 2020-05-25

  // $table->string('shipment_service_type_tag', 32)->nullable();
  
  // ** Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `shipment_service_type_tag` varchar(32) NULL DEFAULT NULL AFTER `shipment_status`;");
  
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `customer_logo`;");
  
  Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_shipping_slips` ADD `is_invoiceable` INT(10) UNSIGNED NOT NULL DEFAULT '1' AFTER `shipment_service_type_tag`;");



  // 2020-05-22
    Illuminate\Support\Facades\DB::statement("ALTER TABLE `customer_invoices` ADD `production_sheet_id` INT(10) UNSIGNED NULL AFTER `posted_at`;");


  // 2020-06-02 ENDS

  die('OK');


  // 2020-03-11
  \App\Configuration::updateValue('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY', '1');

  die('OK');


});


/* ********************************************************** */

