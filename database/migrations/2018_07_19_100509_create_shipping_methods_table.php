<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShippingMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('shipping_methods');
		
		Schema::create('shipping_methods', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('company_id')->unsigned()->default(0);         // For multi-Company setup
            $table->integer('user_id')->unsigned()->default(0);            // Maybe creator user, modifier user

            $table->string('alias', 16)->nullable(false);
			$table->string('name', 64)->nullable(false);
			$table->string('webshop_id', 16)->nullable();					// Customer's Web Shop id

			$table->string('class_name', 64)->nullable();

			$table->tinyInteger('active')->default(1);

			/* Cost calculation stuff */

			$table->string('type', 32)->nullable(false)->default('basic');
			// 'basic', 'multiservice'
			// ShippingMethod CRUD is only available if 'type' == 'basic'
			// If 'type' == 'multiservice', these properties must be defined for each service

			$table->string('transit_time', 16)->nullable();		// 2d: 2 days; 12h: 12 hours. Case insensitive.

			$table->string('billing_type', 32)->nullable(false)->default('price');
			// 'price', 'weight', 'items' (number of items), etc.

			// To do:
			// Calculate handling fee: Fixed / Percent
			// Handling fee
			// Ship to applicable countries: All allowed countries / Only specific countries
			// Ship to specific counties <selector>

			// Out of range behaviour: Apply the cost of the highest defined range / Disable method
			// $table->string('billing_out_of_range', 32)->nullable(false)->default('highest');
			// 'highest', 'disable'

			$table->decimal('free_shipping_from', 20, 6)->default(0.0);

			$table->integer('tax_id')->unsigned();

			// bonus
			$table->integer('position')->unsigned()->default(0);
			// If 'type' == 'multiservice', each service has a 'position' too

			/* Cost calculation stuff ENDS */

			$table->integer('carrier_id')->unsigned()->nullable();
			
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shipping_methods');
	}

}
