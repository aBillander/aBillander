<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('supplier_order_lines');

        Schema::create('supplier_order_lines', function (Blueprint $table) {

            $entity = 'supplier';
            
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php';
            }

            // Parent Document
            $table->integer('supplier_order_id')->unsigned()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_order_lines');
    }
}
