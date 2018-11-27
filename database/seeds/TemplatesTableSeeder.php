<?php

use Illuminate\Database\Seeder;

use App\Template;
use App\Configuration;
  
class TemplatesTableSeeder extends Seeder {
  
    public function run() {
        Template::truncate();
  
        $t = Template::create( [
            'id' => 1,
            'name' => 'Plantilla Pedidos Customer Center', 
            'model_name' => 'CustomerOrderPdf', 
            'folder' => 'templates::abcc', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

        Configuration::updateValue('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE', $t->id);
        Configuration::updateValue('DEF_CUSTOMER_INVOICE_TEMPLATE'      , $t->id);
        
    }
}
