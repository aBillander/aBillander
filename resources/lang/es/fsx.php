<?php

// Context::getContext()->controller = $request->segment(1);       <=     $request->segment(1) == route prefix !!!

return [

	/*
	|--------------------------------------------------------------------------
	| FSx-Connector Settings Language Lines :: show
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'General'     => 'General',
	'Taxes'     => 'Impuestos',
	'Payment Methods'     => 'Formas de Pago',
	'Shipping Methods'     => 'Métodos de Envío',
	'FactuSOLWeb'     => 'FactuSOLWeb',
	'All Keys'     => 'Todas las Claves',

	'WooConnect Settings'     => 'WooConnect Configuración',
	'WooCommerce link Settings'     => 'Configuración del enlace con WooCommerce',

	/*
	|--------------------------------------------------------------------------
	| FSx-Connector Settings Language Lines :: help
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'WOOC_DECIMAL_PLACES.name'     => 'Número de decimales',
	'WOOC_DECIMAL_PLACES.help'     => 'Número de posiciones decimales con que trabaja WooCommerce,',	// Number of decimal places WooCommerce works with. It is a WooCommerce Store Setting.',
	'WOOC_DEF_CURRENCY.name'     => 'Divisa',
	'WOOC_DEF_CURRENCY.help'     => 'Moneda de FactuSOL.',
	'WOOC_DEF_CUSTOMER_GROUP.name'     => 'Grupo de Clientes',
	'WOOC_DEF_CUSTOMER_GROUP.help'     => 'Los Clientes que se importan desde WooCommerce serán asignados a este Grupo.',		// Imported Customers will be asigned to this Group
	'WOOC_DEF_CUSTOMER_PRICE_LIST.name'     => 'Tarifa',
	'WOOC_DEF_CUSTOMER_PRICE_LIST.help'     => 'Los Clientes que se importan desde WooCommerce se les asignará esta Tarifa.',	// Imported Customers will be asigned this Price List

	'WOOC_DEF_LANGUAGE.name'     => 'Idioma',
	'WOOC_DEF_LANGUAGE.help'     => 'Idioma de FactuSOL.',
	'WOOC_DEF_ORDERS_SEQUENCE.name'     => 'Serie para Pedidos',
	'WOOC_DEF_ORDERS_SEQUENCE.help'     => 'Los Pedidos que se importan desde WooCommerce serán asignados a esta Serie.',		// Sequence for Customer Orders imported from WooCommerce
	'WOOC_DEF_SHIPPING_TAX.name'     => 'Impuesto para los Gastos de Envío',
	'WOOC_DEF_SHIPPING_TAX.help'     => 'Impuesto para los Gastos de Envío en FactuSOL.',

	'WOOC_ORDER_NIF_META.name'     => 'Campo "Meta" para el NIF',
	'WOOC_ORDER_NIF_META.help'     => 'Campo "Meta" del Pedido en WooCommerce donde se guarda el NIF/CIF/NIE.',		// Order Meta field name to store Spanish NIF/CIF/NIE.
	'WOOC_ORDERS_PER_PAGE.name'     => 'Pedidos por página',
	'WOOC_ORDERS_PER_PAGE.help'     => 'Número de Pedidos (máximo) para resultados paginados.',

	'WOOC_USE_LOCAL_PRODUCT_NAME.name'     => 'Usar el Nombre local del Producto',
	'WOOC_USE_LOCAL_PRODUCT_NAME.help'     => 'En los Pedidos importados, usar el Nombre del Producto en lugar del Nombre en WooCommerce.',
	''     => '',
	''     => '',

	/*
	|--------------------------------------------------------------------------
	| FSx-Connector Settings Language Lines :: Configuration
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'FSx-Connector - Configuration'     => 'FSx-Connector - Configuración',
	'FSx-Connector - Configurations'     => 'FSx-Connector - Configuraciones',

	'FSx-Connector - FactuSOL Settings'     => 'FSx-Connector - Configuración de FactuSOL',
	'Retrieve your FactuSOL Settings.'     => 'Cargar las Configuraciones de FactuSOL.',

	'FSx-Connector - Taxes Dictionary'     => 'FSx-Connector - Diccionario de Impuestos',
	'FSx-Connector - Payment Methods Dictionary'     => 'FSx-Connector - Diccionario de Formas de Pago',
	'FSx-Connector - Shipping Methods Dictionary'     => 'FSx-Connector - Diccionario de Métodos de Envío',
	''     => '',
	''     => '',
	''     => '',
	''     => '',
	'Disabled'     => 'Deshabilitado',
	''     => '',
	''     => '',

	/*
	|--------------------------------------------------------------------------
	| FSxOrderImporter
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	''     => '',

];
