<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryRouteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_route_lines', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 


            $table->integer('delivery_route_id')->unsigned()->nullable(false);
            $table->integer('customer_id')->unsigned()->nullable(); // For convenience
            $table->integer('address_id')->unsigned()->nullable();

            $table->tinyInteger('active')->default(1);

            $table->text('notes')->nullable();            // Taken from DeliveryRoute

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
        Schema::dropIfExists('delivery_route_lines');
    }
}
