<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cart_lines');

        Schema::create('cart_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 

            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);
            
            $table->decimal('quantity', 20, 6);
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->decimal('unit_customer_price', 20, 6)->default(0.0);        // Calculated custom for customer (initial price for customer)

            $table->decimal('tax_percent', 8, 3)->default(0.0);                 // Tax percent

            $table->integer('cart_id')->unsigned()->nullable(false);
            $table->integer('tax_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('cart_lines');
    }
}
