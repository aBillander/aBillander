<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSepaDirectDebitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sepa_direct_debits');
        
        Schema::create('sepa_direct_debits', function (Blueprint $table) {

            $table->string('bank_account_code', 6);







            
            $table->dateTime('shipping_slip_at')->nullable();
            $table->dateTime('invoiced_at')->nullable();
            $table->dateTime('aggregated_at')->nullable();
            $table->dateTime('backordered_at')->nullable();

            $table->integer('production_sheet_id')->unsigned()->nullable();


            $table->tinyInteger('prices_entered_with_tax')->default(0);         // See: PRICES_ENTERED_WITH_TAX; Maybe not needed here (stored for every invoice)
            $table->tinyInteger('round_prices_with_tax')->default(0);           // See: ROUND_PRICES_WITH_TAX

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sepa_direct_debits');
    }
}
