<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockMovementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('stock_movements');

		Schema::create('stock_movements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamp('date');

			$table->integer('stockmovementable_id');
			$table->string('stockmovementable_type');

			$table->string('document_reference', 64)->nullable();		// document_prefix + document_id of model_name (or supplier reference, etc.)

			$table->decimal('quantity_before_movement', 20, 6);
			$table->decimal('quantity', 20, 6);
			$table->integer('measure_unit_id')->unsigned()->nullable(false);
			$table->decimal('quantity_after_movement', 20, 6);

			// For tracking
			$table->decimal('cost_price_before_movement', 20, 6)->nullable();
			$table->decimal('cost_price_after_movement', 20, 6)->nullable();
			
			// Unit price
			$table->decimal('price', 20, 6)->nullable();				// Company Currency
			$table->decimal('price_currency', 20, 6)->nullable();		// Movement Currency (sales or purchase order currency, etc.)
			$table->integer('currency_id')->unsigned()->nullable(false);
			$table->decimal('conversion_rate', 20, 6);

			$table->text('notes')->nullable();			// Reason for this movement and other comments
			
			$table->integer('product_id')->unsigned()->nullable(false);
			$table->integer('combination_id')->unsigned()->nullable();
            $table->string('reference', 32)->nullable();
            $table->string('name', 128)->nullable(false);

			$table->integer('warehouse_id')->unsigned()->nullable(false);
			$table->integer('warehouse_counterpart_id')->unsigned()->nullable();			// For Stock Transfers between Warehouses

			$table->smallInteger('movement_type_id')->unsigned()->nullable(false);			// Movement types: Input (Purchase order...), Output (Sales order,...), Stock Adjustment
			$table->integer('user_id')->unsigned()->nullable(false)->default(0);


			$table->string('inventorycode', 128)->nullable();			// code used to group different movement line into one operation (may be an inventory, a mass picking) 
			
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
        Schema::dropIfExists('stock_movements');
	}

}
