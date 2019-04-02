<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//  php artisan make:migration add_fields_to_products_table --table=products

// php artisan migrate --path=database/migrations/custom --pretend

// \Artisan::call('migrate', ['--path' => 'database/migrations/custom', '--force'  => true, '--pretend'  => true]);

              
    
    /**  Billable model
     * Get the fillable attributes for the model.
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     *
     * @return array
     */

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
            //
            $table->string('name_en', 128)->nullable(true)->after('active');

            $table->decimal('price_usd', 20, 6)->default(0.0)->after('name_en');
//            $table->decimal('price_usd_tax_inc', 20, 6)->default(0.0);

            $table->decimal('price_usd_conversion_rate', 20, 6)->default(1.0)->after('price_usd');
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
