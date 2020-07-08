<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheques', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('document_number', 32)->nullable(false);     // Cheque number
            $table->string('place_of_issue', 64)->nullable(false);
            $table->decimal('amount', 20, 6)->default(0.0);

            $table->date('date_of_issue')->nullable();
            $table->date('due_date')->nullable();                           // Due date, expiration date or "valid until" date
            $table->date('payment_date')->nullable();                       // Cheque heque is cleared
            $table->date('posted_at')->nullable();                          // Recorded (in account, General Ledger) at

            $table->date('date_of_entry')->nullable();

            $table->string('memo', 128)->nullable();
            $table->text('notes')->nullable();

            $table->string('status', 32)->nullable(false)->default('pending');

            $table->integer('currency_id')->unsigned()->nullable(false);
            $table->integer('customer_id')->unsigned()->nullable(false);    // Drawer or cheque issuer; They sign the cheque
            $table->string('drawee_bank_id', 64)->nullable(false);          // Deudor, librado
            // My company is the payee

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
        Schema::dropIfExists('cheques');
    }
}
