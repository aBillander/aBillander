<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductWarehouseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('product_warehouse');
        
		Schema::create('product_warehouse', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('product_id')->unsigned();
//			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

			$table->integer('warehouse_id')->unsigned();
//			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

			$table->index(['warehouse_id', 'product_id']);

			$table->decimal('quantity', 20, 6)->default(0.0);
			
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
		Schema::dropIfExists('product_warehouse');
	}

}
