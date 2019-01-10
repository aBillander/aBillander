<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpContentsTable extends Migration {

	// php artisan make:controller HelpContentsController --resource --model=HelpContent

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('help_contents');

		Schema::create('help_contents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug', 128)->unique();

			$table->string('name', 128)->nullable(false);
			$table->text('description')->nullable();
//			$table->text('footer')->nullable();
			
			$table->integer('language_id')->unsigned()->default(0);
			
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
		Schema::dropIfExists('help_contents');
	}

}
