<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('bank_accounts');

		Schema::create('bank_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 64)->nullable(false);

			$table->string('bank_name',  64);		// ->nullable(false);
			$table->string('ccc_entidad', 4);
			$table->string('ccc_oficina', 4);
			$table->string('ccc_control', 2);
			$table->string('ccc_cuenta', 10);

			$table->string('iban',  34);		// https://es.wikipedia.org/wiki/International_Bank_Account_Number
			$table->string('swift', 11);		// ISO 9362, también conocido como código SWIFT o código BIC es un código de identificación bancaria más utilizado para realizar las transferencias internacionales de dinero
												// https://es.wikipedia.org/wiki/ISO_9362

			$table->string('mandate_reference', 35)->nullable();
			$table->date('mandate_date')->nullable();
//			$table->date('first_recurring_date')->nullable();	// Not here. For recurring payments

			$table->text('notes')->nullable();

			$table->integer('bank_accountable_id');		// ->unsigned()->nullable(false);
			$table->string('bank_accountable_type');

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
		Schema::drop('bank_accounts');
	}

}
