<?php

use Illuminate\Database\Seeder;
use App\Country as Country;
use App\State as State;
  
class CountriesTableSeeder extends Seeder {
  
    public function run() {
        Country::truncate();
        State::truncate();


        // ******************************************************************* //

  
        $country = Country::create( [
            'name'      => 'España' ,
            'iso_code'     => 'ES' ,
            'contains_states'  => '1' ,
            'active'    => '1' ,
                    'created_at'  => \Carbon\Carbon::createFromDate(2015,04,01)->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );

        // ------------------------------------------------------------------- //
  
        $states_ES=[
['A Coruña', 'ES-C'],
['Alacant', 'ES-A'],
['Álava', 'ES-VI'],
['Albacete', 'ES-AB'],
['Almería', 'ES-AL'],
['Asturias', 'ES-O'],
['Ávila', 'ES-AV'],
['Badajoz', 'ES-BA'],
['Balears', 'ES-PM'],
['Barcelona', 'ES-B'],
['Bizkaia', 'ES-BI'],
['Burgos', 'ES-BU'],
['Cáceres', 'ES-CC'],
['Cádiz', 'ES-CA'],
['Cantabria', 'ES-S'],
['Castelló', 'ES-CS'],
['Ciudad Real', 'ES-CR'],
['Córdoba', 'ES-CO'],
['Cuenca', 'ES-CU'],
['Gipuzkoa', 'ES-SS'],
['Girona', 'ES-GI'],
['Granada', 'ES-GR'],
['Guadalajara', 'ES-GU'],
['Huelva', 'ES-H'],
['Huesca', 'ES-HU'],
['Jaén', 'ES-J'],
['La Rioja', 'ES-LO'],
['Las Palmas', 'ES-GC'],
['León', 'ES-LE'],
['Lleida', 'ES-L'],
['Lugo', 'ES-LU'],
['Madrid', 'ES-M'],
['Málaga', 'ES-MA'],
['Murcia', 'ES-MU'],
['Nafarroa', 'ES-NA'],
['Ourense', 'ES-OR'],
['Palencia', 'ES-P'],
['Pontevedra', 'ES-PO'],
['Salamanca', 'ES-SA'],
['Santa Cruz de Tenerife', 'ES-TF'],
['Segovia', 'ES-SG'],
['Sevilla', 'ES-SE'],
['Soria', 'ES-SO'],
['Tarragona', 'ES-T'],
['Teruel', 'ES-TE'],
['Toledo', 'ES-TO'],
['València', 'ES-V'],
['Valladolid', 'ES-VA'],
['Zamora', 'ES-ZA'],
['Zaragoza', 'ES-Z'],
['Ceuta', 'ES-CE'],
['Melilla', 'ES-ML'],
        ];


        foreach ($states_ES as $v){
                $state = State::create( [
                    'name'      => $v[0] ,
                    'iso_code'     => $v[1] ,
         //           'country_id'    => $country->id ,
                    'active'    => '1' ,
                            'created_at'  => \Carbon\Carbon::createFromDate(2015,04,01)->toDateTimeString(),
                            'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
                ] );

                $country->states()->save($state);
        }


        // ******************************************************************* //

  
        $country_US = Country::create( [
            'name'      => 'Estados Unidos' ,
            'iso_code'     => 'US' ,
            'contains_states'  => '1' ,
            'active'    => '1' ,
                    'created_at'  => \Carbon\Carbon::createFromDate(2015,04,01)->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );

        // ------------------------------------------------------------------- //
  
        $states_US=[
['Alabama', 'AL'],
['Alaska', 'AK'],
['Arizona', 'AZ'],
['Arkansas', 'AR'],
['California', 'CA'],
['Colorado', 'CO'],
['Connecticut', 'CT'],
['Delaware', 'DE'],
['District of Columbia', 'DC'],
['Florida', 'FL'],
['Georgia', 'GA'],
['Hawaii', 'HI'],
['Idaho', 'ID'],
['Illinois', 'IL'],
['Indiana', 'IN'],
['Iowa', 'IA'],
['Kansas', 'KS'],
['Kentucky', 'KY'],
['Louisiana', 'LA'],
['Maine', 'ME'],
['Maryland', 'MD'],
['Massachusetts', 'MA'],
['Michigan', 'MI'],
['Minnesota', 'MN'],
['Mississippi', 'MS'],
['Missouri', 'MO'],
['Montana', 'MT'],
['Nebraska', 'NE'],
['Nevada', 'NV'],
['New Hampshire', 'NH'],
['New Jersey', 'NJ'],
['New Mexico', 'NM'],
['New York', 'NY'],
['North Carolina', 'NC'],
['North Dakota', 'ND'],
['Ohio', 'OH'],
['Oklahoma', 'OK'],
['Oregon', 'OR'],
['Pennsylvania', 'PA'],
['Puerto Rico', 'PR'],
['Rhode Island', 'RI'],
['South Carolina', 'SC'],
['South Dakota', 'SD'],
['Tennessee', 'TN'],
['Texas', 'TX'],
['US Virgin Islands', 'VI'],
['Utah', 'UT'],
['Vermont', 'VT'],
['Virginia', 'VA'],
['Washington', 'WA'],
['West Virginia', 'WV'],
['Wisconsin', 'WI'],
['Wyoming', 'WY'],
        ];


        foreach ($states_US as $v){
                $state = State::create( [
                    'name'      => $v[0] ,
                    'iso_code'     => $v[1] ,
         //           'country_id'    => $country_US->id ,
                    'active'    => '1' ,
                            'created_at'  => \Carbon\Carbon::createFromDate(2015,04,01)->toDateTimeString(),
                            'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
                ] );

                $country_US->states()->save($state);
        }


        // ******************************************************************* //


    } // run ENDS
}
