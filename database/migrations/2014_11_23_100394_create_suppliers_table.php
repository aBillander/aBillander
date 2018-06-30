<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('suppliers');
			
		Schema::create('suppliers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('alias', 32)->nullable(false);

			$table->string('name_fiscal', 128)->nullable(false);						// Company
			$table->string('name_commercial', 64)->nullable();

			$table->string('website', 128)->nullable();
			
			$table->string('identification', 64)->nullable();					// VAT ID or the like (only companies & pro's?)
			// EU VAT Id, Tax number, etc.

		/* */
			$table->text('notes')->nullable();
		/* */

			$table->tinyInteger('active')->default(1);

			$table->integer('currency_id')->unsigned()->nullable(false);
			$table->integer('language_id')->unsigned()->nullable(false);
			$table->integer('payment_method_id')->unsigned()->nullable();

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
		Schema::dropIfExists('suppliers');
	}

}
