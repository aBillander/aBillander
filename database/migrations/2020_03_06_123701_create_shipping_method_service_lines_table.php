<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingMethodServiceLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('shipping_method_service_lines');
		
		Schema::create('shipping_method_service_lines', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('country_id')->unsigned()->nulable();
			$table->integer('state_id')->unsigned()->nulable();
			$table->string('postcode', 12)->nullable();

			$table->decimal('from_amount', 20, 6)->default(0.0);	// Maybe price (order total), weight, number of items, volume, etc. 
			$table->decimal('price', 20, 6)->default(0.0);

			$table->integer('tax_id')->unsigned()->nulable();		// Future use
			
			// Tabulaable (ShippingMethod or ShippingMethodService)
			$table->integer('tabulable_id');
			$table->string('tabulable_type');

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
		Schema::dropIfExists('shipping_method_service_lines');
	}

}
