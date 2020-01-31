<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// /c/xampp/php/php artisan migrate --path=/database/migrations/solo --pretend

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('suppliers');
			
		Schema::create('suppliers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('alias', 32)->nullable();

			$table->string('name_fiscal', 128)->nullable(false);						// Company
			$table->string('name_commercial', 64)->nullable();

			$table->string('website', 128)->nullable();
			$table->string('customer_center_url', 128)->nullable();				// Supplier Customer Center access data
			$table->string('customer_center_user', 128)->nullable();			// Maybe an email address
			$table->string('customer_center_password', 16)->nullable();			// Plaintext, not encripted
			
			$table->string('identification', 64)->nullable();					// VAT ID or the like (only companies & pro's?)
			// EU VAT Id, Tax number, etc.
			
            $table->string('reference_external', 32)->nullable();         // To allow an external system or interface to save its own internal reference to have a link between records into aBillander and records into an external system
			$table->string('accounting_id', 32)->nullable();				// Customer's account code 

            $table->decimal('discount_percent', 20, 6)->default(0.0); 		 // Order/Document discount 
            $table->decimal('discount_ppd_percent', 20, 6)->default(0.0);    // Order/Document prompt payment discount Percent

			$table->string('payment_days', 16)->nullable();					// Comma separated integuers!

			$table->tinyInteger('delivery_time')->default(0);

		/* */
			$table->text('notes')->nullable();

			$table->string('customer_logo', 128)->nullable();				// Usually lives in: /public/........

			$table->tinyInteger('sales_equalization')->default(0);				// Charge Sales equalization tax? (only Spain)
		/* */

			$table->tinyInteger('creditor')->default(0);					// This is a Creditor, not a Supplier

			$table->tinyInteger('approved')->default(1);						// Approved (or ertified) Supplier
			$table->tinyInteger('blocked')->default(0);							// Sales not allowed
			$table->tinyInteger('active')->default(1);

			$table->integer('customer_id')->unsigned()->nullable();				// If this supplier is also a Customer

			$table->integer('currency_id')->unsigned()->nullable(false);
			$table->integer('language_id')->unsigned()->nullable(false);
			$table->integer('payment_method_id')->unsigned()->nullable();
            $table->integer('bank_account_id')->unsigned()->nullable();

			$table->integer('invoice_sequence_id')->unsigned()->nullable();

			$table->integer('invoicing_address_id')->unsigned()->nullable(false);
//			$table->integer('shipping_address_id')->unsigned()->nullable();
			
			$table->string('secure_key', 32)->nullable(false);					// = md5(uniqid(rand(), true))

            $table->string('import_key', 16)->nullable();
            // This field contains an id defined by an import process (when using an Import Module). Goal is to have a field to link and track all records that are added into database by an import process/transaction. This can be used to make a mass delete correction if an import was made successfully by error. 
			
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
		Schema::dropIfExists('suppliers');
	}

}
