<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 32)->nullable(false)->default('pending');
            
            $table->datetime('start_date')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('finish_date')->nullable();

            $table->text('results')->nullable();

            $table->integer('position')->unsigned()->default(0);

            $table->integer('user_created_by_id')->unsigned();
            $table->integer('user_assigned_to_id')->unsigned()->nullable();
            $table->integer('lead_id')->unsigned();

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
        Schema::dropIfExists('lead_lines');
    }
}
