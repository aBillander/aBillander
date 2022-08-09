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
        Schema::create('cash_register_sessions', function (Blueprint $table) {
            $table->id();

            $table->decimal('starting_expected_cash', 20, 6)->default(0.0);
            $table->decimal('starting_cash', 20, 6)->default(0.0);
            $table->decimal('starting_added_cash', 20, 6)->default(0.0);
            $table->decimal('starting_total_cash', 20, 6)->default(0.0);

            $table->decimal('expected_cash', 20, 6)->default(0.0);
            $table->decimal('closing_cash', 20, 6)->default(0.0);
            
            $table->decimal('taken_cash', 20, 6)->default(0.0);
            $table->decimal('closing_total_cash', 20, 6)->default(0.0);

            // Columns to store JSON
            $table->text('starting_total_cash_denominations')->nullable();    // Or JSON type
            $table->text('closing_total_cash_denominations')->nullable();     // Or JSON type

            $table->timestamp('open_date')->nullable(false);    // open_date must be not null; otherwise session is "not" started
            $table->timestamp('close_date')->nullable();        // When a Session is closed, it cannot be modified.

            $table->text('starting_notes')->nullable();
            $table->text('closing_notes')->nullable();

            $table->integer('cashier_user_id')->unsigned()->nullable(false);
            $table->integer('cash_register_id')->unsigned()->nullable(false);

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
        Schema::dropIfExists('cash_register_sessions');
    }
};
