<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCombinationWarehouseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('combination_warehouse');
        
		Schema::create('combination_warehouse', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('combination_id')->unsigned();
//			$table->foreign('combination_id')->references('id')->on('combinations')->onDelete('cascade');

			$table->integer('warehouse_id')->unsigned();
//			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

			$table->index(['warehouse_id', 'combination_id']);

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
		Schema::dropIfExists('combination_warehouse');
	}

}
