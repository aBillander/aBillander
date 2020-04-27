<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOrderTemplateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_order_template_lines', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 
            $table->string('line_type', 32)->nullable(false);       // product, service, shipping, discount, comment

            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();

            $table->decimal('quantity', 20, 6);
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->integer('package_measure_unit_id')->unsigned()->nullable();         // Measure unit used to bundle items
            $table->decimal('pmu_conversion_rate', 20, 6)->nullable()->default(1.0);    // Conversion rates are calculated from one unit of your main measura unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, type "6" (since a pack of six bottles will contain six bottles)
            $table->string('pmu_label', 128)->nullable();

            $table->text('notes')->nullable();

            // Parent Document
            $table->integer('customer_order_template_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('customer_order_template_lines');
    }
}
