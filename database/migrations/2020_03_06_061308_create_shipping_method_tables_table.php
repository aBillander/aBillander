<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingMethodTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('shipping_method_tables');
		
		Schema::create('shipping_method_tables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 64)->nullable();
			$table->string('type', 32)->nullable(false);			// 'weight', 'price', etc.

			$table->integer('tax_id')->unsigned()->nulable();		// Future use

			$table->integer('shipping_method_id')->unsigned()->nulable();
			
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
		Schema::dropIfExists('shipping_method_tables');
	}

}
