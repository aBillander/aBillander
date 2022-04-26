<?php

use Illuminate\Database\Seeder;

use App\Sequence;
use App\Configuration;
  
class esSequencesTableSeeder extends Seeder {
  
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
        Configuration::updateValue('ABCC_QUOTATIONS_SEQUENCE', $t->id);
  
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
        Configuration::updateValue('ABCC_ORDERS_SEQUENCE', $t->id);
  
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
  
        $t = Sequence::create( [
            'name'    => 'Transferencias de Almacén', 
            'model_name'    => 'WarehouseShippingSlip', 
            'prefix'    => 'TRS', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE', $t->id);
  
        $t = Sequence::create( [
            'name'    => 'Remesas Clientes', 
            'model_name'    => 'SepaDirectDebit', 
            'prefix'    => 'RE', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

  
        $t = Sequence::create( [
            'name'    => 'Pedidos a Proveedores', 
            'model_name'    => 'SupplierOrder', 
            'prefix'    => 'PEP', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_SUPPLIER_ORDER_SEQUENCE', $t->id);
  
        $t = Sequence::create( [
            'name'    => 'Albaranes de Proveedores', 
            'model_name'    => 'SupplierShippingSlip', 
            'prefix'    => 'ALP', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE', $t->id);

        $t = Sequence::create( [
            'name'    => 'Facturas de Proveedores', 
            'model_name'    => 'SupplierInvoice', 
            'prefix'    => 'FAP', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_SUPPLIER_INVOICE_SEQUENCE', $t->id);

    }
}
