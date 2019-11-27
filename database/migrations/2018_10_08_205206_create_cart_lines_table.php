<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cart_lines');

        Schema::create('cart_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 
            $table->string('line_type', 32)->nullable(false)->default('product');       // product, service, shipping, discount, comment

            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);
            
            $table->decimal('quantity', 20, 6);
            $table->decimal('extra_quantity', 20, 6)->default(0.0);
            $table->string('extra_quantity_label', 128)->nullable();
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->decimal('unit_customer_price', 20, 6)->default(0.0);        // Calculated for customer after Price List(initial price for customer)
            $table->decimal('unit_customer_final_price', 20, 6)->default(0.0);  // After apllying Price Rules

            $table->tinyInteger('sales_equalization')->default(0);              // Charge Sales equalization tax? (only Spain)

            $table->decimal('total_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_tax_excl', 20, 6)->default(0.0);

            $table->decimal('tax_percent', 8, 3)->default(0.0);                 // Tax percent
            $table->decimal('tax_se_percent', 8, 3)->default(0.0);              // Sales equalization percent

            $table->integer('cart_id')->unsigned()->nullable(false);
            $table->integer('tax_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('cart_lines');
    }

    // php artisan migrate --path=/database/migrations/subfolder/2018_10_08_205206_create_cart_lines_table.php --pretend
}
