<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_order_lines');

        Schema::create('customer_order_lines', function (Blueprint $table) {
            $table->increments('id');
//            $table->integer('line_sort_order')->nullable();         // To sort lines 
//            $table->string('line_type', 32)->nullable(false);       // product, service, shipping, discount, comment

            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('woo_product_id')->unsigned()->nullable();
            $table->integer('woo_variation_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);
            $table->decimal('quantity', 20, 6);

            $table->integer('customer_order_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('customer_order_lines');
    }
}
