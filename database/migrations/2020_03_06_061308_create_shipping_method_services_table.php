<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingMethodServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('shipping_method_services');
		
		Schema::create('shipping_method_services', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 64)->nullable();
			$table->string('billing_type', 32)->nullable(false);			// 'weight', 'price', etc.
			

			$table->string('transit_time', 16)->nullable();		// 2d: 2 days; 12h: 12 hours. Case insensitive.

			$table->decimal('free_shipping_from', 20, 6)->default(0.0);

			$table->integer('tax_id')->unsigned()->nulable();		// Future use, maybe

			// bonus
			$table->integer('position')->unsigned()->default(0);	// Future use, maybe

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
		Schema::dropIfExists('shipping_method_services');
	}

}
