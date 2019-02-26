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
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_documents_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_documents_table.php';
            }

			$table->string('type', 32)->nullable(false)->default('invoice');
			// 'invoice'     => Regular Invoice
			// 'corrective'  => corrective invoice or rectification invoice. 
								// See: https://www.modelofactura.net/la-factura-rectificativa-en-ingles.html
			// 'credit'      => Credit note invoice (refunds, rebates, sales returns)
			// 'deposit'     => Deposit invoice (down payments)

			$table->string('payment_status', 32)->nullable(false)->default('pending');

			$table->string('stock_status', 32)->nullable(false)->default('pending');
			// 'pending'   => Not performed
			// 'completed' => Performed

//			$table->date('valid_until_date')->nullable();						// For Proposals!

			$table->date('next_due_date')->nullable();							// Next payment due date

			$table->date('printed_at')->nullable();								// Printed at
			$table->date('edocument_sent_at')->nullable();						// Electronic document sent at
			$table->date('customer_viewed_at')->nullable();						// Customer retrieved invoice from online customer center
			$table->date('posted_at')->nullable();								// Recorded (in account, General Ledger) at


			$table->tinyInteger('prices_entered_with_tax')->default(0);			// See: PRICES_ENTERED_WITH_TAX; Maybe not needed here (stored for every invoice)
			$table->tinyInteger('round_prices_with_tax')->default(0);			// See: ROUND_PRICES_WITH_TAX


			$table->decimal('open_balance', 20, 6)->default(0.0);
			
			// For certain type of invoices: corrective, credit and maybe deposit
			$table->integer('parent_id')->unsigned()->nullable();

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
