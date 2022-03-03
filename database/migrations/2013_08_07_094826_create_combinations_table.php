<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('combinations');

		Schema::create('combinations', function(Blueprint $table)
		{
			$table->increments('id');
//			$table->string('name', 128)->nullable(false);
			$table->string('reference', 32)->nullable();
			$table->string('ean13', 13)->nullable();
//			$table->text('description')->nullable();
//			$table->text('description_short')->nullable();
			$table->string('measure_unit', 32)->nullable();
			$table->tinyInteger('quantity_decimal_places')->unsigned()->default(0);

			$table->decimal('quantity_onhand', 20, 6)->default(0);			// In Stock; on hand
			$table->decimal('quantity_onorder', 20, 6)->default(0);			// On order; Quantity on Purchase Orders (pendiente de recibir)
			$table->decimal('quantity_allocated', 20, 6)->default(0);		// Allocated; Quantity on Sale Orders (reservado)
			$table->decimal('quantity_onorder_mfg', 20, 6)->default(0);		// On order (manufacturing); Quantity on Production Orders
			$table->decimal('quantity_allocated_mfg', 20, 6)->default(0);	// Allocated (manufacturing); Quantity allocated for Production Orders
																			// Available: instock + onorder - allocated

			$table->decimal('reorder_point', 20, 6)->default(0);			// Acts like safety stock or minimum stock
			$table->decimal('maximum_stock', 20, 6)->default(0);
			
			$table->decimal('price', 20, 6)->default(0.0);
			$table->decimal('last_purchase_price', 20, 6)->default(0.0);
			$table->decimal('cost_price', 20, 6)->default(0.0);
			$table->decimal('cost_average', 20, 6)->default(0.0);

			$table->string('supplier_reference', 32)->nullable();
			$table->integer('supply_lead_time')->unsigned()->default(0);

			$table->string('location', 64)->nullable();
			$table->decimal('width', 20, 6)->nullable()->default(0.0);   // cm
			$table->decimal('height', 20, 6)->nullable()->default(0.0);
			$table->decimal('depth', 20, 6)->nullable()->default(0.0);
			$table->decimal('weight', 20, 6)->nullable()->default(0.0);  // kg

			$table->integer('warranty_period')->unsigned()->default(0);
			
			$table->text('notes')->nullable();
//			$table->tinyInteger('stock_control')->default(1);
			$table->tinyInteger('publish_to_web')->default(0);
			$table->tinyInteger('blocked')->default(0);					// Sales not allowed
			$table->tinyInteger('active')->default(1);

			$table->tinyInteger('is_default')->default(0);				// Is default combination? Mainly for webshop use
			
			$table->integer('product_id')->unsigned()->nullable(false);

//			$table->integer('main_supplier_id')->unsigned()->nullable();
			
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
		Schema::dropIfExists('combinations');
	}

}
