<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeasureUnitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('measure_units', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 32)->nullable(false)->default('Quantity');

			$table->decimal('type_conversion_rate', 20, 6)->default(1.0);	// Conversion rates are calculated from one unit of your default unit. For example, if the default unit is meter and your chosen unit is kilometer, type &quot;0.001&quot; (1meter = 0.001kilometer). Conversion makes sense among units of the same type. For future use.
			
			$table->string('sign', 8)->nullable(false);							// Will appear in Front Office (e.g. $, &amp;euro;, etc.)
			$table->string('name', 32)->nullable(false);						// Only letters and the minus character are allowed

			$table->tinyInteger('decimalPlaces')->default(2);					// Decimal places to show (Prices, taxes, etc.)
			
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
		Schema::dropIfExists('measure_units');
	}

}
