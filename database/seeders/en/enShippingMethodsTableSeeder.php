<?php

namespace Database\Seeders\en;

use Illuminate\Database\Seeder;

use App\Models\ShippingMethod;
use App\Models\Configuration;
  
class enShippingMethodsTableSeeder extends Seeder {
  
    public function run() {
        ShippingMethod::truncate();
  
        $pmeth = ShippingMethod::create( [
            'name' => 'Store Pick Up', 
            'alias' => 'Store',
            'class_name' => 'App\\ShippingMethods\\StorePickUpShippingMethod',
            'active' => 1,
            'type' => 'basic',
            'transit_time' => null,
            'billing_type' => 'price',
            'free_shipping_from' => 0.0,
            'tax_id' => 1,
            'position' => 0,
            'carrier_id' => null,
        ] );
  
        $pmeth = ShippingMethod::create( [
            'name' => 'By Own Resources', 
            'alias' => 'Own',
            'class_name' => 'App\\ShippingMethods\\RegularDeliveryRouteShippingMethod',
            'active' => 1,
            'type' => 'basic',
            'transit_time' => null,
            'billing_type' => 'price',
            'free_shipping_from' => 0.0,
            'tax_id' => 1,
            'position' => 0,
            'carrier_id' => 1,
        ] );
  
        $pmeth = ShippingMethod::create( [
            'name' => 'By Transport Agency', 
            'alias' => 'Agency',
            'class_name' => 'App\\ShippingMethods\\TransportAgencyShippingMethod',
            'active' => 1,
            'type' => 'basic',
            'transit_time' => null,
            'billing_type' => 'price',
            'free_shipping_from' => 0.0,
            'tax_id' => 1,
            'position' => 0,
            'carrier_id' => 2,
        ] );

        Configuration::updateValue('DEF_SHIPPING_METHOD', $pmeth->id);
    }
}
