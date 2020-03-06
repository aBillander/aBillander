<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingMethodTableLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('shipping_method_table_lines');
		
		Schema::create('shipping_method_table_lines', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('country_id')->unsigned()->nulable();
			$table->integer('state_id')->unsigned()->nulable();
			$table->string('postcode', 12)->nullable();

			$table->decimal('amount', 20, 6)->default(0.0);

			$table->integer('tax_id')->unsigned()->nulable();		// Future use
			
			$table->integer('shipping_method_table_id')->unsigned()->default(0);

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
		Schema::dropIfExists('shipping_method_table_lines');
	}

}
