<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePriceListLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_list_lines', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->unsigned()->nullable(false);
			$table->decimal('price', 20, 6)->default(0.0);

			$table->integer('price_list_id')->unsigned()->nullable(false);
			
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
		Schema::drop('price_list_lines');
	}

}
