<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('production_requirements');

        Schema::create('production_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines
            $table->string('type', 32)->nullable(false)->default('product');    // Maybe future use!
            // 'product'   => Regular product.
            // 'assembly'  => Assembly.
            // 'phantom'   => Fhantom product. <= Not possible!
            // '......'    => ?????. 
            $table->string('created_via', 32)->default('manual')->nullable();    // Maybe future use!

            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

            $table->integer('product_bom_id')->unsigned()->nullable();            
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->decimal('required_quantity', 20, 6);
            $table->integer('manufacturing_batch_size')->unsigned()->default(1);

            $table->text('notes')->nullable();
            
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('work_center_id')->unsigned()->nullable();

            $table->integer('production_sheet_id')->unsigned()->nullable(false);    // Should be within a Production Sheet!

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
        Schema::dropIfExists('production_requirements');
    }
}
