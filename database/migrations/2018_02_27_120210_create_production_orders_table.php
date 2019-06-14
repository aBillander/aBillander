<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('production_orders');
        
        Schema::create('production_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sequence_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
            $table->string('reference')->nullable();                            // Project reference, etc.

            $table->string('created_via', 32)->default('manual')->nullable();   
                    // 'manual', 'Production Order Explosion (manufacturing)', 'Sales Order (sales)', 'Web Shop' (webshop)', 'Sales Forecast (forecast)', 'Reorder Point (reorder)'

            $table->string('status', 32)->default('released')->nullable(false);      // 'Simulated', Planned', 'Firm Planned', 'Released', 'Finished'


            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('product_reference', 32)->nullable();
            $table->string('product_name', 128)->nullable(false);
            $table->string('procurement_type', 32)->nullable(false)->default('manufacture');

            $table->decimal('planned_quantity', 20, 6);
            $table->decimal('finished_quantity', 20, 6);
            $table->integer('product_bom_id')->unsigned()->nullable();

            $table->date('due_date');
            $table->integer('schedule_sort_order')->nullable();  // To schedule orders. Use multiple of 10. Lower value means higher priority (is manufactured first) 

            $table->text('notes')->nullable();

            $table->integer('work_center_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();

            $table->integer('production_sheet_id')->unsigned()->nullable();

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
        Schema::dropIfExists('production_orders');
    }
}
