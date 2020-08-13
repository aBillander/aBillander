<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseShippingSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('warehouse_shipping_slips');
        
        Schema::create('warehouse_shipping_slips', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->default(0);              // For multi-Company setup
            $table->integer('warehouse_id')->unsigned()->nullable(false);
            $table->integer('warehouse_counterpart_id')->unsigned()->nullable(false);
            $table->integer('user_id')->unsigned()->default(0);            // Maybe creator user, validator user, closer user

            $table->integer('sequence_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
            $table->string('reference')->nullable();                            // Project reference, etc.

            $table->string('created_via', 32)->default('manual')->nullable();
            // How we received the order: 'webshop', 'manual', 'aggregate', 'by email', etc.

            $table->dateTime('document_date');                          // If document is imported, document_date != created_at
            $table->dateTime('validation_date')->nullable();

            $table->dateTime('delivery_date')->nullable();              // Expected delivery date. While using invoice as Shipping Slip!
            $table->dateTime('delivery_date_real')->nullable();         // Real delivery date. While using invoice as Shipping Slip!
            $table->dateTime('close_date')->nullable();             // A Customer order is closed with status Shipping/Delivered or Billed  =>  A Customer order is closed when a Shipping slip or Invoice is created after it, When a Customer order is closed, it cannot be modified.


            $table->smallInteger('number_of_packages')->unsigned()->default(1);
            $table->decimal('volume', 20, 6)->nullable()->default(0.0);  // m3
            $table->decimal('weight', 20, 6)->nullable()->default(0.0);  // kg
            $table->text('shipping_conditions')->nullable();                    // For Shipping Slip!
            $table->string('tracking_number')->nullable();                      // For Shipping Slip!


            $table->text('notes')->nullable();                  // Private notes ( notes to self ;) )
            $table->text('notes_to_counterpart')->nullable();      // Notes for the Customer

//          $table->enum('status', array('draft', 'confirmed', 'closed', 'canceled'))->default('draft');
            $table->string('status', 32)->nullable(false)->default('draft');
            $table->tinyInteger('onhold')->default(0);            // 0 -> NO; 1 -> Yes (Document cannot change status)

            $table->tinyInteger('locked')->default(0);            // 0 -> NO; 1 -> Yes (Order cannot be modified if retrieved from external system, i.e., webshop)

//            $table->integer('invoicing_address_id')->unsigned()->nullable(false);
//            $table->integer('shipping_address_id')->unsigned()->nullable();     // For Shipping Slip!
            $table->integer('shipping_method_id')->unsigned()->nullable();
            $table->integer('carrier_id')->unsigned()->nullable();
            $table->integer('template_id')->nullable();
//            $table->integer('language_id')->nullable();


            $table->string('shipment_status', 32)->nullable(false)->default('pending');

            $table->string('shipment_service_type_tag', 32)->nullable();

            $table->date('printed_at')->nullable();                             // Printed at
            $table->date('edocument_sent_at')->nullable();                      // Electronic document sent at

            // **>

            $table->dateTime('export_date')->nullable();                // Exported to an external system (such as FactuSOL)
            
            $table->string('secure_key', 32)->nullable(false);                  // = md5(uniqid(rand(), true))

            $table->string('import_key', 16)->nullable();
            // This field contains an id defined by an import process (when using an Import Module). Goal is to have a field to link and track all records that are added into database by an import process/transaction. This can be used to make a mass delete correction if an import was made successfully by error. 

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
        Schema::dropIfExists('warehouse_shipping_slips');
    }
}
