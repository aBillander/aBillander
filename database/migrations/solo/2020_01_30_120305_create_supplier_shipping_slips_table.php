<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierShippingSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('supplier_shipping_slips');
        
        Schema::create('supplier_shipping_slips', function (Blueprint $table) {

            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_documents_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_documents_table.php';
            }

            $table->string('shipment_status', 32)->nullable(false)->default('pending');

            $table->date('invoiced_at')->nullable();


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
        Schema::dropIfExists('supplier_shipping_slips');
    }
}
