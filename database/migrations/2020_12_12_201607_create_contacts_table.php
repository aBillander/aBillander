<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname', 32)->nullable(false);            // Contact information
            $table->string('lastname', 32)->nullable();
            $table->string('job_title')->nullable();
            $table->string('type', 32)->nullable(false)->default('Employee');   // Use case example: manager, secretary, etc.
            $table->string('email')->nullable();

            $table->string('phone', 32)->nullable();
            $table->string('phone_mobile', 32)->nullable();

            $table->string('address')->nullable();
/*
            $table->string('address2')->nullable()->default(null)->after('address1');
            $table->string('state')->nullable()->default(null)->after('address2');
            $table->string('country')->nullable()->default(null)->after('state');
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
*/

            $table->string('website', 128)->nullable();

            $table->tinyInteger('is_primary')->default(0);
            $table->tinyInteger('blocked')->default(0);
            $table->tinyInteger('active')->default(1);

            $table->text('notes')->nullable();

            $table->integer('user_created_by_id')->unsigned();
            $table->integer('party_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('address_id')->unsigned()->nullable();
            
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
        Schema::dropIfExists('contacts');
    }
}
