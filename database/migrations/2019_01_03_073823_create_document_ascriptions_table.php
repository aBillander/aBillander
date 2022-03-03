<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentAscriptionsTable extends Migration {

/*
	Order ==> Shipping Slip ==> Invoice

	  n ----------> 1

	                n ------------> 1

	  n --------------------------> 1


	  Model for this table: DocumentAscription

	  Imagine you create an Invoice (rightable) after 2 Shipping Slips (leftable).
	  Two Polymorphic relations are needed:

	  1.- rightdocument : invoice that owns this shipping slip. Always 0 or 1 document.

	  2.- leftdocuments : shipping slips that made up an invoice. 0 (manual document), 1, or n documents.


*/
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('document_ascriptions');

		Schema::create('document_ascriptions', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('leftable_id');
			$table->string( 'leftable_type');
			
			$table->integer('rightable_id');
			$table->string( 'rightable_type');

			$table->string('type', 32)->nullable(false)->default('traceability');
			
			$table->timestamps();
		});
	}
// a·scrib·a·ble , ascribe

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('document_ascriptions');
	}

}
