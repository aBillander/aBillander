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
            $table->string('status', 32)->nullable(false)->default('regular');  // Values: 'regular', 'terminated'

            $table->integer('cash_register_id')->unsigned()->nullable();    // Assigned to this particaular cash register machine (pos). To Do: one Cashier user may be assigned to more than one registers.
            $table->integer('language_id')->unsigned()->nullable(false); 
            $table->integer('sales_rep_id')->unsigned()->nullable();        // To compute max_sales_discount_percent y sales_commission

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
