<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcotaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('ecotaxes');
		
		Schema::create('ecotaxes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('country_id')->unsigned()->default(0);
			$table->integer('state_id')->unsigned()->default(0);
			$table->string('rule_type', 32)->nullable(false)->default('ecotax');			// 'sales', sales_equalization', etc.

			$table->string('name', 64)->nullable(false);
			$table->decimal('percent', 8, 3)->default(0.0);				// Apply to price
			$table->decimal('amount', 20, 6)->default(0.0);				// Per unit

			$table->integer('position')->unsigned()->default(0);		// Taxes apply according to this order number (lowest applies first)
			
//			$table->integer('tax_id')->unsigned()->default(0);

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
		Schema::dropIfExists('ecotaxes');
	}

}
