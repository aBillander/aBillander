<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductMeasureUnitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_measure_units', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->unsigned()->nullable(false);

			$table->integer('measure_unit_id')->unsigned()->nullable(false);
			$table->integer('stock_measure_unit_id')->unsigned()->nullable(false);	// As seen on products table

			$table->decimal('conversion_rate', 20, 6)->default(1.0);	// Conversion rates are calculated from one unit of your main measura unit. For example, if the main unit is "bottle" and your chosen unit is "pack-of-sixs, type "6" (since a pack of six bottles will contain six bottles)
			
			$table->tinyInteger('active')->default(1);

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
		Schema::dropIfExists('product_measure_units');
	}

}

/* 

https://help.sap.com/doc/9f3cbf53d25ab64ce10000000a174cb4/1610%20001/en-US/2283c4530b29b44ce10000000a174cb4.html

Units of Measure

 

In SAP Materials Management (MM), you can define several different units of measure (stockkeeping units) for each material, all of which are taken into consideration in Warehouse Management (WM).

The base unit of measure is the basis for inventory management and evaluation. Alternative units of measure, such as the order unit, issue unit or WM unit of measure, are defined to identify packages or containers for smaller units of measure such as cartons, boxes, bottles, barrels or pallets.

Units of Measure Used in WM

Base Unit of Measure

Quantities of warehouse materials (quants) are counted using the base unit of measure (UoM). Quantities in alternative units of measure are always converted to the base unit of measure for calculation purposes.

Stockkeeping Unit

The stockkeeping unit is synonymous with the base unit of measure. In the SAP System, the term "base unit of measure" is more commonly used.

WM Unit of Measure

The WM unit of measure (WM UoM) is an alternative unit which can be defined in the Warehouse Management view of the material master record.

Unit of Issue

The unit of issue (UoI) is a unit of measure generally used in Inventory Management for processing goods receipts and goods issues.

Other Alternative Units of Measure

Alternative units of measure can also be defined to identify packages or lager containers for smaller units of measure such as cartons, boxes, bottles, barrels, pallets (storage unit types) and so on.

How Can I Use the Various Units to Best Effect?

The use of several different units of measure in the SAP system is useful, for example, for accounting, storage and packaging purposes. For example, if a crate contains several thousand pieces of a particular material, it is more expedient to purchase, package and sell this material by the crate or box rather than by the piece. Additionally, it is important to use units of measure that cause no field overflows to occur when the quantities are increased.

Alternative Units of Measure in the Material Master

Additionally, in the WM view of the material master record, you can define a loading quantity for each pallet type or storage unit type (SUT). You can enter this quantity using any unit of measure that has been defined in the system.

*/
