<?php

// Context::getContext()->controller = $request->segment(1);       <=     $request->segment(1) == route prefix !!!

return [

	/*
	|--------------------------------------------------------------------------
	| FSx-Connector FSxLogger Language Lines :: Español
	|--------------------------------------------------------------------------
	|
	*/

	'aBillander LOG' => 'aBillander LOG',

	'Date/Time' => 'Fecha/Hora',
	'Type' => 'Tipo',
	'Message' => 'Mensaje',
	'' => '',
	'WARNING(S)' => 'AVISO(S)',
	'ERROR(S)' => 'ERROR(ES)',
	'Empty LOG' => 'Vaciar el LOG',
	'' => '',


	/*
	|--------------------------------------------------------------------------
	| FSx-Connector FSxLogger Language Lines :: ERRORES
	|--------------------------------------------------------------------------
	|
	*/

	'LOG_INFO_1000' => 'LOG iniciado',
	'LOG_INFO_1010' => 'LOG reiniciado',
	'LOG_INFO_1020' => 'LOG terminado',

	'LOG_INFO_6000' => '<b>Secciónes</b>: NO se cargan.',
	'LOG_INFO_6005' => 'Sección [%s] %s se ha cargado a la Tienda.',
	'LOG_AVISO_6010' => 'Sección [%s] %s ya existe en la Tienda. No se ha cargado.',
	'LOG_AVISO_6210' => 'Se ha borrado una entrada del Diccionario (Sección).',
	'LOG_ERROR_6215' => 'No se encuentra la Categoría %s correspondiente a la Sección [%s] %s.',
	'LOG_ERROR_6220' => 'No se encuentra la Sección %s correspondiente a la Categoría [%s] %s.',
	'LOG_ERROR_6225' => 'No se encuentra la Categoría %s. No se encuentra la Sección %s.',

	'LOG_INFO_6100' => '<b>Familias</b>: NO se cargan.',
	'LOG_INFO_6101' => '<b>Familias</b>: Se cargan en la raíz del Catálogo.',
	'LOG_INFO_6105' => 'Familia [%s] %s se ha cargado a la Tienda.',
	'LOG_AVISO_6110' => 'Familia [%s] %s ya existe en la Tienda. No se ha cargado.',
	'LOG_AVISO_6310' => 'Se ha borrado una entrada del Diccionario (Familia).',
	'LOG_ERROR_6315' => 'No se encuentra la Categoría %s correspondiente a la Familia [%s] %s.',
	'LOG_ERROR_6320' => 'No se encuentra la Familia %s correspondiente a la Categoría [%s] %s.',
	'LOG_ERROR_6325' => 'No se encuentra la Categoría %s. No se encuentra la Familia %s.',

	'LOG_INFO_6500'  , '<b>Artículos</b>: NO se cargan.',
	'LOG_INFO_6505'  , 'Artículo [%s] %s se ha cargado a la Tienda.',
	'LOG_ERROR_6505' => 'Artículo [%s] %s <span class="log-showoff-format">NO</span> se ha cargado a la Tienda. No se ha encontrado la correspondencia en el Impuesto',
	'LOG_AVISO_6510' => 'Artículo [%s] %s ya existe en la Tienda. No se ha cargado.',
	'LOG_INFO_6550'  , 'Se ha actualizado el Precio de todos los Productos en la Tienda.',
	'LOG_INFO_6551'  , 'Se ha actualizado el Stock de todos los Productos en la Tienda.',
	'LOG_AVISO_6555' => 'Se ha desactivado el Producto [<b>%s</b>] <b>%s</b> en la Tienda (no se encontró en FactuSol).',

	'LOG_INFO_8000'  , 'El Pedido %s se ha descargado correctamente. ',
	'LOG_ERROR_8001' => 'No se pueden descargar Clientes. La carpeta destino no tiene permisos de escritura (%s).',
	'LOG_INFO_8004'  , 'El Cliente <span class="log-showoff-format">(%s) %s</span> del Pedido %s ya está en FactuSOL (%s). No se ha creado un fichero para descargarlo.',
	'LOG_INFO_8005'  , 'Se ha creado un fichero de Cliente <span class="log-showoff-format">(%s) %s</span> para el Pedido %s.',
	'LOG_ERROR_8006' => 'El fichero de Cliente %s ya existe. El Pedido %s no se descargará.',
	'LOG_ERROR_8007' => 'No se ha creado un fichero de Cliente <span class="log-showoff-format">(%s) %s</span>. El Pedido <b>%s</b> no se descargará.',
	'LOG_INFO_8010'  , 'El <span class="log-showoff-format">Pedido %s</span> no se ha descargado. ',
	'LOG_INFO_8012'  , 'El País del Cliente no coincide.',
	'LOG_INFO_8013'  , 'La Dirección de Entrega del Pedido es diferente de la Dirección Principal del Cliente.',
	'LOG_ERROR_8022' => 'El Producto <span class="log-showoff-format">(%s) %s</span> no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.',
	'LOG_AVISO_8022' => 'El Producto <span class="log-showoff-format">(%s) %s</span> no se ha hallado correspondencia en FactuSol (en el Pedido: <b>%s</b>).',
	'LOG_AVISO_8023' => 'El Producto <span class="log-showoff-format">(%s) %s</span> no se ha hallado correspondencia en FactuSol y tiene un valor no nulo en el campo "Referencia" %s (en el Pedido: <b>%s</b>).',
	'LOG_ERROR_8025' => 'El Producto <span class="log-showoff-format">(%s) %s</span> tiene un Impuesto que no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.',
	'LOG_ERROR_8028' => 'El Coste de Envío del Pedido <span class="log-showoff-format">%s</span> tiene un Impuesto que no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.',
	'LOG_ERROR_8029' => 'El Coste de Contra-Reembolso del Pedido <span class="log-showoff-format">%s</span> tiene un Impuesto que no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.',
	'LOG_AVISO_8032' => 'La Forma de Pago del Pedido <span class="log-showoff-format">%s</span> (%s) no se ha hallado correspondencia en FactuSol. Deberá ponerla manualmente en FactuSol.',
	'LOG_ERROR_8101' => 'No se pueden descargar Pedidos. La carpeta destino no tiene permisos de escritura (%s).',
	'LOG_ERROR_8106' => 'El fichero %s ya existe. El Pedido <b>%s</b> no se ha descargado.',
	'LOG_ERROR_8107' => 'No se ha podido crear un fichero de Pedido %s. El Pedido <b>%s</b> no se ha descargado.',
	'LOG_ERROR_8110' => 'No se ha podido borrar el fichero de Pedido %s, deberá borrarlo manualmente. El Pedido <b>%s</b> no se ha descargado.',

];
