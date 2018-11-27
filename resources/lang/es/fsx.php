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

	'FSx-Connector Settings'     => 'FSx-Connector Configuración',
	'FactuSOL link Settings'     => 'Configuración del enlace con FactuSOL',

	/*
	|--------------------------------------------------------------------------
	| FSx-Connector Settings Language Lines :: help
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'FSOL_SPCCFG.name' => 'Número de Serie para pedidos por Internet',
	'FSOL_SPCCFG.help' => 'Los Pedidos importados a FactuSOL irán a esta Serie.',

	'FSOL_WEB_CUSTOMER_CODE_BASE.name' => 'Código base para Clientes (Web) en FactuSOL',
	'FSOL_WEB_CUSTOMER_CODE_BASE.help' => '',

	'FSOL_ABI_CUSTOMER_CODE_BASE.name' => 'Código base para Clientes (aBillander) en FactuSOL',
	'FSOL_ABI_CUSTOMER_CODE_BASE.help' => '',

	'FSOL_CBDCFG.name' => 'Carpeta de subida de datos',
	'FSOL_CBDCFG.help' => 'Ruta completa, como: <i>/home/mysite/public_html/FSx-Connector/fsweb/BBDD/</i>.',

	'FSOL_CIACFG.name' => 'Carpeta de imágenes',
	'FSOL_CIACFG.help' => 'imagenes/',

	'FSOL_CPVCFG.name' => 'Carpeta descarga de Pedidos',
	'FSOL_CPVCFG.help' => 'npedidos/',

	'FSOL_CCLCFG.name' => 'Carpeta descarga de Clientes',
	'FSOL_CCLCFG.help' => 'nclientes/',

	'FSOL_CBRCFG.name' => 'Nombre del Fichero de Datos',
	'FSOL_CBRCFG.help' => 'factusolweb.sql',

	'FSX_FORCE_CUSTOMERS_DOWNLOAD.name' => 'Descargar siempre el Cliente del Pedido',
	'FSX_FORCE_CUSTOMERS_DOWNLOAD.help' => '1 : Se descargará el Cliente cada vez que hace un Pedido. <br>0 : Sólo se descarga el Cliente la primera vez que hace un Pedido.',

	'FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS.name' => 'Descargar Clientes con Dirección de Entrega',
	'FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS.help' => '1 : Se descargará el Cliente siempre que la Dirección de Entrega del Pedido es diferente de la Dirección Principal del Cliente. <br>0 : El Cliente se descarga según otras configuraciones.',

	'FSX_ORDER_LINES_REFERENCE_CHECK.name' => 'Comprobar la Referencia de las Líneas de Pedido',
	'FSX_ORDER_LINES_REFERENCE_CHECK.help' => '',

	'FSOL_IMPUESTO_DIRECTO_TIPO_1.name' => 'Impuesto Directo Tipo 1',
	'FSOL_IMPUESTO_DIRECTO_TIPO_1.help' => 'El valor obtenido de FactuSOL es: <span style="font-size: 12px; font-weight: bold">:fsol_value%</span>.',

	'FSOL_IMPUESTO_DIRECTO_TIPO_2.name' => 'Impuesto Directo Tipo 2',
	'FSOL_IMPUESTO_DIRECTO_TIPO_2.help' => 'El valor obtenido de FactuSOL es: <span style="font-size: 12px; font-weight: bold">:fsol_value%</span>.',

	'FSOL_IMPUESTO_DIRECTO_TIPO_3.name' => 'Impuesto Directo Tipo 3',
	'FSOL_IMPUESTO_DIRECTO_TIPO_3.help' => 'El valor obtenido de FactuSOL es: <span style="font-size: 12px; font-weight: bold">:fsol_value%</span>.',

	'FSOL_IMPUESTO_DIRECTO_TIPO_4.name' => 'Impuesto Directo Tipo 4',
	'FSOL_IMPUESTO_DIRECTO_TIPO_4.help' => 'El valor obtenido de FactuSOL es: <span style="font-size: 12px; font-weight: bold">:fsol_value%</span>.',

	'FSX_LOAD_FAMILIAS_TO_ROOT.name' => 'Familias en la raíz del Catálogo',
	'FSX_LOAD_FAMILIAS_TO_ROOT.help' => 'Las Familias se cargarán en la raíz del Catálogo.<br /><b>Atención:</b><br /> si selecciona \'Sí\', entonces las Secciones NO se tendrán en cuenta.',

	'FSX_LOAD_ARTICULOS.name' => 'Cargar Catálogo',
	'FSX_LOAD_ARTICULOS.help' => 'Cargar las Secciones, las Familias y los Artículos nuevos de FactuSOL que no están en aBillander.',

	'FSX_LOAD_ARTICULOS_ACTIVE.name' => '¿Activar nuevos Productos?',
	'FSX_LOAD_ARTICULOS_ACTIVE.help' => 'Los nuevos Artículos se crean con status \'Activo\'.',

	'FSX_LOAD_ARTICULOS_PRIZE_ALL.name' => 'Actualizar Precios',
	'FSX_LOAD_ARTICULOS_PRIZE_ALL.help' => 'Actualizar precios de todos los Productos.',

	'FSX_LOAD_ARTICULOS_STOCK_ALL.name' => 'Actualizar Stock',
	'FSX_LOAD_ARTICULOS_STOCK_ALL.help' => 'Actualizar stock de todos los Productos.',

	'FSX_PROD_ABI_ONLY_DEACTIVATE.name' => 'Desactivar los Productos no encontrados',
	'FSX_PROD_ABI_ONLY_DEACTIVATE.help' => 'Desactivar los Productos de aBillander que no tienen correspondencia en FactuSOL.',

	'FSX_FSOL_AUSCFG_PEER.name' => 'Almacén para cargar el Stock',
	'FSX_FSOL_AUSCFG_PEER.help' => '',




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

	'FSx-Connector - FactuSOLWeb Settings'     => 'FSx-Connector - Configuración de FactuSOLWeb',
	'Retrieve your FactuSOLWeb Settings.'     => 'Cargar las Configuraciones de FactuSOLWeb.',

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

	/*
	|--------------------------------------------------------------------------
	| FSxProductImporter
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'FSx-Connector - Import Products' => 'FSx-Connector - Importar Productos',
	'Products' => 'Productos',
	'Back to Products'     => 'Volver a Productos',
	''     => '',

];
