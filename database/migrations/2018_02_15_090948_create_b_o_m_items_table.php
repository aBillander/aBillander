<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBOMItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('b_o_m_items');

        Schema::create('b_o_m_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_id')->unsigned()->nullable(false);
            $table->integer('product_bom_id')->unsigned()->nullable(false);
            
            $table->decimal('quantity', 20, 6)->default(1.0);

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
        Schema::dropIfExists('b_o_m_items');
    }
}
