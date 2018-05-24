<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWooOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('woo_orders');

        Schema::create('woo_orders', function (Blueprint $table) {
//           $table->increments('id');
            $table->integer('id')->unsigned();
            $table->primary('id');

            $table->string('number');
            $table->string('order_key');
            $table->string('currency');

            $table->dateTime('date_created');
            $table->dateTime('date_paid');              // 
            $table->dateTime('date_abi_exported');      // Not needed! This is "created_at" date
            $table->dateTime('date_abi_invoiced');

            $table->string('total');
            $table->string('total_tax');
            
            $table->integer('customer_id')->unsigned();
            $table->string('customer_note');

            $table->string('payment_method');
            $table->string('payment_method_title');
            $table->string('shipping_method_id');
            $table->string('shipping_method_title');

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
        Schema::dropIfExists('woo_orders');
    }
}
