<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('model_attachments');

        Schema::create('model_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128)->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->unsigned()->default(0);

            $table->string('filename', 128)->nullable(false);

            $table->integer('attachmentable_id')->unsigned()->nullable(false);
            $table->string('attachmentable_type');

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
        Schema::dropIfExists('model_attachments');
    }
}
