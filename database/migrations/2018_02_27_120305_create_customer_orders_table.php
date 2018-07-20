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
            $table->integer('company_id')->unsigned()->default(0);              // For multi-Company setup
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->default(0);            // Maybe creator user, validator user, closer user

            $table->integer('sequence_id')->unsigned()->nullable();
            $table->string('document_prefix', 8)->nullable();                   // From Sequence. Needed for index.
            $table->integer('document_id')->unsigned()->default(0);
            $table->string('document_reference', 64)->nullable();               // document_prefix + document_id of document
            $table->string('reference')->nullable();                            // Project reference, etc.

            $table->string('reference_customer', 32)->nullable();         // Custumer order number 
            $table->string('reference_external', 32)->nullable();         // To allow an external system or interface to save its own internal reference to have a link between records into aBillander and records into an external system

            $table->string('created_via', 32)->default('webshop')->nullable();
            // How we received the order: 'webshop', 'manual', 'by phone', 'by email', etc.

            $table->dateTime('document_date');                          // If document is imported, document_date != created_at
            $table->dateTime('payment_date')->nullable();               // Orders from webshop are (usually) paid"
            $table->dateTime('validation_date')->nullable();
//            $table->date('valid_until_date')->nullable();             // For Proposals!
            $table->dateTime('delivery_date')->nullable();              // Expected delivery date. While using invoice as Shipping Slip!
            $table->dateTime('delivery_date_real')->nullable();         // Real delivery date. While using invoice as Shipping Slip!
            $table->dateTime('close_date')->nullable();             // A Customer order is closed with status Shipping/Delivered or Billed  =>  A Customer order is closed when a Shipping slip or Invoice is created after it, When a Customer order is closed, it cannot be modified.

/*
            $table->dateTime('date_created');
            $table->dateTime('date_validated')->nullable();
            $table->dateTime('date_delivered')->nullable();
            $table->dateTime('date_invoiced')->nullable();
            $table->dateTime('date_closed')->nullable();

            $table->date('printed_at')->nullable();                             // Printed at
            $table->date('edocument_sent_at')->nullable();                      // Electronic document sent at
            $table->date('customer_viewed_at')->nullable();                     // Customer retrieved document / (invoice) from online customer center
            $table->date('posted_at')->nullable();                              // Recorded (in account, General Ledger) at
*/

            $table->decimal('document_discount_percent', 20, 6)->default(0.0);  // Order/Document discount Percent
            $table->decimal('document_discount_amount_tax_incl', 20, 6)->default(0.0);   // Order/Document discount Amount
            $table->decimal('document_discount_amount_tax_excl', 20, 6)->default(0.0);

            $table->smallInteger('number_of_packages')->unsigned()->default(1);
            $table->text('shipping_conditions')->nullable();                    // For Shipping Slip!
            $table->string('tracking_number')->nullable();                      // For Shipping Slip!

            $table->decimal('currency_conversion_rate', 20, 6)->default(1.0);
            $table->decimal('down_payment', 20, 6)->default(0.0);               // Payment before issue invoice

            $table->decimal('total_discounts_tax_incl', 20, 6)->default(0.0);   // Order/Document discount lines
            $table->decimal('total_discounts_tax_excl', 20, 6)->default(0.0);
            $table->decimal('total_products_tax_incl', 20, 6)->default(0.0);    // Product netto (product discount included!)
            $table->decimal('total_products_tax_excl', 20, 6)->default(0.0);
            $table->decimal('total_shipping_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_shipping_tax_excl', 20, 6)->default(0.0);
            $table->decimal('total_other_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_other_tax_excl', 20, 6)->default(0.0);
            
//            $table->string('total');                        // Grand total. WooCommerces serve it as string
            
            $table->decimal('total_lines_tax_incl', 20, 6)->default(0.0);       // total = total_lines - document_discount
            $table->decimal('total_lines_tax_excl', 20, 6)->default(0.0);
            
            $table->decimal('total_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_tax_excl', 20, 6)->default(0.0);

            $table->decimal('commission_amount', 20, 6)->default(0.0);          // Sales Representative commission amount

            $table->text('notes_from_customer')->nullable();          // Notes FROM the Customer
            $table->text('notes')->nullable();                  // Private notes ( notes to self ;) )
            $table->text('notes_to_customer')->nullable();      // Notes for the Customer

//          $table->enum('status', array('draft', 'confirmed', 'closed', 'canceled'))->default('draft');
            $table->string('status', 32)->nullable(false)->default('draft');

            $table->tinyInteger('locked')->default(0);                          // 0 -> NO; 1 -> Yes (Order cannot be modified if retrieved from external system, i.e., webshop)

            $table->integer('invoicing_address_id')->unsigned()->nullable(false);
            $table->integer('shipping_address_id')->unsigned()->nullable();     // For Shipping Slip!
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('shipping_method_id')->unsigned()->nullable();
            $table->integer('carrier_id')->unsigned()->nullable();
            $table->integer('shipping_method_id')->unsigned()->nullable();
            $table->integer('sales_rep_id')->unsigned()->nullable();             // Sales representative
            $table->integer('currency_id')->unsigned()->nullable(false);
            $table->integer('payment_method_id')->unsigned()->nullable(false);
            $table->integer('template_id')->nullable();

            $table->integer('parent_document_id')->unsigned()->nullable();      // Parent of Order is Quotation

//            $table->dateTime('production_at')->nullable();                      // Scheduled to Production
            $table->integer('production_sheet_id')->unsigned()->nullable();
            
            $table->string('secure_key', 32)->nullable(false);                  // = md5(uniqid(rand(), true))

            $table->string('import_key', 16)->nullable(false);
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
        Schema::dropIfExists('customer_orders');
    }
}
