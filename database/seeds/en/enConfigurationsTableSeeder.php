<?php

use Illuminate\Database\Seeder;

use App\Configuration;

// use Illuminate\Support\Facades\DB;
// use App\Models\Contact;

class enConfigurationsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('configurations')->truncate();
		// DB::table('configurations')->delete();

		Configuration::loadConfiguration();

$confs = [

['ABCC_OUT_OF_STOCK_TEXT', 'Currently we do not have stock of this Product, but you can place your Order and we will serve it as soon as possible.'],	// 'Actualmente no tenemos stock de este Producto, pero puede hacer su Pedido y se los serviremos lo antes posible.'
['CUSTOMER_INVOICE_BANNER', 'Place your Order at www.mystore.com'],	// 'Haga su pedido en www.mitienda.es' or &nbsp; (otherwise header pagenumber not well located)
['CUSTOMER_INVOICE_CAPTION', 'Registered with the Commercial Registry of City.'],	// 'Sociedad inscrita en el Registro Mercantil de Ciudad.'
['CUSTOMER_INVOICE_TAX_LABEL', 'VAT'],


// ['TIMEZONE', 'Europe/Madrid'],

];

        foreach ($confs as $v){

                Configuration::updateValue( $v[0] , $v[1] );
        }

	}

}
