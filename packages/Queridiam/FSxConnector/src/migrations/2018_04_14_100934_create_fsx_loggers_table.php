<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFsxLoggersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('fsx_loggers');
		
		Schema::create('fsx_loggers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('type', 32)->nullable(false)->default('');		// $type='INFO', 'WARNING', 'ERROR'
			$table->string('message', 255)->nullable(false)->default('');
			$table->dateTime('date_added')->nullable(false)->useCurrent();
			$table->string('secs_added', 6)->nullable(false)->default('000000');
			
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
		Schema::dropIfExists('fsx_loggers');
	}

}
