<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('customer_invoices');

		Schema::create('customer_invoices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('sequence_id')->unsigned()->nullable(false);
			$table->integer('customer_id')->unsigned()->nullable(false);
			$table->string('document_prefix', 8)->nullable();					// From Sequence. Needed for index.
			$table->integer('document_id')->unsigned()->default(0);
			$table->string('document_reference', 64)->nullable();				// document_prefix + document_id of document
			$table->string('reference')->nullable();							// Project reference, etc.
			$table->decimal('document_discount', 20, 6)->default(0.0);			// Order/Document discount Percent
			$table->date('document_date');
			$table->date('valid_until_date')->nullable();						// For Proposals!
			$table->date('delivery_date')->nullable();							// Whilie using invoice as Shipping Slip!
			$table->date('delivery_date_real')->nullable();						// 
			$table->date('next_due_date')->nullable();							// Next payment due date
			$table->date('printed_at')->nullable();								// Printed at
			$table->date('edocument_sent_at')->nullable();						// Electronic document sent at
			$table->date('customer_viewed_at')->nullable();						// Customer retrieved invoice from online customer center
			$table->date('posted_at')->nullable();								// Recorded (in account, General Ledger) at

			$table->smallInteger('number_of_packages')->unsigned()->default(1);
			$table->text('shipping_conditions')->nullable();					// For Shipping Slip!
			$table->string('tracking_number')->nullable();						// For Shipping Slip!

			$table->tinyInteger('prices_entered_with_tax')->default(0);			// See: PRICES_ENTERED_WITH_TAX; Maybe not needed here (stored for every invoice)
			$table->tinyInteger('round_prices_with_tax')->default(0);			// See: ROUND_PRICES_WITH_TAX

			$table->decimal('currency_conversion_rate', 20, 6)->default(1.0);
			$table->decimal('down_payment', 20, 6)->default(0.0);	        	// Payment before issue invoice
			$table->decimal('open_balance', 20, 6)->default(0.0);

			$table->decimal('total_discounts_tax_incl', 20, 6)->default(0.0);	// Order/Document discount
			$table->decimal('total_discounts_tax_excl', 20, 6)->default(0.0);
			$table->decimal('total_products_tax_incl', 20, 6)->default(0.0);	// Product netto (product discount included!)
			$table->decimal('total_products_tax_excl', 20, 6)->default(0.0);
			$table->decimal('total_shipping_tax_incl', 20, 6)->default(0.0);
			$table->decimal('total_shipping_tax_excl', 20, 6)->default(0.0);
			$table->decimal('total_other_tax_incl', 20, 6)->default(0.0);
			$table->decimal('total_other_tax_excl', 20, 6)->default(0.0);
			
			$table->decimal('total_tax_incl', 20, 6)->default(0.0);
			$table->decimal('total_tax_excl', 20, 6)->default(0.0);

			$table->decimal('commission_amount', 20, 6)->default(0.0);			// Sales Representative commission amount

			$table->text('notes')->nullable();					// Private notes ( notes to self ;) )
			$table->text('notes_to_customer')->nullable();		// Notes for the Customer

//			$table->enum('status', array('draft', 'pending', 'halfpaid', 'paid', 'doubtful'))->default('draft');
			$table->string('status', 32)->nullable(false);

//			$table->tinyInteger('locked')->default(0);							// 0 -> NO; 1 -> Yes (Invoice cannot be modified if sent to customer, payments are in place, etc).  <= Not needed: status=draft == locked=0 and status=draft != locked=1

//			$table->tinyInteger('einvoice')->default(0);						// 0: Not accepted; 1 Accepted  <- Property of the Customer!!
//			$table->tinyInteger('einvoice_sent')->default(0);					// Document sent. See also: field "edocument_sent_at"
//			$table->tinyInteger('printed')->default(0);
//			$table->tinyInteger('customer_viewed')->default(0);					// Customer retrieved invoice from online customer center
//			$table->tinyInteger('posted')->default(0);							// Recorded in account (General Ledger)

//			$table->integer('status_id');
			$table->integer('invoicing_address_id')->unsigned()->nullable(false);
			$table->integer('shipping_address_id')->unsigned()->nullable();		// For Shipping Slip!
			$table->integer('warehouse_id')->unsigned()->nullable();
			$table->integer('carrier_id')->unsigned()->nullable();
			$table->integer('sales_rep_id')->unsigned()->nullable();             // Sales representative
			$table->integer('currency_id')->unsigned()->nullable(false);
			$table->integer('payment_method_id')->unsigned()->nullable(false);
			$table->integer('template_id')->nullable();										// Template for printer
//			$table->integer('parent_document_id')->unsigned()->nullable();		// Parent of Invoice is Shipping Slip
			
			$table->string('secure_key', 32)->nullable(false);					// = md5(uniqid(rand(), true))

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
		Schema::dropIfExists('customer_invoices');
	}

}
