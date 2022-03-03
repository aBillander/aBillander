<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_sort_order')->nullable();         // To sort lines
            $table->string('name', 128)->nullable(false);
            $table->decimal('amount', 20, 6)->default(0.0);

            // Related Document
            $table->integer('payment_id')->unsigned()->nullable();

            $table->integer('customer_invoice_id')->unsigned()->nullable();
            $table->string('customer_invoice_reference', 64)->nullable();

            // Parent Document
            $table->integer('cheque_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('cheque_details');
    }
}
