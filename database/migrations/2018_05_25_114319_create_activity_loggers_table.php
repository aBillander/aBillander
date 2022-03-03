<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('activity_loggers');

        Schema::create('activity_loggers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128)->nullable(false)->default('default');
            $table->string('signature', 32)->index()->nullable(false);           // md5(description)
            $table->text('description');        // Something like : Controller::class+' :: '+controller::Method+' :: '+User->id
            $table->text('back_to')->nullable();

            $table->integer('user_id')->nullable();

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
        Schema::dropIfExists('activity_loggers');
    }
}
