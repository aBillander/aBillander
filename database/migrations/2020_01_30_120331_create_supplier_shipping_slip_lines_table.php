<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierShippingSlipLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('supplier_shipping_slip_lines');

        Schema::create('supplier_shipping_slip_lines', function (Blueprint $table) {
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php';
            }

            // Parent Document
            $table->integer('supplier_shipping_slip_id')->unsigned()->nullable(false);
        });

        Schema::table('supplier_shipping_slip_lines', function ($table) {
            $table->renameColumn('unit_customer_price',               'unit_supplier_price');
            $table->renameColumn('unit_customer_final_price',         'unit_supplier_final_price');
            $table->renameColumn('unit_customer_final_price_tax_inc', 'unit_supplier_final_price_tax_inc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_shipping_slip_lines');
    }
}
