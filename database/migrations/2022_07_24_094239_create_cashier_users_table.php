<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashierUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cashier_users');
        
        Schema::create('cashier_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 128)->unique();
            $table->string('password');

//            $table->string('home_page', 128)->nullable();       // Redirect after login to route_home
            $table->string('firstname', 32)->nullable();
            $table->string('lastname', 32)->nullable();
//            $table->string('timezone', 32)->nullable();

            $table->rememberToken();

            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('is_principal')->default(0);

            $table->tinyInteger('enable_quotations')->default(-1);   // Use default Customer Center setting
            $table->tinyInteger('enable_min_order')->default(-1);   // Use default Customer Center setting
            $table->tinyInteger('use_default_min_order_value')->default(1);   // Use default Customer Center setting
            $table->decimal('min_order_value', 20, 6)->default(0.0);   // Use default Customer Center setting
            $table->tinyInteger('display_prices_tax_inc')->default(0);

            $table->integer('language_id')->unsigned()->nullable(false); 
            $table->integer('customer_id')->unsigned()->nullable(false); 
            $table->integer('address_id')->unsigned()->nullable(); 

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashier_users');
    }
}
