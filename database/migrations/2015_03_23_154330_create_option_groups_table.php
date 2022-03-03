<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOptionGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('option_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 128)->nullable(false);
			$table->string('public_name', 64)->nullable();
			$table->integer('position')->unsigned()->default(0);
			
			$table->string('webshop_id', 16)->nullable();

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
		Schema::drop('option_groups');
	}

}
