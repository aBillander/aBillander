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
        Schema::create('stock_count_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');

            $table->decimal('quantity_before_count', 20, 6);
            $table->decimal('quantity', 20, 6);
            $table->decimal('cost_price', 20, 6);
            $table->decimal('cost_price_before_count', 20, 6);
            // What happen with cost_average if cost_price is set???

            $table->integer('stock_count_id')->unsigned()->nullable(false);
            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('combination_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable(false);
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
