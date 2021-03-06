<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('supplier_orders');

        Schema::create('supplier_orders', function (Blueprint $table) {

            $entity = 'supplier';

            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_documents_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_documents_table.php';
            }

            // Suggested Orders may not have a Supplier!
            // $table->integer('invoicing_address_id')->nullable()->change();

            $table->string('fulfillment_status', 32)->nullable(false)->default('pending');  // pending, partial, done
            
            $table->dateTime('shipping_slip_at')->nullable();
//             $table->dateTime('invoiced_at')->nullable();
            $table->dateTime('aggregated_at')->nullable();
            $table->dateTime('backordered_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_orders');
    }
}
