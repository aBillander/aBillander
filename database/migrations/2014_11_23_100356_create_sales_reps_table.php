<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesRepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('sales_reps');
		
		// Maybe Sales Agent
		Schema::create('sales_reps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sales_rep_type', 32)->nullable(false)->default('external');
			// 'external'    => Spain: accounting : 41* (Suppliers).
			// 'employee' => Spain: accounting : 465.

			$table->string('alias', 32)->nullable(false);
			$table->string('identification', 64)->nullable();					// VAT ID or the like (only companies & pro's?). Número del Documento Nacional de Identidad o Código de Identificación Fiscal. Al introducir el DNI, y darle al enter, nos calculará automáticamente la letra para el NIF, podemos aceptar o no el cambio del mismo.

			$table->string('firstname', 32)->nullable();			// Contact information
			$table->string('lastname', 32)->nullable();
			$table->string('email', 128)->nullable();

			$table->string('phone', 32)->nullable();
			$table->string('phone_mobile', 32)->nullable();
			$table->string('fax', 32)->nullable();

			$table->text('notes')->nullable();
			
            $table->string('reference_external', 32)->nullable();         // To allow an external system or interface to save its own internal reference to have a link between records into aBillander and records into an external system
            $table->string('accounting_id', 32)->nullable();				// Sale Rep's account code 

			$table->decimal('commission_percent', 8, 3)->default(0.0);			// Sales Representative commission amount
			$table->decimal('max_discount_allowed', 20, 6)->default(0.0);		// Sales Representative max discount allowed

			$table->decimal('pitw', 20, 6)->default(0.0);						// Porcentaje de retención de la empresa al agente
																				// percentage of withholdings on account of Personal Income Tax 

		//	$table->integer('address_id')->unsigned()->nullable(false);

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
		Schema::dropIfExists('sales_reps');
	}

}
