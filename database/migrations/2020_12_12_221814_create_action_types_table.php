<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('action_types');

		// Phone call, online meeting, face-to-face meeting, etc.
		
		Schema::create('action_types', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('alias', 16)->nullable(false);
			$table->string('name', 64)->nullable(false);
            $table->text('description')->nullable();

			$table->tinyInteger('active')->default(1);

			// bonus
			$table->integer('position')->unsigned()->default(0);
			
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
		Schema::dropIfExists('action_types');
	}

}
