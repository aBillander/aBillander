<?php

use Illuminate\Database\Seeder;

use App\Configuration;

// use Illuminate\Support\Facades\DB;
// use App\Models\Contact;

class ConfigurationsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('configurations')->truncate();
		DB::table('configurations')->delete();

$confs = [

['ABCC_HEADER_TITLE', '<span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> </span>'],
['ABCC_EMAIL', ''],
['ABCC_EMAIL_NAME', 'Customer Center'],
['ABCC_STOCK_SHOW', 'label'],
['ABCC_STOCK_THRESHOLD', '999'],
['ABCC_DEFAULT_PASSWORD', '12345678'],
['ABCC_ENABLE_NEW_PRODUCTS', '1'],
['ABCC_NBR_DAYS_NEW_PRODUCT', '20'],
['ABCC_NBR_ITEMS_IS_QUANTITY', 'items'],	// 'quantity', 'items', 'value'
['ABCC_ITEMS_PERPAGE', '8'],
['ABCC_CART_PERSISTANCE', '30'],
['ABCC_DEFAULT_ORDER_TEMPLATE', ''],
['ABCC_ORDERS_SEQUENCE', ''],

['ABI_IMPERSONATE_TIMEOUT', '0'],
['ABI_TIMEOUT_OFFSET', '3'],
['ABI_MAX_ROUNDCYCLES', '25'],

['ALLOW_PRODUCT_SUBCATEGORIES', '1'],
['ALLOW_SALES_RISK_EXCEEDED', '0'],
['ALLOW_SALES_WITHOUT_STOCK', '0'],
['CUSTOMER_ORDERS_NEED_VALIDATION', '0'],
['DEF_CARRIER', ''],
['DEF_COMPANY', ''],
['DEF_COUNTRY', ''],
['DEF_CURRENCY', ''],
['DEF_CUSTOMER_INVOICE_SEQUENCE', ''],
['DEF_CUSTOMER_INVOICE_TEMPLATE', ''],
['DEF_CUSTOMER_ORDER_SEQUENCE', ''],
['DEF_CUSTOMER_PAYMENT_METHOD', ''],
['DEF_DIMENSION_UNIT', 'cm'],
['DEF_DISTANCE_UNIT', 'km'],
['DEF_ITEMS_PERAJAX', '10'],
['DEF_ITEMS_PERPAGE', '8'],
['DEF_LANGUAGE', ''],
['DEF_LOGS_PERPAGE', '100'],
['DEF_MEASURE_UNIT_FOR_BOMS', ''],
['DEF_MEASURE_UNIT_FOR_PRODUCTS', ''],
['DEF_OUTSTANDING_AMOUNT', '3000.0'],
['DEF_PAYMENT_METHOD', ''],
['DEF_PERCENT_DECIMALS', '2'],
['DEF_QUANTITY_DECIMALS', '0'],
['DEF_SHIPPING_METHOD', ''],
['DEF_TAX', ''],
['DEF_VOLUME_UNIT', 'cl'],
['DEF_WAREHOUSE', ''],
['DEF_WEIGHT_UNIT', 'kg'],
['DEVELOPER_MODE', '0'],
['DOCUMENT_ROUNDING_METHOD', 'total'],
['ENABLE_COMBINATIONS', '0'],
['ENABLE_CUSTOMER_CENTER', '0'],
['ENABLE_SALESREP_CENTER', '0'],
// ['ENABLE_WAREHOUSE', '1'],
['ENABLE_MANUFACTURING', '0'],
['ENABLE_WEBSHOP_CONNECTOR', '0'],
['ENABLE_FSOL_CONNECTOR', '0'],
['FSOL_ABI_CUSTOMER_CODE_BASE', '80000'],
['FSOL_AUSCFG', ''],
['FSOL_CBDCFG', ''],
['FSOL_CBRCFG', 'factusolweb.sql'],
['FSOL_CCLCFG', 'nclientes/'],
['FSOL_CIACFG', 'imagenes/'],
['FSOL_CONFIGURATIONS_CACHE', ''],
['FSOL_CPVCFG', 'npedidos/'],
['FSOL_IMPUESTO_DIRECTO_TIPO_1', ''],
['FSOL_IMPUESTO_DIRECTO_TIPO_2', ''],
['FSOL_IMPUESTO_DIRECTO_TIPO_3', ''],
['FSOL_IMPUESTO_DIRECTO_TIPO_4', ''],
['FSOL_PIV1CFG', '21.0000'],
['FSOL_PIV2CFG', '10.0000'],
['FSOL_PIV3CFG', '4.0000'],
['FSOL_PRE1CFG', '5.2000'],
['FSOL_PRE2CFG', '1.4000'],
['FSOL_PRE3CFG', '0.5000'],
['FSOL_SPCCFG', ''],
['FSOL_TCACFG', ''],
['FSOL_WEB_CUSTOMER_CODE_BASE', '50000'],
['FSX_DLOAD_ORDER_SHIPPING_ADDRESS', '1'],
['FSX_FORCE_CUSTOMERS_DOWNLOAD', '0'],
['FSX_FORMAS_DE_PAGO_CACHE', ''],
['FSX_FORMAS_DE_PAGO_DICTIONARY_CACHE', ''],
['FSX_ORDER_LINES_REFERENCE_CHECK', '0'],
['FSX_PAYMENT_METHODS_CACHE', ''],
['HEADER_TITLE', '<span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> </span>'],
['SELL_ONLY_MANUFACTURED', '0'],
['INCLUDE_SHIPPING_COST_IN_PROFIT', '0'],
['MARGIN_METHOD', 'PRC'],
['NEW_PRICE_LIST_POPULATE', '0'],
['NEW_PRODUCT_TO_ALL_PRICELISTS', '0'],
['PRICES_ENTERED_WITH_TAX', '0'],
['PRODUCT_NOT_IN_PRICELIST', 'block'],
['QUOTES_EXPIRE_AFTER', '30'],
['ROUND_PRICES_WITH_TAX', '1'],
['SKU_AUTOGENERATE', '0'],
['SKU_PREFIX_LENGTH', '4'],
['SKU_PREFIX_OFFSET', '100'],
['SKU_SEPARATOR', '-'],
['SKU_SUFFIX_LENGTH', '1'],
['STOCK_COUNT_IN_PROGRESS', '0'],
['SUPPORT_CENTER_EMAIL', ''],
['SUPPORT_CENTER_NAME', 'aBillander Support Center'],
['SW_DATABASE_VERSION', '0.2.2'],
['SW_VERSION', '0.2.2'],
['TAX_BASED_ON_SHIPPING_ADDRESS', '0'],
['TIMEZONE', 'Europe/Madrid'],
['USE_CUSTOM_THEME', ''],
['WOOC_CONFIGURATIONS_CACHE', '0'],
['WOOC_CURRENCY', 'EUR'],
['WOOC_DECIMAL_PLACES', '2'],
['WOOC_DEF_CURRENCY', ''],
['WOOC_DEF_CUSTOMER_GROUP', ''],
['WOOC_DEF_CUSTOMER_PRICE_LIST', ''],
['WOOC_DEF_INVOICES_SEQUENCE', ''],
['WOOC_DEF_LANGUAGE', ''],
['WOOC_DEF_ORDERS_SEQUENCE', ''],
['WOOC_DEF_SHIPPING_TAX', ''],
['WOOC_DEF_TAX', ''],
['WOOC_DEFAULT_COUNTRY', ''],
['WOOC_ENABLE_GUEST_CHECKOUT', 'no'],
['WOOC_ORDER_NIF_META', ''],
['WOOC_ORDERS_PER_PAGE', '10'],
['WOOC_PAYMENT_GATEWAYS_CACHE', ''],
['WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE', ''],
['WOOC_PRICES_INCLUDE_TAX', ''],
['WOOC_SAVE_INVOICE_AS_DRAFT', ''],
['WOOC_SHIPPING_METHODS_CACHE', ''],
['WOOC_SHIPPING_METHODS_DICTIONARY_CACHE', ''],
['WOOC_SHIPPING_TAX_CLASS', ''],
['WOOC_TAXES_CACHE', ''],
['WOOC_TAXES_DICTIONARY_CACHE', ''],
['WOOC_USE_LOCAL_PRODUCT_NAME', '0'],

];


        foreach ($confs as $v){

                Configuration::updateValue( $v[0] , $v[1] );
        }
		
		/*
		$configurations = array(
			array(	'name'        => 'SW_VERSION', 
					'value'       => '0.2.2',
					'created_at'  => \Carbon\Carbon::createFromDate(2015,03,31)->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),		// date('Y-m-d H:i:s');
					),
			array(	'name'        => 'SW_DATABASE_VERSION', 
					'value'       => '0.2.2',
					'created_at'  => \Carbon\Carbon::createFromDate(2015,03,31)->toDateTimeString(),
					'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
					),

		);

		// Uncomment the below to run the seeder
		DB::table('configurations')->insert($configurations);

		*/
	}

}
