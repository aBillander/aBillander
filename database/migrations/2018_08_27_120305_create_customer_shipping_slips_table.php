<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerShippingSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_shipping_slips');
        
        Schema::create('customer_shipping_slips', function (Blueprint $table) {

            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_documents_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_documents_table.php';
            }

            $table->date('printed_at')->nullable();                             // Printed at
            $table->date('edocument_sent_at')->nullable();                      // Electronic document sent at
            $table->date('customer_viewed_at')->nullable();                     // Customer retrieved invoice from online customer center


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
        Schema::dropIfExists('customer_shipping_slips');
    }
}
