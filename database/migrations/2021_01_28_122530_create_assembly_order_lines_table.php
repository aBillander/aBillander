<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssemblyOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('assembly_order_lines');

        Schema::create('assembly_order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

            $table->decimal('pack_item_quantity', 20, 6);       // According to Pack definition
            $table->decimal('required_quantity', 20, 6);        // According to Order Finished Product planned quantity
            $table->decimal('real_quantity', 20, 6);            // Real consumption
            $table->integer('measure_unit_id')->unsigned()->nullable(false);
            
            $table->integer('warehouse_id')->unsigned()->nullable();
            
            $table->integer('assembly_order_id')->unsigned()->nullable(false);
            
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
        Schema::dropIfExists('assembly_order_lines');
    }
}
