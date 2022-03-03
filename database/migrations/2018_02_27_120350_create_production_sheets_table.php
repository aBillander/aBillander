<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('production_sheets');

        Schema::create('production_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sequence_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
//            $table->string('reference')->nullable();                            // Project reference, etc.
            
            $table->string('type', 32)->nullable(false)->default('onorder');
            // 'onorder'  => Fulfill Customer Orders
            // 'reorder'  => Restock Warehouse
            
            $table->date('due_date')->nullable();
            $table->string('name', 128)->nullable();
            $table->text('notes')->nullable();


            $table->tinyInteger('is_dirty')->default(0);
            // 0 -> NO; 1 -> Yes (Production Sheet needs calculations after a Customer / Production Oreder is added / removed).

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
        Schema::dropIfExists('production_sheets');
    }
}
