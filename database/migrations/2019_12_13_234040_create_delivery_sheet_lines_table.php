<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverySheetLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_sheet_lines', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 


            $table->integer('delivery_sheet_id')->unsigned()->nullable(false);
            $table->integer('customer_id')->unsigned()->nullable();                 // For convenience
            $table->integer('address_id')->unsigned()->nullable();

            $table->text('route_notes')->nullable();
            $table->text('notes')->nullable();                  // Private notes ( notes to self ;) )

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
        Schema::dropIfExists('delivery_sheet_lines');
    }
}
