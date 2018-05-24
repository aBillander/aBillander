<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('payments');

		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('payment_type', 32)->nullable(false);
			// 'receivable' => Payment from customer (receive money, inbound)
			// 'payable'    => Payment to supplier (send money, outbound)
			
			$table->string('reference')->nullable();			// Creditor (usually Supplier) reference			
			$table->string('name', 32)->nullable();				// Payment subject/detail

			$table->date('due_date')->nullable(false);
			$table->date('payment_date')->nullable();			// Real payment date
			$table->decimal('amount', 20, 6)->nullable(false);
	//		$table->decimal('amount_paid', 20, 6)->nullable();	// If amount is not fully paid, a new payment will be created for the difference

			$table->integer('currency_id')->unsigned()->nullable(false);
			$table->decimal('currency_conversion_rate', 20, 6)->default(1.0);

//			$table->enum('status', array('pending', 'bounced', 'paid'))->default('pending');
			$table->string('status', 32)->nullable(false);

			$table->text('notes')->nullable();


			// Document
			$table->integer('paymentable_id');
			$table->string('paymentable_type');
			$table->string('document_reference', 64)->nullable();		// document_prefix + document_id of document

			// Paymentor (creditor or debitor)
			$table->integer('paymentorable_id');
			$table->string('paymentorable_type');


/*
			$table->integer('invoice_id')->unsigned()->nullable(false);		// Customer / Supplier document (Invoice, Credit Note or Debit Note)
			$table->string('model_name', 64)->nullable(false);  // Payment may be owned by a CustomerInvoice, SupplierInvoice...!
			$table->integer('owner_id')->unsigned()->nullable(false);		// Customer or Supplier
			$table->string('owner_model_name', 64)->nullable(false);        // Payment may be owned by a Customer or Supplier
*/

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
		Schema::dropIfExists('payments');
	}

}
