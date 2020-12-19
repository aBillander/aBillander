<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('supplier_invoices');

        Schema::create('supplier_invoices', function (Blueprint $table) {

            $entity = 'supplier';
            
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

//          $table->date('valid_until_date')->nullable();                       // For Proposals!

            $table->date('next_due_date')->nullable();                          // Next payment due date

            $table->date('posted_at')->nullable();                              // Recorded (in account, General Ledger) at


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
        Schema::dropIfExists('supplier_invoices');
    }
}
