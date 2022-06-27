<?php

namespace Database\Seeders\es;

use Illuminate\Database\Seeder;

use App\Models\MeasureUnit;
use App\Models\Configuration;
  
class esMeasureUnitsTableSeeder extends Seeder {
  
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

  
        $munit = MeasureUnit::create( [
            'type' => 'Length', 
            'type_conversion_rate' => 1.0,
            'sign' => 'cm', 
            'name' => 'centímetro', 
            'decimalPlaces' => 2, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_LENGTH_UNIT', $munit->id);

  
        $munit = MeasureUnit::create( [
            'type' => 'Dry Volume', 
            'type_conversion_rate' => 1.0,
            'sign' => 'm3', 
            'name' => 'metro cúbico', 
            'decimalPlaces' => 2, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_VOLUME_UNIT', $munit->id);
        Configuration::updateValue('DEF_VOLUME_UNIT_CONVERSION_RATE', 1000000); // 1m3 = 1000000cm3        

  
        $munit = MeasureUnit::create( [
            'type' => 'Mass', 
            'type_conversion_rate' => 1.0,
            'sign' => 'kg', 
            'name' => 'kilogramo', 
            'decimalPlaces' => 2, 
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_WEIGHT_UNIT', $munit->id);


        // Other
        Configuration::updateValue('DEF_DIMENSION_UNIT', 'cm');
        Configuration::updateValue('DEF_DISTANCE_UNIT',  'km');
    }
}
