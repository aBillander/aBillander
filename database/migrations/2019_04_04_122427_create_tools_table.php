<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// php artisan make:model Tool -c -m
// php artisan make:model Tool -a

// php artisan migrate --path=database/migrations/custom --pretend

class CreateToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tools');

        Schema::create('tools', function (Blueprint $table) {
            $table->increments('id');

            
            $table->string('tool_type', 32)->nullable();
            $table->string('name', 128)->nullable(false);
            $table->string('reference', 32)->nullable();
            $table->string('barcode', 180)->nullable();
            $table->text('description')->nullable();

            $table->string('location', 64)->nullable();

            // Manufacturer

            // Image

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
        Schema::dropIfExists('tools');
    }
}
