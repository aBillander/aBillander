<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currencies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 32)->nullable(false);						// Only letters and the minus character are allowed
			$table->string('iso_code', 3)->nullable(false);						// ISO code (e.g. USD for Dollars, EUR for Euros, etc.)
			$table->string('iso_code_num', 3)->nullable(false);					// Numeric ISO code (e.g. 840 for Dollars, 978 for Euros, etc.)
			$table->string('sign', 8)->nullable(false);							// Will appear in Front Office (e.g. $, &amp;euro;, etc.)

			$table->tinyInteger('signPlacement')->default(1);					// 0: before (Such as with Dollars); 1: after (Such as with Euros)
			$table->string('thousandsSeparator', 1)->default('.');				// 
			$table->string('decimalSeparator', 1)->default(',');				// 
			$table->tinyInteger('decimalPlaces')->default(2);					// Decimal places to show (Prices, taxes, etc.)


/*
			$table->tinyInteger('format')->default(0);							// 0: nothing selected! Applies to all prices (e.g. $1,240.15)

<select name="format" class=" fixed-width-xl" id="format">
	<option value="1">X0,000.00 (Such as with Dollars)</option>
	<option value="2" selected="selected">0 000,00X (Such as with Euros)</option>
	<option value="3">X0.000,00</option>
	<option value="4">0,000.00X</option>
	<option value="5">X0 000.00</option>
</select>
*/

			$table->tinyInteger('blank')->default(0);							// Include a space between symbol and price (e.g. $1,240.15 -&gt; $ 1,240.15)
	//		$table->tinyInteger('decimals')->default(1);						// Display decimals in prices
			$table->decimal('conversion_rate', 20, 6)->default(1.0);	// Exchange rates are calculated from one unit of your default currency. For example, if the default currency is euros and your chosen currency is dollars, type &quot;1.20&quot; (1&amp;euro; = $1.20)
			
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
		Schema::dropIfExists('currencies');
	}

}
