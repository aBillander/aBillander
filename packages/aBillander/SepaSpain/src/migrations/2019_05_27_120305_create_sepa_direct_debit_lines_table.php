<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSepaDirectDebitLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sepa_direct_debit_lines');
        
        Schema::create('sepa_direct_debit_lines', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 32);

            $table->string('bank_account_code', 6);

            $table->string('iban',  34);        // https://es.wikipedia.org/wiki/International_Bank_Account_Number
            $table->string('swift', 11)->nullable();        // ISO 9362, también conocido como código SWIFT o código BIC es un código de identificación bancaria más utilizado para realizar las transferencias internacionales de dinero
                                                // https://es.wikipedia.org/wiki/ISO_9362
            $table->string('creditorid', 30)->nullable(false);

            $table->string('currency_iso_code', 3)->nullable(false);                     // ISO code (e.g. USD for Dollars, EUR for Euros, etc.)
            $table->decimal('currency_conversion_rate', 20, 6)->default(1.0);    // Exchange rates are calculated from one unit of your default currency. For example, if the default currency is euros and your chosen currency is dollars, type &quot;1.20&quot; (1&amp;euro; = $1.20)

            $table->string('status', 32)->nullable(false)->default('pending');

            $table->date('document_date');
            $table->date('payment_date')->nullable();

            $table->decimal('total', 20, 6)->default(0.0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sepa_direct_debit_lines');
    }
}
