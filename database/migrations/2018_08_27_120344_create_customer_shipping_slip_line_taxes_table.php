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
            $table->increments('id');
            $table->string('name', 128)->nullable(false);

            $table->string('tax_rule_type', 32)->nullable(false);

            $table->decimal('taxable_base', 20, 6)->default(0.0);                       // Base for tax calculations
            $table->decimal('percent', 8, 3)->default(0.0);                             // Tax percent
            $table->decimal('amount', 20, 6)->default(0.0);                             // Tax may be fixed amount
            $table->decimal('total_line_tax', 20, 6)->default(0.0);

            $table->integer('position')->unsigned()->default(0);

            $table->integer('customer_shipping_slip_line_id')->unsigned()->nullable(false);
            $table->integer('tax_id')->unsigned()->nullable(false);
            $table->integer('tax_rule_id')->unsigned()->nullable(false);                    // What if it changes/disappears??

            $table->timestamps();
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
