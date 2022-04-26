<?php

use Illuminate\Database\Seeder;

use App\Carrier;
use App\Configuration;
  
class enCarriersTableSeeder extends Seeder {
  
    public function run() {
        Carrier::truncate();
  
        $carrier = Carrier::create( [
            'id'      => '1' ,
            'name'      => 'Own Resources' ,
            'alias'     => 'Own' ,
            'active'    => '1' ,
        ] );
  
        $carrier = Carrier::create( [
            'id'      => '2' ,
            'name'      => 'Transport Agency' ,
            'alias'     => 'Agency' ,
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CARRIER', $carrier->id);
    }
}
