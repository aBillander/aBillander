<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkCentersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_centers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('alias', 32)->nullable(false);
			$table->string('name', 64)->nullable(false);

			$table->text('notes')->nullable();

			$table->tinyInteger('active')->default(1);

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
		Schema::dropIfExists('work_centers');
	}

}
