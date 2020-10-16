<?php

use Illuminate\Database\Seeder;
use App\Carrier;
  
class esCarriersTableSeeder extends Seeder {
  
    public function run() {
        Carrier::truncate();
  
        Carrier::create( [
            'id'      => '1' ,
            'name'      => 'Reparto Propio' ,
            'alias'     => 'Propio' ,
            'active'    => '1' ,
        ] );
  
        Carrier::create( [
            'id'      => '2' ,
            'name'      => 'Agencia de Transporte' ,
            'alias'     => 'Agencia' ,
            'active'    => '1' ,
        ] );
    }
}
