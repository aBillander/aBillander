<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceRulesTable extends Migration {

	// php artisan make:controller HelpContentsController --resource --model=HelpContent

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('price_rules');

		Schema::create('price_rules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 128)->nullable();

			$table->integer('category_id')->unsigned()->nullable();

			$table->integer('product_id')->unsigned()->nullable();
			$table->integer('combination_id')->unsigned()->nullable();
			
			$table->integer('customer_id')->unsigned()->nullable();
			$table->integer('customer_group_id')->unsigned()->nullable();

			$table->integer('currency_id')->unsigned()->nullable();

			$table->string('rule_type', 32)->nullable(false)->default('price');
			// 'price'
			// 'discount'
			$table->decimal('price', 20, 6)->default(0.0);
			
			$table->string('discount_type', 32)->nullable(false)->default('percentage');
			// 'amount'
			// 'percentage'
			$table->decimal('discount_percent', 8, 3)->default(0.0);
			$table->decimal('discount_amount', 20, 6)->default(0.0);
			$table->tinyInteger('discount_amount_is_tax_incl')->default(0);

			$table->decimal('from_quantity', 20, 6);
			$table->decimal('extra_quantity', 20, 6)->default(0.0);

			$table->dateTime('date_from')->nullable();
			$table->dateTime('date_to')->nullable();

            $table->integer('measure_unit_id')->unsigned()->nullable();
            $table->decimal('conversion_rate', 20, 6)->default(1.0);		// Conversion to stock (main) unit. Conversion rates are calculated from one unit of your main measura unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, type "6" (since a pack of six bottles will contain six bottles)



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
		Schema::dropIfExists('price_rules');
	}

}
