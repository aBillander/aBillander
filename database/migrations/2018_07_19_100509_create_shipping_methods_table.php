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

			$table->tinyInteger('active')->default(1);

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
