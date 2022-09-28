<?php

return [

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Orders Language Lines :: worders
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Online Shop Orders'     => 'Pedidos Tienda Online',
	'Import Orders'     => 'Importar pedidos',
	'Add Orders to Production Sheet'     => 'Añadir pedidos a Hoja de Producción',
	'Add'     => 'Añadir',
	'Add Orders to NEW Production Sheet'     => 'Añadir pedidos a NUEVA Hoja de Producción',
	'Date'     => 'Fecha',
	'Name'     => 'Nombre',
	'Production Sheet'     => 'Hoja de Producción',
	'No active Production Sheet found.'     => 'No se han encontrado Hojas de Producción activas.',
	
	
	'Date from'     => 'Fecha desde',
	'Date to'     => 'Fecha hasta',
	
	'Order #'     => 'Pedido #',
	'Order Date'     => 'Fecha Pedido',
	'Payment Date'     => 'Fecha Pago',
	'Import Date'     => 'Fecha Importación',
	'Production Date'     => 'Fecha Producción',
	'Customer Note'     => 'Notas Cliente',
	'Go to Production Sheet'     => 'Ir a la Hoja de Producción',

	'Change Order Status'     => 'Cambiar el Estado del Pedido',
	'New Order Status'     => 'Nuevo Estado',
	'Mark as Paid'     => 'Marcar como pagado',
	''     => '',

	''     => '',

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Orders Language Lines :: show
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Back to WooCommerce Orders'     => 'Volver a Pedidos Tienda Online',
	'WooCommerce Orders'     => 'Pedidos Tienda Online',
	'Aggregated WooCommerce Orders'     => 'Resumen de Pedidos Tienda Online',
	'WooC Order #' => 'Pedido #',
	'Status'     => 'Estado',
	'Created at'     => 'Creado Fecha',
	'Paid at'     => 'Pagado Fecha',
	'Company'     => 'Empresa',
	'Address'     => 'Dirección',
	'City'     => 'Ciudad',
	'State'     => 'Provincia',
	'Customer Notes'     => 'Notas Cliente',
	'Reference'     => 'Referencia',
	'Description'     => 'Descripción',
	'Quantity'     => 'Cantidad',
	'Payment method'     => 'Método de Pago',
	'Shipping method'     => 'Método de Envío',
	'Carrier'     => 'Transportista',

	'Customer'     => 'Cliente',

	'Transaction ID'     => 'ID Transacción',

	'Go to Customer Order'     => 'Ir al Pedido del Cliente',
	'This Customer Order has not been imported'     => 'Este Pedido de Cliente no ha sido importado',

	'Go to Customer'     => 'Ir al Cliente',
	'This Customer has not been imported'     => 'Este Cliente no ha sido importado',
	'Shipping Address is different from Billing Address!' => 'La Dirección de Envío es diferente de la Dirección de Facturación!',
	'Shipping Address is the same as Billing Address.' => 'La Dirección de Envío coincide con la Dirección de Facturación.',

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Settings Language Lines :: show
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'General'     => 'General',
	'Taxes'     => 'Impuestos',
	'Payment Gateways'     => 'Formas de Pago',
	'Shipping Methods'     => 'Métodos de Envío',
	'Shop'     => 'Tienda',
	'All Keys'     => 'Todas las Claves',

	'WooConnect Settings'     => 'WooConnect Configuración',
	'WooCommerce link Settings'     => 'Configuración del enlace con WooCommerce',

	'WooCommerce link'     => 'Enlace WooCommerce',
	'WooC link'     => 'Enlace WooC',

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Settings Language Lines :: help
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'WOOC_STORE_URL.name'     => 'URL de la Tienda',
	'WOOC_STORE_URL.help'     => 'Algo como: <i>https://www.mywoostore.com/</i>',
	'WOOC_CONSUMER_KEY.name'     => 'Clave de cliente',
	'WOOC_CONSUMER_KEY.help'     => '',
	'WOOC_CONSUMER_SECRET.name'     => 'Clave secreta',
	'WOOC_CONSUMER_SECRET.help'     => '',


	'WOOC_DECIMAL_PLACES.name'     => 'Número de decimales',
//	'WOOC_DECIMAL_PLACES.help'     => 'Los Precios de WooCommerce se recuperan con este número de posiciones decimales. Para no perder precisión, poner un número superior al número de posiciones decimales con que se ha configurado WooCommerce (<span style="font-style: italic;">\'woocommerce_price_num_decimals\'</span>).',	// Number of decimal places WooCommerce works with. It is a WooCommerce Store Setting.',
	'WOOC_DECIMAL_PLACES.help'     => 'Número de posiciones decimales a utilizar en cada recurso. El valor predeterminado es 2. Se utiliza al recuperar un Pedido de Cliente a través de la API REST.',
	// Number of decimal positions to use in each resource. Default is 2. Used when retrieving a Customer Order through REST API.',	// Number of decimal places WooCommerce works with. It is a WooCommerce Store Setting.',
	'WOOC_DEF_CURRENCY.name'     => 'Divisa',
	'WOOC_DEF_CURRENCY.help'     => 'Moneda de la Tienda WooCommerce.',
	'WOOC_DEF_CUSTOMER_GROUP.name'     => 'Grupo de Clientes',
	'WOOC_DEF_CUSTOMER_GROUP.help'     => 'Los Clientes que se importan desde WooCommerce serán asignados a este Grupo.',		// Imported Customers will be asigned to this Group
	'WOOC_DEF_CUSTOMER_PRICE_LIST.name'     => 'Tarifa',
	'WOOC_DEF_CUSTOMER_PRICE_LIST.help'     => 'Esta Tarifa se usará para actualizar el Precio de los Productos en la Tienda. Si no selecciona una Tarifa, se utilizará el Precio de la Ficha del Producto. Además, los Clientes que se importan desde WooCommerce se les asignará esta Tarifa.',	// Imported Customers will be asigned this Price List

	'WOOC_DEF_WAREHOUSE.name'     => 'Almacén',
	'WOOC_DEF_WAREHOUSE.help'     => 'Este Almacén se usará para actualizar el Stock Físico de los Productos en la Tienda. Si no selecciona un Almacén, se utilizará el Stock Total del Producto.',

	'WOOC_DEF_LANGUAGE.name'     => 'Idioma',
	'WOOC_DEF_LANGUAGE.help'     => 'Idioma de la Tienda WooCommerce.',
	'WOOC_DEF_ORDERS_SEQUENCE.name'     => 'Serie para Pedidos',
	'WOOC_DEF_ORDERS_SEQUENCE.help'     => 'Los Pedidos que se importan desde WooCommerce serán asignados a esta Serie.',		// Sequence for Customer Orders imported from WooCommerce
	'WOOC_DEF_SHIPPING_TAX.name'     => 'Impuesto para los Gastos de Envío',
	'WOOC_DEF_SHIPPING_TAX.help'     => 'Impuesto para los Gastos de Envío en la Tienda WooCommerce.',

	'WOOC_PRODUCT_EAN_META.name'     => 'Campo "Meta" para el EAN',
	'WOOC_PRODUCT_EAN_META.help'     => 'Campo "Meta" del Producto en WooCommerce donde se guarda el código EAN.',		// Product Meta field name to store EAN code.
	'WOOC_ORDER_NIF_META.name'     => 'Campo "Meta" para el NIF',
	'WOOC_ORDER_NIF_META.help'     => 'Campo "Meta" del Pedido en WooCommerce donde se guarda el NIF/CIF/NIE.',		// Order Meta field name to store Spanish NIF/CIF/NIE.
	'WOOC_ORDERS_PER_PAGE.name'     => 'Pedidos por página',
	'WOOC_ORDERS_PER_PAGE.help'     => 'Número de Pedidos (máximo) para resultados paginados.',

	'WOOC_USE_LOCAL_PRODUCT_NAME.name'     => 'Usar el Nombre local del Producto',
	'WOOC_USE_LOCAL_PRODUCT_NAME.help'     => 'En los Pedidos importados, usar el Nombre del Producto en lugar del Nombre en WooCommerce.',


	'WOOC_DEF_PRODUCT_STATUS.name'     => 'Subir a WooCommerce Productos en Estado:',
	'WOOC_DEF_PRODUCT_STATUS.help'     => '',
	'WOOC_DEF_MANAGE_STOCK.name'       => '¿Gestionar el Inventario en WooCommerce?',
	'WOOC_DEF_MANAGE_STOCK.help'       => 'Per Item: Según el campo "Control de Stock" de cada Producto.',
	'WOOC_DEF_REVIEWS_ALLOWED.name'    => 'Activar valoraciones de Productos',
	'WOOC_DEF_REVIEWS_ALLOWED.help'    => '',

	''     => '',
	''     => '',

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Settings Language Lines :: Configuration
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'WooCommerce Connect - Configuration'     => 'WooCommerce Connect - Configuración',
	'WooCommerce Connect - Configurations'     => 'WooCommerce Connect - Configuraciones',

	'WooCommerce Connect - WooCommerce Shop Settings'     => 'WooCommerce Connect - Configuración de la Tienda WooCommerce',
	'Retrieve your WooCommerce shop Settings.'     => 'Cargar las Configuraciones de la Tienda WooCommerce.',

	'WooCommerce Connect - Taxes Dictionary'     => 'WooCommerce Connect - Diccionario de Impuestos',
	'WooCommerce Connect - Payment Gateways Dictionary'     => 'WooCommerce Connect - Diccionario de Formas de Pago',
	'WooCommerce Connect - Shipping Methods Dictionary'     => 'WooCommerce Connect - Diccionario de Métodos de Envío',
	''     => '',
	''     => '',
	''     => '',
	''     => '',
	'Disabled'     => 'Deshabilitado',
	'Need update!'     => 'Actualización necesaria!',
	''     => '',

	/*
	|--------------------------------------------------------------------------
	| WooOrderImporter
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'-Billing'     => '-Principal',
	'-Shipping'    => '-Envío',

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Categories Language Lines :: show
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Categories'     => 'Categorías',
	'Products' => 'Productos',
//	'ID'     => 'ID', from layouts
	'Parent ID'     => 'ID del Padre',
	'Category Name'     => 'Nombre de la Categoría',
	'Display'     => 'Mostrar',
	'Menu Order'     => 'Posición en el Menú',
	'Products Count'     => 'Número de Productos',
	'Slug'     => 'Slug',
	'Set Local Category'     => 'Enlazar con Categoría Local',
	'Local Category'     => 'Categoría Local',
	'WooCommerce Category'     => 'Categoría WooCommerce',


	/*
	|--------------------------------------------------------------------------
	| WooCommerce Products Language Lines :: show
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'SKU' => 'SKU',
	'Product Name' => 'Nombre del Producto',
	'Category' => 'Categoría',
	'Type' => 'Tipo',
	'Price' => 'Precio',
	'Regular Price' => 'Precio Normal',
	'Sale Price' => 'Precio en Oferta',
	'Tax' => 'Impuesto',
	'Weight' => 'Peso',

	'name' => 'Nombre del Producto',
	'sku' => 'SKU',
	'status' => 'Estado',
	'featured' => 'Producto destacado?',
	'manage_stock' => 'Gestión de Inventario?',
	'stock_status' => 'En Inventario?',
	'reviews_allowed' => 'Permitir Valoraciones',

	'stock_quantity' => 'Stock en Tienda',
	'catalog_visibility' => 'Visibilidad',

	'regular_price' => 'Precio Normal',
	'sale_price' => 'Precio en Oferta',
	'Product regular price.' => 'Precio Normal del Producto.',
	'Product sale price.' => 'Precio en Oferta del Producto.',

	'description' => 'Descripción',
	'short_description' => 'Descripción corta',

	'Fill only if \'manage_stock\' is set to \'Yes\'.' => 'Rellenar sólo si \'Gestión de Inventario?\' tiene el valor \'Sí\'.',
	'Fill only if \'manage_stock\' is set to \'No\'.'  => 'Rellenar sólo si \'Gestión de Inventario?\' tiene el valor \'No\'.',


	/*
	|--------------------------------------------------------------------------
	| WooCommerce Customers Language Lines :: show
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Customers' => 'Clientes',
	

	/*
	|--------------------------------------------------------------------------
	| WooCommerce Language Lines :: Last aditions
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Go to local Product'     => 'Ir al Producto',

	'Billing Address'     => 'Dirección de Facturación',
	'Shipping Address'     => 'Dirección de Envío',

	'Some Product Descriptions has been retrieved from WooCommerce Shop.'     => 'Algunas Descripciones de Productos han sido importadas desde la Tienda WooCommerce.',
	'Some Product Images has been retrieved from WooCommerce Shop.'     => 'Algunas Imagenes de Productos han sido importadas desde la Tienda WooCommerce.',
	''     => '',
	''     => '',
	''     => '',
	''     => '',

];
