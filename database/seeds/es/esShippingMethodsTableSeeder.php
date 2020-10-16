<?php

use Illuminate\Database\Seeder;

use App\ShippingMethod;
use App\Configuration;
  
class esShippingMethodsTableSeeder extends Seeder {
  
    public function run() {
        ShippingMethod::truncate();
  
        $pmeth = ShippingMethod::create( [
            'name' => 'Recogida en tienda', 
            'alias' => 'Tienda',
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
            'name' => 'Reparto propio', 
            'alias' => 'Reparto',
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
            'name' => 'Envío por agencia', 
            'alias' => 'Envío',
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

        Configuration::updateValue('DEF_CUSTOMER_SHIPPING_METHOD', $pmeth->id);
    }
}
