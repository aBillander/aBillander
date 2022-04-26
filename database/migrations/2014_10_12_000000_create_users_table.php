<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 128)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('home_page', 128)->nullable();       // Redirect after login to route_home
            $table->string('theme', 128)->nullable();
            $table->string('firstname', 32)->nullable();
            $table->string('lastname', 32)->nullable();
//            $table->string('timezone', 32)->nullable();
            
            $table->rememberToken();

            $table->tinyInteger('is_admin')->default(0);        // Role here
            $table->tinyInteger('active')->default(1);

            $table->integer('language_id')->unsigned()->nullable(false); 

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
        Schema::dropIfExists('users');
    }
};
