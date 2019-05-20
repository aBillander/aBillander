<?php

use Illuminate\Database\Seeder;

use App\Sequence;
use App\Configuration;
  
class SequencesTableSeeder extends Seeder {
  
    public function run() {
        Sequence::truncate();
/*  
        Sequence::create( [
            'name'    => 'Recuentos de Stock', 
            'model_name'    => 'StockCount', 
            'prefix'    => 'REC', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );
 */ 
  
        $t = Sequence::create( [
            'name'    => 'Presupuestos de Clientes', 
            'model_name'    => 'CustomerQuotation', 
            'prefix'    => 'PRE', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_QUOTATION_SEQUENCE', $t->id);
  
        $t = Sequence::create( [
            'name'    => 'Pedidos de Clientes', 
            'model_name'    => 'CustomerOrder', 
            'prefix'    => 'POT', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_ORDER_SEQUENCE', $t->id);
  
        $t = Sequence::create( [
            'name'    => 'Albaranes de Clientes', 
            'model_name'    => 'CustomerShippingSlip', 
            'prefix'    => 'ALB', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE', $t->id);

        $t = Sequence::create( [
            'name'    => 'Facturas Nacional', 
            'model_name'    => 'CustomerInvoice', 
            'prefix'    => 'NAC', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_INVOICE_SEQUENCE', $t->id);

    }
}
