<?php

use Illuminate\Database\Seeder;

use App\MeasureUnit;
use App\Configuration;
  
class MeasureUnitsTableSeeder extends Seeder {
  
    public function run() {
        MeasureUnit::truncate();
  
        $munit = MeasureUnit::create( [
            'type' => 'Quantity', 
            'type_conversion_rate' => 1.0,
            'sign' => 'ud.', 
            'name' => 'Unidad(es)', 
            'decimalPlaces' => 0, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_MEASURE_UNIT_FOR_PRODUCTS', $munit->id);
        Configuration::updateValue('DEF_MEASURE_UNIT_FOR_BOMS'    , $munit->id);
        
        Configuration::updateValue('DEF_QUANTITY_DECIMALS'        , $munit->decimalPlaces);
    }
}
