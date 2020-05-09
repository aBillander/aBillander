<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_routes', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('alias', 32)->nullable(false);           // For dropdown selectors
            $table->string('name', 64)->nullable(false);

            $table->string('driver_name', 128)->nullable();

            $table->tinyInteger('active')->default(1);
            
            $table->text('notes')->nullable();                  // Private notes ( notes to self ;) )

            $table->integer('carrier_id')->unsigned()->nullable(false);
            
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
        Schema::dropIfExists('delivery_routes');
    }
}
