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

            $table->decimal('starting_cash', 20, 6)->nullable();
            $table->decimal('expected_cash', 20, 6)->nullable();
            $table->decimal('closing_cash', 20, 6)->nullable();
            
            $table->decimal('taken_cash', 20, 6)->nullable();

            // Columns to store JSON
            $table->text('starting_cash_denominations')->nullable();    // Or JSON type
            $table->text('closing_cash_denominations')->nullable();     // Or JSON type

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
