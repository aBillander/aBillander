<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('caption', 128)->nullable();

			$table->string('extension', 10)->default('');
			$table->integer('position')->unsigned()->default(0);
			$table->boolean('is_featured')->default(false);

			$table->tinyInteger('active')->default(1);

			$table->integer('imageable_id')->unsigned()->nullable(false);
			$table->string('imageable_type');

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
		Schema::drop('images');
	}

}
