<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pack_items');

        Schema::create('pack_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 
            // $table->string('line_type', 32)->nullable(false);       // product, service, shipping, discount, comment

            // Allow kind of services???
            $table->integer('item_product_id')->unsigned()->nullable();
            $table->integer('item_combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

            $table->decimal('quantity', 20, 6);
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->integer('package_measure_unit_id')->unsigned()->nullable();         // Measure unit used to bundle items
            $table->decimal('pmu_conversion_rate', 20, 6)->nullable()->default(1.0);    // Conversion rates are calculated from one unit of your main measura unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, type "6" (since a pack of six bottles will contain six bottles)

            $table->text('notes')->nullable();

            $table->integer('product_id')->unsigned();

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
        Schema::dropIfExists('pack_items');
    }
}
