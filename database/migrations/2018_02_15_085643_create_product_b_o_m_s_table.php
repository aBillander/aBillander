<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBOMsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('product_b_o_m_s');

        Schema::create('product_b_o_m_s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias', 32)->nullable(false);
            $table->string('name', 128)->nullable(false);                       // BOM description

            $table->decimal('quantity', 20, 6)->default(1.0);                   // Ingredients are for this quantity of finished product
            $table->integer('measure_unit_id')->unsigned()->nullable(false);

            $table->string('status', 32)->nullable(false)
                                        ->default('certified');

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('product_b_o_m_s');
    }
}
