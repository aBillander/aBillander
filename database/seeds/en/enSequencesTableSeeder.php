<?php

use Illuminate\Database\Seeder;

use App\Sequence;
use App\Configuration;
  
class enSequencesTableSeeder extends Seeder {
  
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
            'name'    => 'Customer Quotations', 
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
            'name'    => 'Customer Orders', 
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
            'name'    => 'Customer Shipping Slips', 
            'model_name'    => 'CustomerShippingSlip', 
            'prefix'    => 'ALB', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE', $t->id);

        $t = Sequence::create( [
            'name'    => 'Customer Invoices', 
            'model_name'    => 'CustomerInvoice', 
            'prefix'    => 'NAC', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_INVOICE_SEQUENCE', $t->id);
  
        $t = Sequence::create( [
            'name'    => 'Warehouse Transfer Shipping Slips', 
            'model_name'    => 'WarehouseShippingSlip', 
            'prefix'    => 'TRS', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

        Configuration::updateValue('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE', $t->id);

  
        $t = Sequence::create( [
            'name'    => 'Supplier Shipping Slips', 
            'model_name'    => 'SupplierShippingSlip', 
            'prefix'    => 'ALP', 
            'length'    => '4', 
            'separator'    => '-', 
            'next_id'     => '1',
            'active'    => '1' ,
        ] );

    }
}
