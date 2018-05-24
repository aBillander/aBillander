<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyConversionRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_conversion_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');
            $table->integer('currency_id')->unsigned()->nullable(false);
            $table->decimal('conversion_rate', 20, 6)->default(1.0);
            
            $table->integer('user_id')->unsigned()->nullable(false)->default(0);

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
        Schema::dropIfExists('currency_conversion_rates');
    }
}
