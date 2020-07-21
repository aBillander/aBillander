<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('lot_items');
		
		Schema::create('lot_items', function(Blueprint $table)
		{
			$table->increments('id');

            $table->integer('lot_id')->unsigned()->nullable(false);
            
            $table->decimal('quantity', 20, 6)->default(1.0);

            $table->tinyInteger('is_reservation')->default(0);		// 1: pre-asigned lot to lotable (usually a Customer Shipping Slip). Useful before issue a Picking List

			$table->integer('lotable_id');
			$table->string('lotable_type');
			
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
		Schema::dropIfExists('lot_items');
	}

}
