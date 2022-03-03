<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinationOptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combination_option', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('combination_id')->unsigned()->index();
//			$table->foreign('combination_id')->references('id')->on('combinations')->onDelete('cascade');

			$table->integer('option_id')->unsigned()->index();
//			$table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
			
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
		Schema::drop('combination_option');
	}

}
