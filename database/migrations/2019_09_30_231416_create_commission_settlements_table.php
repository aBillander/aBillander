<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('commission_settlements');
        
        Schema::create('commission_settlements', function (Blueprint $table) {

            $table->increments('id');

/*  Not needed so far, but maybe in the future
            $table->integer('sequence_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
*/

            $table->string('name', 128)->nullable();
            $table->dateTime('document_date');
            $table->dateTime('date_from');                         // Settlement period from - to
            $table->dateTime('date_to');

            $table->tinyInteger('paid_documents_only')->default(0);

            $table->string('status', 32)->nullable(false)->default('pending');
            $table->tinyInteger('onhold')->default(0);            // 0 -> NO; 1 -> Yes (Document cannot change status)

            $table->decimal('total_commissionable', 20, 6)->default(0.0);   // Remember: different products may have different commission percent
            $table->decimal('total_settlement', 20, 6)->default(0.0);       // This amount will be paid to the Sales Rep

            $table->text('notes')->nullable();

            $table->dateTime('close_date')->nullable();         // Settlement cannot be modified after this date

            $table->date('posted_at')->nullable();              // Recorded (in account, General Ledger)

            $table->integer('sales_rep_id')->unsigned()->nullable(false);       // Sales representative

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
        Schema::dropIfExists('commission_settlements');
    }
}
