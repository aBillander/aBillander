<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('down_payments', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('document_number', 32)->nullable(false);     // Cheque number or the like
            $table->decimal('amount', 20, 6)->default(0.0);

            $table->date('date_of_issue')->nullable();
            $table->date('due_date')->nullable();                           // Due date, expiration date or "valid until" date
            $table->date('payment_date')->nullable();                       // Cheque heque is cleared
            $table->date('posted_at')->nullable();                          // Recorded (in account, General Ledger) at


            $table->integer('currency_id')->unsigned()->nullable(false);
            $table->decimal('currency_conversion_rate', 20, 6)->default(1.0);

            $table->string('status', 32)->nullable(false)->default('pending');

//            $table->string('memo', 128)->nullable();
            $table->text('notes')->nullable();

            $table->integer('payment_type_id')->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();

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
        Schema::dropIfExists('down_payments');
    }
}
