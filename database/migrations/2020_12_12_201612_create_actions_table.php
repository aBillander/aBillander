<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('lead_lines');

        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->text('description')->nullable();
            $table->string('status', 32)->nullable(false)->default('pending');
            $table->string('priority', 32)->nullable(false)->default('low');    // low, medium, high
            
            $table->datetime('start_date')->nullable();     // Maybe input interface should have two fields: one for date, other for time.
            $table->datetime('due_date')->nullable();
            $table->datetime('finish_date')->nullable();

            $table->text('results')->nullable();

            $table->integer('position')->unsigned()->default(0);

            $table->integer('user_created_by_id')->unsigned()->nullable();
            $table->integer('user_assigned_to_id')->unsigned()->nullable();
            $table->integer('action_type_id')->unsigned()->nullable(false);   // Phone call, online meeting, face-to-face meeting, etc.

            $table->integer('sales_rep_id')->unsigned()->nullable();
            $table->integer('contact_id')->unsigned()->nullable();
            // An Action maybe attached to a Lead or stright to a Customer:
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('lead_id')->unsigned()->nullable();

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
