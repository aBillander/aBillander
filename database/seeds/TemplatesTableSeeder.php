<?php

use Illuminate\Database\Seeder;

use App\Template;
use App\Configuration;
  
class TemplatesTableSeeder extends Seeder {
  
    public function run() {
        Template::truncate();
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Pedidos Customer Center', 
            'model_name' => 'CustomerOrderPdf', 
            'folder' => 'templates::abcc', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

//        Configuration::updateValue('DEF_CUSTOMER_ORDER_TEMPLATE', $t->id);
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Albaranes', 
            'model_name' => 'CustomerShippingSlipPdf', 
            'folder' => 'customer_shipping_slips', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

        Configuration::updateValue('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE', $t->id);
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Facturas', 
            'model_name' => 'CustomerInvoicePdf', 
            'folder' => 'templates::', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

        Configuration::updateValue('DEF_CUSTOMER_INVOICE_TEMPLATE'      , $t->id);
        
    }
}
