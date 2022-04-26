<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCountLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('stock_count_lines');
        
        Schema::create('stock_count_lines', function (Blueprint $table) {
            $table->increments('id');
            // $table->timestamp('date');

//            $table->decimal('quantity_before_count', 20, 6);
            $table->decimal('quantity', 20, 6);
            $table->decimal('cost_price', 20, 6)->nullable();
            $table->decimal('cost_average', 20, 6)->nullable();
            $table->decimal('last_purchase_price', 20, 6)->nullable();

            $table->integer('stock_count_id')->unsigned()->nullable(false);
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);
  //          $table->integer('warehouse_id')->unsigned()->nullable(false);
            $table->integer('user_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('stock_count_lines');
    }
}
