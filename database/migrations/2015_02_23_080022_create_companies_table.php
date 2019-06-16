<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_fiscal', 128)->nullable(false);			// Company
			$table->string('name_commercial', 64)->nullable();
			$table->string('identification', 64)->nullable(false);			// VAT ID or the like (only companies & pro's?)
			$table->tinyInteger('apply_RE')->default(0);					// Apply Sales Equalization Tax (Recargo de Equivalencia) on purchases

			$table->string('website', 128)->nullable();

			$table->string('company_logo', 128)->nullable();				// Usually lives in: /public/img/ 
																			// Image types?
			$table->text('notes')->nullable();

			// ToDo: extra fields: "Registro mercantil" and the like

			$table->integer('currency_id')->unsigned()->nullable(false); 
            $table->integer('language_id')->unsigned()->nullable(false); 
            $table->integer('bank_account_id')->unsigned()->nullable(); 
			
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
		Schema::dropIfExists('companies');
	}

}
