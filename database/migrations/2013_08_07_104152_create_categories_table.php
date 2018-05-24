<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 128)->nullable(false);
			$table->integer('position')->unsigned();
			
			$table->tinyInteger('publish_to_web')->default(0);
			$table->string('webshop_id', 16)->nullable();
			
			$table->tinyInteger('is_root')->default(0);
			$table->tinyInteger('active')->default(1);
			
			$table->integer('parent_id')->unsigned()->default(0);
			
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
		Schema::drop('categories');
	}

}
