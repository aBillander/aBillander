<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverySheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_sheets', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sequence_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
//            $table->string('reference')->nullable();                            // Project reference, etc.
            
//            $table->string('alias', 32)->nullable(false);           // For dropdown selectors
            $table->string('name', 64)->nullable(false);
            $table->string('driver_name', 128)->nullable();
            $table->date('due_date')->nullable(false);

            $table->tinyInteger('active')->default(1);

            $table->text('route_notes')->nullable();            // Taken from DeliveryRoute
            $table->text('driver_notes')->nullable();           // Added by Driver
            $table->text('notes')->nullable();                  // Notes & Directions for Driver

            $table->integer('delivery_route_id')->unsigned()->nullable(false);
            
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
        Schema::dropIfExists('delivery_sheets');
    }
}
