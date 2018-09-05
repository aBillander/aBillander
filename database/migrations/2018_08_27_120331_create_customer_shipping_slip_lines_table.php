<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerShippingSlipLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_shipping_slip_lines');

        Schema::create('customer_shipping_slip_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 
            $table->string('line_type', 32)->nullable(false);       // product, service, ecotaxes, shipping, discount, comment
/*
            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('woo_product_id')->unsigned()->nullable();
            $table->integer('woo_variation_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);
            $table->decimal('quantity', 20, 6);
*/


            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);
            
            $table->decimal('quantity', 20, 6);
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->tinyInteger('prices_entered_with_tax')->default(0);
//            $table->tinyInteger('tax_computation_method');

            $table->decimal('cost_price', 20, 6)->default(0.0);
            $table->decimal('unit_price', 20, 6)->default(0.0);                 // From Product data (initial price)
            $table->decimal('unit_customer_price', 20, 6)->default(0.0);        // Calculated custom for customer (initial price for customer)
            $table->decimal('unit_customer_final_price', 20, 6)->default(0.0);  // Customer Price for this line
            $table->decimal('unit_customer_final_price_tax_inc', 20, 6)->default(0.0); 
                                                                                
            $table->decimal('unit_final_price', 20, 6)->default(0.0);           // Price after discounts = unit_customer_final_price - discount
            $table->decimal('unit_final_price_tax_inc', 20, 6)->default(0.0);

            $table->tinyInteger('sales_equalization')->default(0);              // Charge Sales equalization tax? (only Spain)

            $table->decimal('discount_percent', 8, 3)->default(0.0);            // Not the same as discount amount!! Maybe applies either one or another!
            $table->decimal('discount_amount_tax_incl', 20, 6)->default(0.0);   // Same tax as Product
            $table->decimal('discount_amount_tax_excl', 20, 6)->default(0.0);

            $table->decimal('total_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_tax_excl', 20, 6)->default(0.0);             // quantity * unit_final_price

            // margin: margin on purchase/cost price
            // mark rate or markup: margin on selling price

            $table->decimal('tax_percent', 8, 3)->default(0.0);                 // Tax percent
            $table->decimal('commission_percent', 8, 3)->default(0.0);          // Commission percent

            $table->text('notes')->nullable();
            
            $table->tinyInteger('locked')->default(0);                          // 0 -> NO; 1 -> Yes (line cannot be modified if retrieved from external system, i.e., webshop).

            $table->integer('customer_shipping_slip_id')->unsigned()->nullable(false);
            $table->integer('tax_id')->unsigned()->nullable(false);
            $table->integer('sales_rep_id')->unsigned()->nullable();             // Sales representative

//            $table->string('import_key', 16)->nullable(false);

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
        Schema::dropIfExists('customer_shipping_slip_lines');
    }
}
