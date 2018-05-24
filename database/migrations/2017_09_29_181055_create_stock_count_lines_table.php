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

            $table->decimal('quantity', 20, 6);
            $table->decimal('quantity_counted', 20, 6);
            $table->decimal('price', 20, 6);

            $table->integer('stock_count_id')->unsigned()->nullable(false);
            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('combination_id')->unsigned()->nullable(false)->default(0);
            $table->integer('warehouse_id')->unsigned()->nullable(false);
            $table->integer('user_id')->unsigned()->nullable(false)->default(0);

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
