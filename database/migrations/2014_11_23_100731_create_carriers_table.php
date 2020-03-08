<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarriersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carriers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('alias', 32)->nullable(false);
			$table->string('name', 64)->nullable(false);

			$table->string('tracking_url', 128)->nullable();
			$table->string('transit_time', 16)->nullable();		// 2d: 2 days; 12h: 12 hours. Case insensitive.
			
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
		Schema::drop('carriers');
	}

}
