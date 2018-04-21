<?php

use Illuminate\Database\Seeder;
use App\Configuration as Configuration;

class CompanyTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		\DB::table('addresses')->delete();
		
		$aconfigurations = array(
//			array(//	'id'              => '1',
					'alias'           => 'Main Address',
					'webshop_id'      => '',
					'model_name'      => 'Company',

					'name_commercial' => 'Wath, Ltd.',

					'address1'        => '123th Fake St.',
					'address2'        => 'duplicado',
					'postcode'        => '28001',
					'city'            => 'Alcafrán',
					'state'           => 'Madrid',
					'country'         => 'España',

					'firstname'       => '',
					'lastname'        => '',
					'email'           => 'hello@wath.com',

					'phone'           => '911234567',
					'phone_mobile'    => '618121200',
					'fax'             => '',

					'notes'           => '',
					'active'          => '1',

					'owner_id'        => '1',
					'state_id'        => '1',
					'country_id'        => '1',

					'created_at'  => \Carbon\Carbon::createFromDate(2015,03,31)->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
//					),

		);

		$id = \DB::table('addresses')->insertGetId($aconfigurations);


		// Uncomment the below to wipe the table clean before populating
		\DB::table('companies')->delete();
		
		$configurations = array(
			array(	'id'              => '1',
					'name_fiscal'     => 'Work at Home, Ltd.',
					'name_commercial' => 'Wath, Ltd.',
					'identification'  => '12345678E',

					'website'     => '',

					'notes'       => '',

					'address_id'  => $id,		// MAL primero crear company y luego address
												// rellenando owner_id con el id de company
					'currency_id' => '1',

					'created_at'  => \Carbon\Carbon::createFromDate(2015,03,31)->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
					),

		);

		// Uncomment the below to run the seeder
		\DB::table('companies')->insert($configurations);

		Configuration::updateValue('DEF_COMPANY', 1);
	}

}
