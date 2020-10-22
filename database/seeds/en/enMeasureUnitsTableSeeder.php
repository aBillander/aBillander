<?php

use Illuminate\Database\Seeder;

use App\MeasureUnit;
use App\Configuration;
  
class enMeasureUnitsTableSeeder extends Seeder {
  
    public function run() {
        MeasureUnit::truncate();
  
        $munit = MeasureUnit::create( [
            'type' => 'Quantity', 
            'type_conversion_rate' => 1.0,
            'sign' => 'u.', 
            'name' => 'Unit(s)', 
            'decimalPlaces' => 0, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_MEASURE_UNIT_FOR_PRODUCTS', $munit->id);
        Configuration::updateValue('DEF_MEASURE_UNIT_FOR_BOMS'    , $munit->id);
        
        Configuration::updateValue('DEF_QUANTITY_DECIMALS'        , $munit->decimalPlaces);


        Configuration::updateValue('DEF_DIMENSION_UNIT', 'cm');
        Configuration::updateValue('DEF_DISTANCE_UNIT',  'km');

        Configuration::updateValue('DEF_VOLUME_UNIT', 'cl');
        Configuration::updateValue('DEF_WEIGHT_UNIT', 'kg');
    }
}
