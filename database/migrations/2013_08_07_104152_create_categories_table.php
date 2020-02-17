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
			$table->integer('position')->unsigned()->default(0);;
			
			$table->tinyInteger('publish_to_web')->default(0);
			$table->string('webshop_id', 16)->nullable();
			
            $table->string('reference_external', 32)->nullable();         // To allow an external system or interface to save its own internal reference to have a link between records into aBillander and records into an external system
			
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
