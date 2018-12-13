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

            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_customer_orders_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_customer_orders_table.php';
            }

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
