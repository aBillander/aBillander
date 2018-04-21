<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFsxConfigurationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('fsx_configurations');
		
		Schema::create('fsx_configurations', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 254)->index()->unique();
			$table->text('description')->nullable();
			$table->text('value')->nullable();
			
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
		Schema::dropIfExists('fsx_configurations');
	}

}
