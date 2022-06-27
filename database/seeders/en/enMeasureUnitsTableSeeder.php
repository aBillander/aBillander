<?php

namespace Database\Seeders\en;

use Illuminate\Database\Seeder;

use App\Models\MeasureUnit;
use App\Models\Configuration;
  
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

  
        $munit = MeasureUnit::create( [
            'type' => 'Length', 
            'type_conversion_rate' => 1.0,
            'sign' => 'cm', 
            'name' => 'centimeter', 
            'decimalPlaces' => 2, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_LENGTH_UNIT', $munit->id);

  
        $munit = MeasureUnit::create( [
            'type' => 'Dry Volume', 
            'type_conversion_rate' => 1.0,
            'sign' => 'm3', 
            'name' => 'cubic meter', 
            'decimalPlaces' => 2, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_VOLUME_UNIT', $munit->id);
        Configuration::updateValue('DEF_VOLUME_UNIT_CONVERSION_RATE', 1000000); // 1m3 = 1000000cm3        

  
        $munit = MeasureUnit::create( [
            'type' => 'Mass', 
            'type_conversion_rate' => 1.0,
            'sign' => 'kg', 
            'name' => 'kilogram', 
            'decimalPlaces' => 2, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_WEIGHT_UNIT', $munit->id);


        // Other
        Configuration::updateValue('DEF_DIMENSION_UNIT', 'cm');
        Configuration::updateValue('DEF_DISTANCE_UNIT',  'km');
    }
}
