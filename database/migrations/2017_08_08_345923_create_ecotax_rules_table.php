<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcotaxRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('ecotax_rules');
		
		Schema::create('ecotax_rules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('country_id')->unsigned()->default(0);
			$table->integer('state_id')->unsigned()->default(0);
			$table->string('rule_type', 32)->nullable(false);			// 'sales', sales_equalization', etc.

			$table->string('name', 64)->nullable(false);
			$table->decimal('percent', 8, 3)->default(0.0);
			$table->decimal('amount', 20, 6)->default(0.0);				// Ecotax may be fixed amount

			$table->integer('position')->unsigned()->default(0);		// Ecotaxes apply according to this order number (lowest applies first)
			
			$table->integer('ecotax_id')->unsigned()->default(0);

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
		Schema::dropIfExists('ecotax_rules');
	}

}
