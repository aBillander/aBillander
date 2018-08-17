<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('stock_counts');

        Schema::create('stock_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('document_date');
//            $table->integer('sequence_id')->unsigned()->nullable(false);
//            $table->string('document_prefix', 8)->nullable();                    // From Sequence. Needed for index.
//            $table->integer('document_id')->unsigned()->default(0);
//            $table->string('document_reference', 64);                           // document_prefix + document_id of model_name (or supplier reference, etc.)
            
            $table->string('name', 128)->nullable();
            $table->text('notes')->nullable();

            $table->integer('warehouse_id')->unsigned()->nullable(false);
            $table->tinyInteger('initial_inventory')->default(0);               // Is initial Inventory?

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
        Schema::dropIfExists('stock_counts');
    }
}
