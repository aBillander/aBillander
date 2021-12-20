<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('customers');
		
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
//			$table->string('customer_type', 32)->nullable(false)->default('simple');
			// 'individual', 'company'
			// maybe use "const __default" of the model 
            
            $table->integer('company_id')->unsigned()->default(0);              // For multi-Company setup
            $table->integer('user_id')->unsigned()->default(0);            // Maybe creator user, modifier user

			$table->string('name_fiscal', 128)->nullable(false);						// Company
			$table->string('name_commercial', 64)->nullable();

			$table->string('website', 128)->nullable();
			
			$table->string('identification', 64)->nullable();					// VAT ID or the like (only companies & pro's?)
			// EU VAT Id, Tax number, etc.

			$table->string('webshop_id', 16)->nullable();
            $table->string('reference_external', 32)->nullable();         // To allow an external system or interface to save its own internal reference to have a link between records into aBillander and records into an external system
			$table->string('accounting_id', 32)->nullable();				// Customer's account code 

            $table->decimal('discount_percent', 20, 6)->default(0.0); 		 // Order/Document discount 
            $table->decimal('discount_ppd_percent', 20, 6)->default(0.0);    // Order/Document prompt payment discount Percent

			$table->string('payment_days', 16)->nullable();					// Comma separated integuers!
			$table->tinyInteger('no_payment_month')->default(0);
			
			$table->decimal('outstanding_amount_allowed', 20, 6)->default(0.0);	// Maximum outstanding amount allowed
			$table->decimal('outstanding_amount', 20, 6)->default(0.0);	        // Actual balance
			$table->decimal('unresolved_amount', 20, 6)->default(0.0);	        // Uncertain Payment

			$table->text('notes')->nullable();

			$table->string('customer_logo', 128)->nullable();				// Usually lives in: /public/........

			$table->tinyInteger('is_invoiceable')->default(1);				// Useful for internal customers & departments (is_invoiceable = false)
			$table->tinyInteger('invoice_by_shipping_address')->default(0);
			// 
        	// "0" => 'No': use form settings (default behaviour),
        	// "1" => 'Force 1 Invoice per Shipping Address',
        	// "2" => 'Force 1 Invoice per Shipping Slip and Shipping Address',
			$table->tinyInteger('automatic_invoice')->default(1);			// Include Customer Shipping Slips in automatic Invoicing process
			$table->string('cc_addresses', 128)->nullable();				// Send carbon copy email to these (comma separated) addresses. Used with invoices

			$table->tinyInteger('vat_regime')->default(0);
			$table->tinyInteger('sales_equalization')->default(0);				// Charge Sales equalization tax? (only Spain)
			$table->tinyInteger('allow_login')->default(0);						// Allow login to Customer Center
			$table->tinyInteger('accept_einvoice')->default(0);					// Accept electronic invoice
			$table->tinyInteger('blocked')->default(0);							// Sales not allowed
			$table->tinyInteger('active')->default(1);
			
			$table->integer('sales_rep_id')->unsigned()->nullable();             // Sales representative. Maybe "Sales Agent"
//			$table->integer('primary_contact_id')->unsigned()->nullable();		// Some user here
			$table->integer('currency_id')->unsigned()->nullable(false);
			$table->integer('language_id')->unsigned()->nullable(false);
			$table->integer('customer_group_id')->unsigned()->nullable();
			$table->integer('payment_method_id')->unsigned()->nullable();
            $table->integer('bank_account_id')->unsigned()->nullable();
			$table->integer('invoice_sequence_id')->unsigned()->nullable();
			$table->integer('invoice_template_id')->unsigned()->nullable();

			$table->integer('order_template_id')->unsigned()->nullable();
			$table->integer('shipping_slip_template_id')->unsigned()->nullable();

//			$table->integer('carrier_id')->unsigned()->nullable();
			$table->integer('shipping_method_id')->unsigned()->nullable();
			$table->integer('price_list_id')->unsigned()->nullable();
			$table->integer('direct_debit_account_id')->unsigned()->nullable(); // Cuenta remesas

			$table->integer('invoicing_address_id')->unsigned()->nullable(false);
			$table->integer('shipping_address_id')->unsigned()->nullable();
			
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
		Schema::dropIfExists('customers');
	}

}
