<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrderToolLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('production_order_tool_lines');

        Schema::create('production_order_tool_lines', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tool_id')->unsigned()->nullable(false);
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

//            $table->decimal('base_quantity', 20, 6);            // Assumed equal to 1
            $table->decimal('quantity', 20, 6);

            $table->string('location', 64)->nullable();
            
            $table->integer('production_order_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('production_order_tool_lines');
    }
}
