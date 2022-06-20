<?php

namespace Database\Seeders\en;

use Illuminate\Database\Seeder;

use App\Models\PaymentType;
use App\Models\Configuration;
  
class enPaymentTypesTableSeeder extends Seeder {
  
    public function run() {
        PaymentType::truncate();
  
        $ptype = PaymentType::create( [
            'alias' => 'Cash', 
            'name' => 'Cash', 
            'active' => 1, 
            'accounting_code' => '',
        ] );
  
        $ptype = PaymentType::create( [
            'alias' => 'Remitance', 
            'name' => 'Remitance', 
            'active' => 1, 
            'accounting_code' => '',
        ] );

        Configuration::updateValue('DEF_SEPA_PAYMENT_TYPE', $ptype->id);

  
        $ptype = PaymentType::create( [
            'alias' => 'Cheque', 
            'name' => 'Cheque', 
            'active' => 1, 
            'accounting_code' => '',
        ] );

        Configuration::updateValue('DEF_CHEQUE_PAYMENT_TYPE', $ptype->id);
  
        $ptype = PaymentType::create( [
            'alias' => 'Bank Transfer', 
            'name' => 'Transfer', 
            'active' => 1, 
            'accounting_code' => '',
        ] );

    }
}

/*

INSERT INTO `payment_types` 
(`id`, `alias`, `name`, `active`, `accounting_code`, `created_at`, `updated_at`, `deleted_at`) VALUES 
(3, 'Efectivo', 'Efectivo', 1, '100', '2019-10-25 14:12:09', '2019-10-25 14:12:09', NULL), 
(4, 'Remesa', 'Remesa', 1, '101', '2019-10-25 14:12:30', '2019-10-25 14:12:30', NULL), 
(5, 'Cheque', 'Cheque', 1, '102', '2019-10-25 14:12:47', '2019-10-25 14:12:47', NULL), 
(6, 'Transferencia', 'Transferencia', 1, '103', '2019-10-25 14:17:31', '2019-10-25 14:17:31', NULL) 

*/
