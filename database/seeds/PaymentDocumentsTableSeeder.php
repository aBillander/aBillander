<?php

use Illuminate\Database\Seeder;
use App\PaymentDocument;
  
class PaymentDocumentsTableSeeder extends Seeder {
  
    public function run() {
        PaymentDocument::truncate();
  
        PaymentDocument::create( [
            'name'      => 'No asignado' ,
					'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );
    }
}
