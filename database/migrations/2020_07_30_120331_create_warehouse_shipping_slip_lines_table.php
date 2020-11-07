<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseShippingSlipLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('warehouse_shipping_slip_lines');

        Schema::create('warehouse_shipping_slip_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 
            // $table->string('line_type', 32)->nullable(false);       // product, service, shipping, discount, comment

            // Allow kind of services???
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

            $table->decimal('quantity', 20, 6);
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->integer('package_measure_unit_id')->unsigned()->nullable();         // Measure unit used to bundle items
            $table->decimal('pmu_conversion_rate', 20, 6)->nullable()->default(1.0);    // Conversion rates are calculated from one unit of your main measura unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, type "6" (since a pack of six bottles will contain six bottles)

            $table->text('notes')->nullable();
            
            $table->tinyInteger('locked')->default(0);                          // 0 -> NO; 1 -> Yes (line is after a shipping-slip => should not mofify quantity).


            // Parent Document
            $table->integer('warehouse_shipping_slip_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('warehouse_shipping_slip_lines');
    }
}
