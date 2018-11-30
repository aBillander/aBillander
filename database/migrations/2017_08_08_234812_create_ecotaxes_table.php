<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcotaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('ecotaxes');
		
		Schema::create('ecotaxes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 64)->nullable(false);

			$table->tinyInteger('active')->default(1);
			
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
		Schema::dropIfExists('ecotaxes');
	}

}

/*

1 	Tubos Led y Fluorescentes 	0.00% 	0,12 	
2 	Lamparas 					0.00% 	0,08 	
3 	Luminarias > 5kg 			0.00% 	0,28 	
4 	Luminarias 1-5kg 			0.00% 	0,13 	
5 	Luminarias < 1kg 			0.00% 	0,06

*/
