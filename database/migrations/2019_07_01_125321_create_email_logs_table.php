<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('email_logs');

        Schema::create('email_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('to')->nullable()->default(null);
            $table->string('subject');
            $table->longText('body');

            $table->string('from')->nullable()->default(null);
            $table->string('cc')->nullable()->default(null);
            $table->string('bcc')->nullable()->default(null);

            $table->text('headers')->nullable()->default(null);
            $table->longText('attachments')->nullable()->default(null);

            $table->integer('userable_id')->nullable();
            $table->string('userable_type')->nullable();

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
        Schema::dropIfExists('email_logs');
    }

    // https://github.com/stojankukrika/laravel-database-log-emails/
    // https://laracasts.com/discuss/channels/laravel/how-to-monitor-and-log-all-email-that-is-sent-out?page=1
    // https://www.sitepoint.com/mail-logging-in-laravel-5-3-extending-the-mail-driver/
}
