<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('customer_groups');
		
		Schema::create('customer_groups', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('company_id')->unsigned()->default(0);              // For multi-Company setup
            $table->integer('user_id')->unsigned()->default(0);            // Maybe creator user, modifier user
            
			$table->string('name', 32)->nullable(false);
			// price_display_method
			// show_prices
			$table->string('webshop_id', 16)->nullable();						// Customer's Web Shop id

//			$table->tinyInteger('accept_einvoice')->default(1);
			$table->tinyInteger('active')->default(1);

//			$table->integer('currency_id')->unsigned()->nullable();
//			$table->integer('payment_method_id')->unsigned()->nullable();
//			$table->integer('sequence_id')->unsigned()->nullable();
			$table->integer('invoice_template_id')->unsigned()->nullable();
//			$table->integer('carrier_id')->unsigned()->nullable();
			$table->integer('shipping_method_id')->unsigned()->nullable();
			$table->integer('price_list_id')->unsigned()->nullable();
//			$table->integer('direct_debit_account_id')->unsigned()->nullable(); // Cuenta remesas
			
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
		Schema::dropIfExists('customer_groups');
	}

}
