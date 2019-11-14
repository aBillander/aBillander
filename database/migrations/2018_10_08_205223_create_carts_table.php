<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('carts');
        
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_user_id')->unsigned()->nullable(false);
            $table->integer('customer_id')->unsigned()->nullable(false);
//            $table->integer('guest_id')->unsigned()->nullable();

//            $table->string('reference')->nullable();                            // Project reference, etc.
//            $table->string('reference_customer', 32)->nullable();         // Custumer order number 
            $table->text('notes_from_customer')->nullable();          // Notes FROM the Customer

            $table->timestamp('date_prices_updated')->nullable();       // Reference date for prices & Cart persistance

            $table->integer('total_items')->unsigned()->default(0);

//
            $table->decimal('total_products_tax_incl', 20, 6)->default(0.0);    // Product netto (product discount included!)
            $table->decimal('total_products_tax_excl', 20, 6)->default(0.0);

            $table->decimal('total_shipping_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_shipping_tax_excl', 20, 6)->default(0.0);

            $table->decimal('document_discount_percent', 20, 6)->default(0.0);  // Order/Document discount Percent
            $table->decimal('document_discount_amount_tax_incl', 20, 6)->default(0.0);   // Order/Document discount Amount
            $table->decimal('document_discount_amount_tax_excl', 20, 6)->default(0.0);

            $table->decimal('document_ppd_percent', 20, 6)->default(0.0);           // Order/Document prompt payment discount Percent
            $table->decimal('document_ppd_amount_tax_incl', 20, 6)->default(0.0);   // Order/Document prompt payment discount Amount
            $table->decimal('document_ppd_amount_tax_excl', 20, 6)->default(0.0);

            $table->decimal('total_currency_tax_incl', 20, 6)->default(0.0);    // Totals using Customer Order Currency
            $table->decimal('total_currency_tax_excl', 20, 6)->default(0.0);
            
            $table->decimal('total_tax_incl', 20, 6)->default(0.0);    // Totals using Company Currency
            $table->decimal('total_tax_excl', 20, 6)->default(0.0);


//
//            $table->decimal('total_currency_tax_excl', 20, 6)->default(0.0);
//            $table->decimal('total_tax_excl', 20, 6)->default(0.0);

            $table->integer('invoicing_address_id')->unsigned()->nullable();
            $table->integer('shipping_address_id')->unsigned()->nullable();     // For Shipping Slip!
//            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('shipping_method_id')->unsigned()->nullable();
            $table->integer('carrier_id')->unsigned()->nullable();
//            $table->integer('sales_rep_id')->unsigned()->nullable();             // Sales representative
            $table->integer('currency_id')->unsigned()->nullable(false);
            $table->integer('payment_method_id')->unsigned()->nullable();

            $table->string('secure_key', 32)->nullable(false);                  // = md5(uniqid(rand(), true))

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
