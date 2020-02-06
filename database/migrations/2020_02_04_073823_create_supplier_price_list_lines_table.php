<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupplierPriceListLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_price_list_lines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('supplier_id')->unsigned()->nullable(false);

			$table->integer('product_id')->unsigned()->nullable(false);
			$table->string('supplier_reference', 32)->nullable();

			$table->integer('currency_id')->unsigned()->nullable();
			$table->decimal('price', 20, 6)->default(0.0);
			
			$table->decimal('discount_percent', 8, 3)->default(0.0);
			$table->decimal('discount_amount', 20, 6)->default(0.0);

			$table->decimal('from_quantity', 20, 6)->default(1.0);
			
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
		Schema::drop('supplier_price_list_lines');
	}

}
