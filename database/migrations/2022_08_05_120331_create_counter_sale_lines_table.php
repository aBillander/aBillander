<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterSaleLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('counter_sale_lines');

        Schema::create('counter_sale_lines', function (Blueprint $table) {
            if (file_exists(__DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php')) {
                include __DIR__.'/schnitzel/_schnitzel_create_document_lines_table.php';
            }

            // Parent Document
            $table->integer('counter_sale_id')->unsigned()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counter_sale_lines');
    }
}
