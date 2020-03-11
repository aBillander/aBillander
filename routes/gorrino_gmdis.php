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
  // 2020-03-11
  \App\Configuration::updateValue('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY', '1');

  die('OK');


});


/* ********************************************************** */

