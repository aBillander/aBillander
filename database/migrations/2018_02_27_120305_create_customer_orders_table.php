<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_orders');
        
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sequence_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
            $table->string('reference')->nullable();                            // Project reference, etc.

            $table->string('created_via', 32)->default('webshop')->nullable();   // 'webshop', 'manual'

            $table->dateTime('date_created');
            $table->dateTime('date_paid');              // 
            $table->date('delivery_date')->nullable();

            $table->string('total');                        // Grand total. WooCommerces serve it as string
            
            $table->text('customer')->nullable();      // Customer data: name, address, phone
            $table->text('customer_note')->nullable();
            $table->text('notes')->nullable();                  // Private notes ( notes to self ;) )
            $table->text('notes_to_customer')->nullable();      // Notes for the Customer

            $table->string('status', 32)->nullable();

//            $table->dateTime('date_abi_exported');        // Not needed! This is "created_at" date
            $table->dateTime('production_at')->nullable();     // Scheduled to Production
            
//            $table->string('secure_key', 32)->nullable();                  // = md5(uniqid(rand(), true))

            $table->integer('production_sheet_id')->unsigned()->nullable();

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
        Schema::dropIfExists('customer_orders');
    }
}
