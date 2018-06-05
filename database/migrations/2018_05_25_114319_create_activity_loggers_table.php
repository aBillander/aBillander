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
            $table->string('log_name')->index()->default('default');
            $table->text('description');

            $table->smallInteger('level')->default(0);
            $table->string('level_name', 32)->default('');       // $type='INFO', 'WARNING', 'ERROR'
            $table->text('message')->default('');
            $table->text('context')->nullable();

//            $table->morphs('loggable');
            $table->integer('loggable_id')->unsigned()->nullable();
            $table->string('loggable_type')->nullable();

            $table->integer('user_id')->nullable();

            $table->dateTime('date_added')->nullable(false)->useCurrent();
            $table->string('secs_added', 6)->nullable(false)->default('000000');

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
