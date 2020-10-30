<?php

use Illuminate\Database\Seeder;

use App\PaymentMethod;
use App\Configuration;
  
class esPaymentMethodsTableSeeder extends Seeder {
  
    public function run() {
        PaymentMethod::truncate();
  
        $pmeth = PaymentMethod::create( [
            'name' => 'Contado / Efectivo', 
            'deadlines' => [['slot' => 0, 'percentage' => 100]],
            'payment_is_cash' => 1,
            'auto_direct_debit' => 0,
            'active' => 1,
        ] );

        Configuration::updateValue('DEF_CUSTOMER_PAYMENT_METHOD', $pmeth->id);
    }
}
