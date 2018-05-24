<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('templates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 128);
			$table->string('model_name', 64)->nullable(false);    	// This template applies to this Model

			$table->string('folder');
			$table->string('file_name', 64);
			$table->string('paper', 32)->default('A4');
			$table->enum('orientation', array('portrait', 'landscape'))->default('portrait');

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
		Schema::drop('templates');
	}

}
