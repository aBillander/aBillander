<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOrderTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_order_templates', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('alias', 32)->nullable();           // For dropdown selectors
            $table->string('name', 128)->nullable(false);

            $table->decimal('document_discount_percent', 20, 6)->default(0.0);  // Order/Document discount Percent
            $table->decimal('document_ppd_percent', 20, 6)->default(0.0);           // Order/Document prompt payment discount

            $table->text('notes')->nullable();                  // Private notes ( notes to self ;) )

            $table->tinyInteger('active')->default(1);
            $table->timestamp('last_used_at')->nullable();

            $table->integer('last_customer_order_id')->nullable();
            $table->string('last_document_reference', 64)->nullable();
            $table->decimal('total_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_tax_excl', 20, 6)->default(0.0);

            $table->integer('customer_id')->unsigned()->nullable(false);
            $table->integer('shipping_address_id')->unsigned()->nullable(false);
            $table->integer('template_id')->nullable();

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
        Schema::dropIfExists('customer_order_templates');
    }
}
