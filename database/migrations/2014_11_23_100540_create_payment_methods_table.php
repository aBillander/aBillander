<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('payment_methods');
		
		Schema::create('payment_methods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 128)->nullable(false);

//			$table->enum('deadlines_by', array('days', 'months'))->default('days');
			
			$table->text('deadlines')->nullable(false);				// serialized array: [[0] => [days/months slot, percentage], [1] => [] ....]

			$table->tinyInteger('payment_is_cash')->default(0);
			$table->tinyInteger('auto_direct_debit')->default(0); 	// Include invoices (with this method) in automatic payment remittances
			$table->tinyInteger('active')->default(1);

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
		Schema::dropIfExists('payment_methods');
	}

}
