<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLoggerLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('activity_logger_lines');

        Schema::create('activity_logger_lines', function (Blueprint $table) {
            $table->increments('id');

            $table->smallInteger('level')->default(0);
            $table->string('level_name', 32)->default('');       // $type='INFO', 'WARNING', 'ERROR'
            $table->text('message')->default('');
            $table->text('context')->nullable();

//            $table->morphs('loggable');
            $table->integer('loggable_id')->unsigned()->nullable();
            $table->string('loggable_type')->nullable();

            $table->dateTime('date_added')->nullable(false)->useCurrent();
            $table->string('secs_added', 6)->nullable(false)->default('000000');

            $table->integer('activity_logger_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('activity_logger_lines');
    }
}
