<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            
            $table->decimal('quantity_onhand', 20, 6)->default(0);          // In Stock; on hand
            $table->decimal('quantity_onorder', 20, 6)->default(0);         // On order; Quantity on Purchase Orders (pendiente de recibir)
            $table->decimal('quantity_allocated', 20, 6)->default(0);       // Allocated; Quantity on Sale Orders (reservado)
            $table->decimal('quantity_onorder_mfg', 20, 6)->default(0);     // On order (manufacturing); Quantity on Production Orders
            $table->decimal('quantity_allocated_mfg', 20, 6)->default(0);   // Allocated (manufacturing); Quantity allocated for Production Orders
                                                                            // Available: instock + onorder - allocated

            $table->decimal('reorder_point', 20, 6)->default(0);            // Acts like safety stock or minimum stock
            $table->decimal('maximum_stock', 20, 6)->default(0);    
            
            $table->decimal('price', 20, 6)->default(0.0);
            $table->decimal('price_tax_inc', 20, 6)->default(0.0);
//          $table->tinyInteger('price_is_tax_inc')->default(0);            // Only applies to sales price
            $table->decimal('last_purchase_price', 20, 6)->default(0.0);
            $table->decimal('cost_price', 20, 6)->default(0.0);
            $table->decimal('cost_average', 20, 6)->default(0.0);           // Should be on per warehouse base?

            $table->string('supplier_reference', 32)->nullable();
            $table->integer('supply_lead_time')->unsigned()->default(0);

            $table->integer('warranty_period')->unsigned()->default(0);  // days

            $table->tinyInteger('stock_control')->default(1);
            $table->tinyInteger('publish_to_web')->default(0);

            $table->integer('tax_id')->unsigned()->nullable(false);
            $table->integer('main_supplier_id')->unsigned()->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
