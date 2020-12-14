<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_fiscal', 128)->nullable(false);                     // Company
            $table->string('name_commercial', 64)->nullable();
            $table->string('type', 32)->nullable(false)->default('partner');
            
            $table->string('identification', 64)->nullable();

            $table->string('email')->unique();
            $table->string('phone', 32)->nullable();
            $table->string('phone_mobile', 32)->nullable();

            $table->string('address')->nullable();
/*
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
*/
//            $table->string('industry');//$table->string('company_type')->nullable();

            $table->string('website', 128)->nullable();

            $table->tinyInteger('blocked')->default(0);
            $table->tinyInteger('active')->default(1);

            $table->text('notes')->nullable();
            
            $table->integer('user_created_by_id')->unsigned();
            $table->integer('user_assigned_to_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned();         // When converted to Customer
//            $table->integer('industry_id')->unsigned();

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
        Schema::dropIfExists('parties');
    }
}
