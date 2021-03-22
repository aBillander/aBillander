<?php

use Illuminate\Database\Seeder;

use App\Configuration;

// use Illuminate\Support\Facades\DB;
// use App\Models\Contact;

class esConfigurationsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('configurations')->truncate();
		// DB::table('configurations')->delete();

		Configuration::loadConfiguration();

$confs = [

['ABCC_OUT_OF_STOCK_TEXT', 'Actualmente no tenemos stock de este Producto, pero puede hacer su Pedido y se lo serviremos lo antes posible.'],
['CUSTOMER_INVOICE_BANNER', 'Haga su pedido en www.mitienda.es'],	// 'Haga su pedido en www.mitienda.es' or &nbsp; (otherwise header pagenumber not well located)
['CUSTOMER_INVOICE_CAPTION', 'Sociedad inscrita en el Registro Mercantil de Ciudad.'],
['CUSTOMER_INVOICE_TAX_LABEL', 'IVA'],


// ['TIMEZONE', 'Europe/Madrid'],

];

        foreach ($confs as $v){

                Configuration::updateValue( $v[0] , $v[1] );
        }

	}

}
