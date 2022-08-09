<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellingLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('selling_locations');

        Schema::create('selling_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned()->default(0);  // For multi-Company setup

            $table->string('alias', 32)->nullable(false);
            $table->string('name', 128)->nullable(false);
//            $table->string('timezone', 32)->nullable();

            $table->text('cash_denominations')->nullable();    // Comma/semicolon separated values. Example: 100,200,500,2000 or 0,01;0,02;0,05;... or 0.01;0.02;0.05;...

            $table->text('allowed_payment_methods')->nullable();    // JSON

            $table->tinyInteger('active')->default(1);

            $table->integer('address_id')->unsigned()->nullable(false);
            $table->integer('currency_id')->unsigned()->nullable(false);
//            $table->text('cash_denomination_set_id')->nullable(); <= Make sense to link an image to any denomination (Too complex? I think so...)
            $table->integer('counter_sale_sequence_id')->unsigned()->nullable(false);
            $table->integer('counter_sale_template_id')->unsigned()->nullable(false);
            $table->integer('invoice_sequence_id')->unsigned()->nullable(false);
            $table->integer('invoice_template_id')->unsigned()->nullable(false);
            $table->integer('price_list_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selling_locations');
    }
}
