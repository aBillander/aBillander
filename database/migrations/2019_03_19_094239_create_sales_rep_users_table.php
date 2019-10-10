<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRepUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sales_rep_users');
        
        Schema::create('sales_rep_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 128)->unique();
            $table->string('password');

//            $table->string('home_page', 128)->nullable();       // Redirect after login to route_home
            $table->string('firstname', 32)->nullable();
            $table->string('lastname', 32)->nullable();
//            $table->string('timezone', 32)->nullable();

            $table->rememberToken();

            $table->tinyInteger('allow_abcc_access')->default(0);              // Sales Representative can give Customer access to Customer Center
            $table->tinyInteger('active')->default(1);

            $table->integer('language_id')->unsigned()->nullable(false); 
            $table->integer('sales_rep_id')->unsigned()->nullable(false); 

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
        Schema::dropIfExists('sales_rep_users');
    }
}
