<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerInvoiceLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('customer_invoice_lines');
		
		Schema::create('customer_invoice_lines', function(Blueprint $table)
		{
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php';
            }

			// Parent Document
			$table->integer('customer_invoice_id')->unsigned()->nullable(false);

			// This line comes from this Customer Shipping Slip ()although not allways!
			$table->integer('customer_shipping_slip_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customer_invoice_lines');
	}

}
