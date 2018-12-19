<?php

	/**
	 * Document Line Taxes.
	 *
	 * Common fields
	 */

			$table->increments('id');
			$table->string('name', 128)->nullable(false);

			$table->string('tax_rule_type', 32)->nullable(false);

			$table->decimal('taxable_base', 20, 6)->default(0.0);						// Base for tax calculations
			$table->decimal('percent', 8, 3)->default(0.0);								// Tax percent
			$table->decimal('amount', 20, 6)->default(0.0);                             // Tax may be fixed amount
//			$table->decimal('amount_type', 32)->nullable(false)->default('per_unit');	// <- Tax rule stuff
			// 'amount'     => Fixed amount.
			// 'per_unit'   => Proportional to line quantity.
			// 'per_weight' =>  Proportional to line weight.
			$table->decimal('total_line_tax', 20, 6)->default(0.0);

			$table->integer('position')->unsigned()->default(0);

//			$table->integer('document_line_id')->unsigned()->nullable(false);
			$table->integer('tax_id')->unsigned()->nullable(false);
			$table->integer('tax_rule_id')->unsigned()->nullable(false);

			$table->timestamps();
