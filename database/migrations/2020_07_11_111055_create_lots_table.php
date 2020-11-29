<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 32)->nullable();

            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('combination_id')->unsigned()->nullable();

            $table->decimal('quantity_initial', 20, 6)->default(0);
            $table->decimal('quantity', 20, 6)->default(0);

            $table->integer('measure_unit_id')->unsigned()->nullable(false);            // Default is Product Measure Unit

            $table->integer('package_measure_unit_id')->unsigned()->nullable();         // Measure unit used to bundle items
            $table->decimal('pmu_conversion_rate', 20, 6)->nullable()->default(1.0);    // Conversion rates are calculated from one unit of your main measura unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, type "6" (since a pack of six bottles will contain six bottles)

            $table->timestamp('manufactured_at')->nullable()->default(NULL);
            $table->timestamp('expiry_at')->nullable()->default(NULL);            

            $table->tinyInteger('blocked')->default(1);                 // Stock Movements not allowed

            $table->text('notes')->nullable();

            $table->integer('warehouse_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('lots');
    }
}
