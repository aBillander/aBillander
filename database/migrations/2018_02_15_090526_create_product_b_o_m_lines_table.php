<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBOMLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('product_b_o_m_lines');
        
        Schema::create('product_b_o_m_lines', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines 
            $table->string('line_type', 32)->nullable(false)
                                        ->default('product');       // product, phantom

            $table->integer('product_id')->unsigned()->nullable(false);
            $table->decimal('quantity', 20, 6)->default(1.0);
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->decimal('scrap', 8, 3)->default(0.0);         // Percent. When the components are ready to be consumed in a released production order, this percentage will be added to the expected quantity in the Consumption Quantity field in a production journal.

            $table->text('notes')->nullable();

            $table->integer('product_bom_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('product_b_o_m_lines');
    }
}
