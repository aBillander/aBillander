<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('supplier_invoice_lines');
        
        Schema::create('supplier_invoice_lines', function (Blueprint $table) {

            $entity = 'supplier';
            
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php';
            }

            // Parent Document
            $table->integer('supplier_invoice_id')->unsigned()->nullable(false);

            // This line comes from this Supplier Shipping Slip (when applicable!)
            $table->integer('supplier_shipping_slip_id')->unsigned()->nullable();

            // This line comes from this Supplier Shipping Slip Line (when applicable!)
            $table->integer('supplier_shipping_slip_line_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_invoice_lines');
    }
}
