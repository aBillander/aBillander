<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSequencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sequences', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 128);
			$table->string('model_name', 64)->nullable(false);    	// This sequence applies to this Model
//			$table->string('sequenceable_type');					// Full qualified clss name, please ;)
			
			$table->string('prefix', 8)->index();
			$table->tinyInteger('length')->unsigned();				// Document id will be left padded with 0'es to this length
			$table->string('separator', 3);							// To show between document_prefix and document_id
			
			$table->integer('next_id')->unsigned();
			$table->timestamp('last_date_used')->nullable()->default(NULL);
			
			$table->tinyInteger('active')->default(1);

			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sequences');
	}

}
