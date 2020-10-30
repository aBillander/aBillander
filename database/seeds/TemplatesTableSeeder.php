<?php

use Illuminate\Database\Seeder;

use App\Template;
use App\Configuration;
  
class TemplatesTableSeeder extends Seeder {
  
    public function run() {
        Template::truncate();
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Presupuestos', 
            'model_name' => 'CustomerQuotationPdf', 
            'folder' => 'templates::', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

        Configuration::updateValue('DEF_CUSTOMER_QUOTATION_TEMPLATE', $t->id);
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Pedidos Customer Center', 
            'model_name' => 'CustomerOrderPdf', 
            'folder' => 'templates::abcc', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Pedidos', 
            'model_name' => 'CustomerOrderPdf', 
            'folder' => 'templates::', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

        Configuration::updateValue('DEF_CUSTOMER_ORDER_TEMPLATE', $t->id);
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Plantilla Albaranes', 
            'model_name' => 'CustomerShippingSlipPdf', 
            'folder' => 'templates::', 
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
  
        $t = Template::create( [
//            'id' => 1,
            'name' => 'Albarán entre Almacenes', 
            'model_name' => 'WarehouseShippingSlipPdf', 
            'folder' => 'templates::', 
            'file_name' => 'default', 
            'paper' => 'A4', 
            'orientation' => 'portrait',
        ] );

        Configuration::updateValue('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE', $t->id);
        
    }
}
