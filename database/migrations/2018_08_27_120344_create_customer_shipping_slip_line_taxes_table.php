<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerShippingSlipLineTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_shipping_slip_line_taxes');

        Schema::create('customer_shipping_slip_line_taxes', function (Blueprint $table) {
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_document_line_taxes_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_document_line_taxes_table.php';
            }

            // Parent Document Line
            $table->integer('customer_shipping_slip_line_id')->unsigned()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_shipping_slip_line_taxes');
    }
}
