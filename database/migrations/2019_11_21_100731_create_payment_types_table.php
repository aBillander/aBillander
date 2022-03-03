<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_types', function(Blueprint $table)
		{
			Schema::dropIfExists('payment_types');

			$table->increments('id');
			$table->string('alias', 32)->nullable(false);			// For dropdown selectors
			$table->string('name', 64)->nullable(false);
			
			$table->tinyInteger('active')->default(1);

			$table->string('accounting_code', 32)->nullable();		// Accounting balancing entry (link to Accounting General Ledger)

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
		Schema::dropIfExists('payment_types');
	}

}
