<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionSettlementLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('commission_settlement_lines');
        
        Schema::create('commission_settlement_lines', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('commissionable_id');
            $table->string('commissionable_type');

            $table->string('document_reference', 64)->nullable();       // document_prefix + document_id of commissionable model_name 
            $table->dateTime('document_date');
//            $table->integer('customer_id')->unsigned()->nullable(false);

            $table->decimal('document_commissionable_amount', 20, 6)->default(0.0);     // Starting point for settlement calculations
            // Simplyfy: commission_percent = salesrep->commission_percent
            // Remember: different products may have different commission percent
            $table->decimal('commission_percent', 8, 3)->default(0.0);
            $table->decimal('commission', 20, 6)->default(0.0);       // This amount will be added to settlement

            // if force => commission_percent = salesrep->commission_percent
            // commission = document_commissionable_amount * ( commission_percent / 100.0 )
            // but not always true: different products may have different commission percent within a document
            // In this case: commission_percent = commission / document_commissionable_amount
            // and commission is calculated per line

            $table->integer('commission_settlement_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('commission_settlement_lines');
    }
}
