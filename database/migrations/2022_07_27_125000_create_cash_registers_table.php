<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();

            $table->string('alias', 32)->nullable(false);
            $table->string('name', 128)->nullable(false);
            $table->string('reference', 32)->nullable();        // Serial number or the like (company asset code...)
            $table->string('barcode', 180)->nullable();
            $table->text('description')->nullable();

            $table->tinyInteger('active')->default(1);
            $table->string('status', 32)->nullable(false)->default('regular');   // 'regular', 'decommissioned', '?'
//          ^--Cash Register is open if it has an open CashRegisterJournal; otherwise is closed

//            $table->tinyInteger('display_prices_tax_inc')->default(0);

            $table->string('location', 64)->nullable();     // Within a Store, Branch, etc

            // Manufacturer

            // Image

            // Documents

//            $table->integer('cashier_user_id')->unsigned()->nullable();
//            ^-- A cash register should has a CashierUser assigned in order to open, manage and close it. Otherwise cash register cannot be operated after any CashierUser login. Relation is stablished on Cashier User, since a Cashier User has one Cash Register after login (for now...)

            $table->integer('currency_id')->unsigned()->nullable(false);
            $table->integer('selling_location_id')->unsigned()->nullable(); // Store, outlet, etc., with address and maybe local settings

//            $table->text('notes')->nullable();

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
        Schema::dropIfExists('cash_registers');
    }
};
