<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('languages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 32)->nullable(false);
			$table->string('iso_code', 2)->nullable(false);						// Two-letter ISO code (e.g. FR, EN, DE)
			$table->string('language_code', 5)->nullable(false);				// IETF language tag (e.g. en-US, pt-BR)
			
			$table->string('date_format_lite', 32)->nullable(false);			// Short date format (e.g., Y-m-d)
			$table->string('date_format_full', 32)->nullable(false);			// Full date format (e.g., Y-m-d H:i:s)

			$table->string('date_format_lite_view', 32)->nullable(false);		// Short date format for view datepicker
			$table->string('date_format_full_view', 32)->nullable(false);		// Full date format for view datepicker

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
		Schema::dropIfExists('languages');
	}

}
