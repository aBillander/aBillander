<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParentChildTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('parent_child');

		Schema::create('parent_child', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('parentable_id');
			$table->string('parentable_type');
			
			$table->integer('childable_id');
			$table->string('childable_type');
			
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
		Schema::dropIfExists('parent_child');
	}

}
