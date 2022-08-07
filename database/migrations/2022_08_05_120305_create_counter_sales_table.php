<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('counter_sales');
        
        Schema::create('counter_sales', function (Blueprint $table) {

            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_documents_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_documents_table.php';
            }
            
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
        Schema::dropIfExists('counter_sales');
    }
}
