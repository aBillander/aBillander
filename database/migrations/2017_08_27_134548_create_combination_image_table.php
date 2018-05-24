<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinationImageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combination_image', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('combination_id')->unsigned()->index();
//			$table->foreign('combination_id')->references('id')->on('combinations')->onDelete('cascade');

			$table->integer('image_id')->unsigned()->index();
//			$table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');

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
		Schema::drop('combination_image');
	}

}
