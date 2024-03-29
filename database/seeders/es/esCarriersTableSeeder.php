<?php

namespace Database\Seeders\es;

use Illuminate\Database\Seeder;

use App\Models\Carrier;
use App\Models\Configuration;
  
class esCarriersTableSeeder extends Seeder {
  
    public function run() {
        Carrier::truncate();
  
        $carrier = Carrier::create( [
            'id'      => '1' ,
            'name'      => 'Reparto Propio' ,
            'alias'     => 'Propio' ,
            'active'    => '1' ,
        ] );
  
        $carrier = Carrier::create( [
            'id'      => '2' ,
            'name'      => 'Agencia de Transporte' ,
            'alias'     => 'Agencia' ,
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CARRIER', $carrier->id);
    }
}
