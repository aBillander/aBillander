<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 32)->nullable(false)->default('pending');

            $table->datetime('lead_date')->nullable();
            $table->datetime('lead_end_date')->nullable();

            $table->text('notes')->nullable();

            $table->integer('user_created_by_id')->unsigned();
            $table->integer('user_assigned_to_id')->unsigned()->nullable();
            $table->integer('party_id')->unsigned();
            $table->integer('contact_id')->unsigned()->nullable();

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
        Schema::dropIfExists('leads');
    }
}
