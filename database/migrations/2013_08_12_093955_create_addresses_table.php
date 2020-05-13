<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('addresses');
		
		Schema::create('addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('alias', 32)->nullable(false);
			$table->string('webshop_id', 32)->nullable();			// Address ID in the Web Shop

			$table->string('name_commercial', 64)->nullable();		// Address Name
			
			$table->string('address1', 128)->nullable(false);
			$table->string('address2', 128)->nullable();
			$table->string('postcode', 12)->nullable();
			$table->string('city', 64)->nullable();
			$table->string('state_name', 64)->nullable();				// State name, if no state id found (mainly for data import from WooCommerce, etc.)
			$table->string('country_name', 64)->nullable();				// Country name, if no country id found
			
			$table->string('firstname', 32)->nullable();			// Contact information
			$table->string('lastname', 32)->nullable();
			$table->string('email', 128)->nullable();

			$table->string('phone', 32)->nullable();
			$table->string('phone_mobile', 32)->nullable();
			$table->string('fax', 32)->nullable();
			
			$table->text('notes')->nullable();
			$table->tinyInteger('active')->default(1);

			$table->float('latitude')->nullable();					// Geolocation
			$table->float('longitude')->nullable();
			
//			$table->integer('addressable_id')->unsigned()->nullable(false);	
			$table->integer('addressable_id');
			$table->string('addressable_type');

			$table->integer('state_id')->unsigned()->nullable();
			$table->integer('country_id')->unsigned()->nullable();
			
			$table->integer('shipping_method_id')->unsigned()->nullable();
			
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
		Schema::dropIfExists('addresses');
	}

}
