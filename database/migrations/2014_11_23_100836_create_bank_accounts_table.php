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
		Schema::create('bank_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 64)->nullable(false);
			$table->string('iban', 34);
			$table->string('swift', 11);

			$table->string('mandate_reference', 35)->nullable();
			$table->date('mandate_date')->nullable();
			$table->date('first_recurring_date')->nullable();

			$table->text('notes')->nullable();

			$table->integer('bank_accountable_id')->unsigned()->nullable(false);
			$table->string('bank_accounable_type');

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
