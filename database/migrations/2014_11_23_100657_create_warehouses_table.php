<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('warehouses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('alias', 32)->nullable(false);
			$table->string('name', 64)->nullable();

			$table->text('notes')->nullable();

			$table->tinyInteger('active')->default(1);

			// management_type	enum('WA', 'FIFO', 'LIFO')					// Management type. A method of accounting valuation, based on your country's regulations. See the "Stock management rules" part of this chapter for more information.

			// $table->integer('currency_id')->unsigned()->nullable(false);	// Stock valuation currency. A valuation currency for this warehouse's stock (among the registered currencies).
																			// It is not possible to change a warehouse's valuation method and currency once it has been set. If you need to change that information, you will have to recreate the warehouse, and delete the wrong one. You can only delete a warehouse if it does not contain any product anymore.
			// id_employee	int(11)											// Manager. A person in charge of the warehouse, chosen among your shop's registered employees. If the employee's account is not yet created, you must create it first.

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
		Schema::drop('warehouses');
	}

}
