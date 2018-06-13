<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('production_order_lines');

        Schema::create('production_order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 32)->nullable(false)->default('product');
            // 'product'   => Regular product.
            // 'assembly'  => Assembly.
            // 'phantom'   => Fhantom product. <= Not possible!
            // '......'    => ?????. 
            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

            $table->decimal('base_quantity', 20, 6);            // According to BOM
            $table->decimal('required_quantity', 20, 6);        // According to Order Finished Product planned quantity
            $table->integer('measure_unit_id')->unsigned()->nullable(false);
            
            $table->integer('warehouse_id')->unsigned()->nullable();
            
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
        Schema::dropIfExists('production_order_lines');
    }
}
