<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('configurations')->truncate();
		DB::table('currencies')->delete();
		
		$configurations = array(
			array(	'name'         => 'Euro', 
					'iso_code'     => 'EUR',
					'iso_code_num' => '978',
					'sign'         => '€',

					'signPlacement'      => '1',
					'thousandsSeparator' => '.',
					'decimalSeparator'   => ',',
					'decimalPlaces'      => '2',

					'blank'                    => '0',
					'conversion_rate' => '1.0',
					'active'                   => '1',

					'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
					),
			array(	'name'         => 'Dollar', 
					'iso_code'     => 'USD',
					'iso_code_num' => '840',
					'sign'         => '$',

					'signPlacement'      => '0',
					'thousandsSeparator' => ',',
					'decimalSeparator'   => '.',
					'decimalPlaces'      => '2',

					'blank'                    => '0',
					'conversion_rate' => '1.22',
					'active'                   => '1',

					'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
					),
			array(	'name'         => 'Pound Sterling', 
					'iso_code'     => 'GBP',
					'iso_code_num' => '826',
					'sign'         => '£',

					'signPlacement'      => '0',
					'thousandsSeparator' => ',',
					'decimalSeparator'   => '.',
					'decimalPlaces'      => '2',

					'blank'                    => '0',
					'conversion_rate' => '0.88',
					'active'                   => '1',

					'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
					),
			array(	'name'         => 'Yen', 
					'iso_code'     => 'JPY',
					'iso_code_num' => '392',
					'sign'         => '¥',			// Or: 円 

					'signPlacement'      => '0',
					'thousandsSeparator' => ',',
					'decimalSeparator'   => '.',
					'decimalPlaces'      => '0',

					'blank'                    => '0',
					'conversion_rate' => '130.0',
					'active'                   => '1',

					'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
					),

		);

		// Uncomment the below to run the seeder
		DB::table('currencies')->insert($configurations);

		$c = \App\Currency::where('iso_code', '=', 'EUR')->first();
		\App\Configuration::updateValue('DEF_CURRENCY', $c->id);

		$company = \App\Company::findOrFail( intval(\App\Configuration::get('DEF_COMPANY')) );
		$company->update( ['currency_id' => $c->id] );
	}

}
