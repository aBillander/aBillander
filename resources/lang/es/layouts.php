<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Common Language Lines :: messages
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Error 404 / Page not found'     => 'Error 404 / Página no encontrada',
	'Coming soon...' => 'Próximamente...',
	'Be right back.' => 'Estamos mejorando el sitio.',

	'Success' => 'Éxito',
	'Info'    => 'Información',
	'Warning' => 'Aviso',
	'Error'   => 'Error',
	
	'Please check the form below for errors' => 'Por favor, compruebe los errores en el formulario más abajo',
	'You are not allowed to do this'                                      =>  'No está autorizado',
	'You are not allowed to access to this resource'  =>  'No está autorizado para acceder a este recurso',
	'This record has been successfully created &#58&#58 (:id) '  =>  'El registro se ha creado correctamente &#58&#58 (:id) ',
	'This record has been successfully updated &#58&#58 (:id) '  =>  'El registro se ha actualizado correctamente &#58&#58 (:id) ',
	'These records have been successfully updated &#58&#58 (:id) '  =>  'Los registros se ha actualizado correctamente &#58&#58 (:id) ',
	'This record has been updated with errors &#58&#58 (:id) Check the Log '  =>  'El registro se ha actualizado con errores &#58&#58 (:id) Consulte el Log. ',
	'This record has been updated with warnings &#58&#58 (:id) Check the Log '  =>  'El registro se ha actualizado con avisos &#58&#58 (:id) Consulte el Log. ',
	'This record has been successfully deleted &#58&#58 (:id) '  =>  'El registro se ha eliminado correctamente &#58&#58 (:id) ',
	'This record cannot be deleted because it is in use &#58&#58 (:id) '  =>  'El registro no puede ser eliminado porque está en uso &#58&#58 (:id) ',
	'This record cannot be deleted because its Status &#58&#58 (:id) '  =>  'El registro no puede ser eliminado por su Estado &#58&#58 (:id) ',
	'This record cannot be deleted because its Quantity or Value &#58&#58 (:id) '  =>  'El registro no puede ser eliminado por su Cantidad o Valor &#58&#58 (:id) ',
	'Unable to create this record &#58&#58 (:id) '               =>  'No se ha podido crear el registro solicitado &#58&#58 (:id) ',
	'Unable to update this record &#58&#58 (:id) '               =>  'No se ha podido actualizar el registro solicitado &#58&#58 (:id) ',
	'Unable to load this record &#58&#58 (:id) '                 =>  'No se ha podido encontrar el registro solicitado &#58&#58 (:id) ',
	'The operation you are trying to do is high risk and cannot be undone.' => 'La operación que intenta hacer es de alto riesgo y no puede deshacerse.',
	'Document has no Lines'                                      =>  'El Documento no contiene Líneas',
	'Document amount should be more than: :amount'               =>  'El Importe del Documento debe ser superior a: :amount',
	'Document is on-hold'                                      =>  'El Documento está en espera',
	'Document Lines do not have enough Lots'					=> 'Las Lineas del Documento no tienen Lotes suficientes',
	'Document is not closed'                                      =>  'El Documento no está cerrado',
	'Document has Payments'                                      =>  'El Documento tiene Pagos',
	'Document not found'										=> 'Documento no encontrado',
	'Document ID not found'										=> 'ID de Documento no encontrado',
	'Unable to close this document &#58&#58 (:id) '              =>  'No se ha podido cerrar el documento solicitado &#58&#58 (:id) ',
	'Unable to close this document because lines do not match &#58&#58 (:id) '              =>  'No se ha podido cerrar el documento solicitado porque las líneas no coinciden &#58&#58 (:id) ',
	'Unable to delete this record &#58&#58 (:id) '               =>  'No se ha podido borrar el registro solicitado &#58&#58 (:id) ',
	'Unable to load PDF Document &#58&#58 (:id) '                =>  'No se puede cargar el documento PDF &#58&#58 (:id) ',
	'This record cannot be created because it already exists &#58&#58 (:id) '  =>  'El registro no puede ser creado porque ya existe &#58&#58 (:id) ',
	'The record with id=:id does not exist'                      =>  'El registro con id=:id no existe',
	'Some records in the list [ :id ] do not exist'              =>  'Algunos registros en la lista [ :id ] no existen',
	'Records in the list [ :id ] are not groupable, because ":field" is not the same. ' => 'Los Registros en la lista [ :id ] no pueden agruparse, porque ":field" no es el mismo para todos. ',
	'This record has been successfully published &#58&#58 (:id) :name as id=:web_id'     => 'El registro se ha publicado correctamente &#58&#58 (:id) :name como id=:web_id',

	'There are Taxes that are not defined for the Country of the Customer &#58&#58 (:id) ' => 'Hay Impuestos que no están definidos para el País del Cliente',
	'There are Ecotaxes that are not defined for the Country of the Customer &#58&#58 (:id) ' => 'Hay Eco-Impuestos que no están definidos para el País del Cliente',

	'Too many Records for this Query &#58&#58 (:id) ' => 'Demasiados Registros para esta Consulta &#58&#58 (:id) ',

	'Your Document has been sent! &#58&#58 (:id) '     => 'Su Documento ¡ha sido enviado! &#58&#58 (:id) ',
	'Your Document could not be sent &#58&#58 (:id) '     => 'Su Documento no ha podido ser enviado &#58&#58 (:id) ',

	'You SHOULD define a System wide Default Value for this Model.' => 'Para este Modelo DEBE definir un Valor por Defecto que se usará en toda la Aplicación.',

	'This function is not available &#58&#58 (:id) ' => 'Esta función no está diosponible &#58&#58 (:id) ',

	'No records selected. ' => 'No se han seleccionado registros. ',
	'No action is taken &#58&#58 (:id) ' => 'No se ha realizado ninguna acción &#58&#58 (:id) ',

	'This configuration has been successfully updated'  =>  'La configuración se ha actualizado correctamente',

	'Margin calculation is based on Cost Price'  => 'El Margen se calcula sobre el Precio de Coste',
	'Margin calculation is based on Sales Price' => 'El Margen se calcula sobre el Precio de Venta',

	'Cost for margin calculation is Product Average Cost Price' => 'El Coste para calcular el Margen es el Precio de Coste Promedio del Producto',
	'Cost for margin calculation is Product Cost Price' => 'El Coste para calcular el Margen es el Precio de Coste del Producto',

	'Shipping Cost included'     => 'Coste de Envío incluido',
	'Shipping Cost excluded'     => 'Coste de Envío excluido',	

	'Margin'     => 'Margen',
	'Document Lines to be included in calculations' => 'Líneas del Documento que se incluirán en los cálculos',
	'Product Lines.' => 'Líneas de Productos.',
	'Discount Lines.' => 'Líneas de Descuentos.',
	'Sevice Lines.' => 'Líneas de Servicios.',
	'Shipping Lines.' => 'Líneas de Coste de Envío.',

	'Depending on Configurations (:yn).' => 'Dependiendo de la Configuración (:yn).',

	'Margin'     => 'Margen',
	'Document Lines to be included in calculations' => 'Líneas del Documento que se incluirán en los cálculos',
	'Product Lines.' => 'Líneas de Productos.',
	'Discount Lines.' => 'Líneas de Descuentos.',
	'Sevice Lines.' => 'Líneas de Servicios.',
	'Shipping Lines.' => 'Líneas de Coste de Envío.',

	'Depending on Configurations (:yn).' => 'Dependiendo de la Configuración (:yn).',

	'Discount on Product Price' => 'Descuento sobre el Precio del Producto',

	'Attachments' => 'Adjuntos',
	'Upload an Attach Files' => 'Subir y Adjuntar Ficheros',

	'The backup has been proceed successfully.' => 'La copia de seguridad se ha realizado correctamente.',
	'Error: The backup process has been failed.' => 'Error: el proceso de copia de seguridad ha fallado.',



	/*
	|--------------------------------------------------------------------------
	| Common Language Lines :: layout
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Basic Data'     => 'Tablas Generales',
	'Currencies' => 'Divisas',	
	'Currency Converter' => 'Conversor de Divisas',
	'Shipping Methods'     => 'Métodos de Envío',
	'Payment Methods'     => 'Formas de Pago',
	'Payment Types' => 'Tipos de Pago',
	'Taxes'     => 'Impuestos',
	'Ecotaxes'     => 'Eco-Impuestos',
	'Cheques' => 'Cheques',
	'Down Payments' => 'Anticipos',
	'Manufacturers'     => 'Fabricantes',
	'Product Categories'     => 'Categorías de Productos',
	'Customer Groups'     => 'Grupos de Clientes',

	'System'     => 'Sistema',
	'Customers'     => 'Clientes',
	'Carriers'     => 'Transportistas',
	'Price Lists'     => 'Tarifas',
	'Price Rules'     => 'Reglas de Precio',
	'ABCC Billboard' => 'ABCC Portada',
	'Sales Representatives'     => 'Agentes',
	'Warehouses'     => 'Almacenes',
	'Product Options'     => 'Opciones de Productos',
	'Products'     => 'Productos',
	'Tools'     => 'Utillajes',
	'Bills of Materials' => 'Listas de Materiales',

	'aBillander LOG' => 'aBillander LOG',
	'Email LOG' => 'Email LOG',
	'DB Backups' => 'Copias de Seguridad',

	'Warehouse'     => 'Almacén',
    'Products with Low Stock' => 'Productos con Bajo Stock',
    'Products with no Stock' => 'Productos sin Stock',
	'Suppliers' => 'Proveedores',
	'Supplier Shipping Slips'     => 'Albaranes de Proveedores',
	'Supplier Invoices'     => 'Facturas de Proveedores',
	'Supplier Vouchers'     => 'Recibos de Proveedores',
	'Purchase Orders'     => 'Pedidos de Compra',
	'Assembly Orders' => 'Ordenes de Montaje',
	'Sale Orders'     => 'Pedidos de Clientes',
	'Sale Order Templates'     => 'Plantillas Pedidos Venta',
	'Orders'     => 'Pedidos',
	'Shipping Slips'     => 'Albaranes',
	'Invoices'     => 'Facturas',
	'Quotations'     => 'Presupuestos',
	'Stock Movements'     => 'Movimientos de Almacén',
	'Lots' => 'Lotes',
	'Inventory Count'     => 'Inventario de Almacén',
	'Inventory Adjustments'     => 'Regularización de Almacén',
	'Warehouse Transfers' => 'Transferencias entre Almacenes',

	'Invoicing'     => 'Facturación',
	'Customer Invoices'     => 'Facturas de Clientes',
	'Customer Vouchers'     => 'Recibos de Clientes',

	'Reports'     => 'Informes',
	'Sales Orders'     => 'Pedidos de Venta',
	'Sales Shipping Slips'     => 'Albaranes de Venta',
	'Sales Shipping Slips (daily)'     => 'Albaranes de Venta (diario)',
	'Sales Invoices'     => 'Facturas de Venta',
	'Accounting'     => 'Contabilidad',
	'Earns & Profit'     => 'Rentabilidad',
	'Analysis' => 'Análisis',

	'Login'     => 'Login',
	'Documentation'     => 'Documentación',
	'Support & feed-back'     => 'Soporte y feed-back',
	'About ...'     => 'Acerca de ...',
	'Company'     => 'Empresa',
	'Banks' => 'Bancos',
	'Todos'     => 'Tareas',
	'Pending Todos'     => 'Tareas Pendientes',
	'Configuration - All keys'     => 'Configuración - Todas las claves',
	'Configuration'     => 'Configuración',
	'Languages'     => 'Idiomas',
	'Translations'     => 'Traducciones',
	'Countries'     => 'Países',
	'Document sequences'     => 'Series de Documentos',
	'Help Contents' => 'Contenidos de Ayuda',
	'Document templates'     => 'Plantillas de Documentos',
	'Users'     => 'Usuarios',
	'Logout'     => 'Salir',

	'Version'     => 'Versión',


	/*
	|--------------------------------------------------------------------------
	| La ExtraNatural :: layout
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

//	'Finished Products'     => 'Elaborados',
//	'Ingredients'     => 'Ingredientes',
//	'Recipes'     => 'Recetas',
	'Measure Units'     => 'Unidades de Medida',
	'Work Centers'     => 'Centros de Trabajo',

	'Sales'     => 'Ventas',
	'Online Shop'     => 'Tienda Online',
	'Production Sheets'     => 'Hojas de Producción',
	'Production Orders'     => 'Ordenes de Fabricación',
	'Manufacturing'     => 'Fabricación',
	'Delivery Routes'     => 'Rutas de Reparto',
	'Delivery Sheets'     => 'Hojas de Reparto',

	'microCRM'     => '&micro;CRM',
	'Dashboard' => 'Tablero',
	'Parties' => 'Terceros',
	'Leads' => 'Oportunidades',
	'Contacts' => 'Contactos',
	


	/*
	|--------------------------------------------------------------------------
	| Common Language Lines :: forms & buttons
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'ID'      => 'ID',
	'Active'  => 'Activo',
	'Not Active'  => 'No Activo',
	'Active?' => '¿Activo?',
	'Blocked'     => 'Bloqueado',
	'Blocked?'     => '¿Bloqueado?',
	'Status'     => 'Estado',
	'Yes'     => 'Sí',
	'No'      => 'No',
	'Default'      => 'Por defecto',
	'Position'     => 'Posición',
	'Notes'     => 'Notas',
	'Alias'     => 'Alias',

	'Filter Records'     => 'Filtrar Registros',
	'Search Records'     => 'Buscar Registros',
	'Date from'     => 'Fecha desde',
	'Date to'     => 'Fecha hasta',
	'Date'     => 'Fecha',
	'From'     => 'Desde',
	'To'     => 'Hasta',

	'Filter'     => 'Filtrar',
	'Search' => 'Buscar',
	'Search terms' => 'Buscar palabras',
	'Use terms of three (3) characters or more' => 'Use palabras de tres (3) caracteres o más',
	'Actions'     => 'Acciones',
	'Export'     => 'Exportar',
	'Export Headers'     => 'Exportar Encabezados',
	'Import'     => 'Importar',
	'File'     => 'Fichero',
	'File Name'     => 'Nombre del Fichero',
	'Load'     => 'Cargar',
	'Reset'     => 'Reiniciar',
	'View Chart'     => 'Ver Gráfico',
	'View Document'     => 'Ver Documento',
	'View'     => 'Ver',
	'View more'     => 'Ver más',
	'Edit'     => 'Modificar',
	'Undo'     => 'Deshacer',
	'Apply'     => 'Aplicar',
	'Refresh'     => 'Refrescar',
	'Show'     => 'Mostrar',
	'Show Preview'     => 'Vista Previa',
	'Show Log'     => 'Mostrar el Log',
	'Progress'     => 'Progreso',
	'Duplicate' => 'Duplicar',
	'Save'     => 'Guardar',
	'Save & Complete'     => 'Guardar y Completar',
	'Save & Confirm'      => 'Guardar y Confirmar',
	'Save & Continue'     => 'Guardar y Continuar',
	'Update'     => 'Actualizar',
	'Process' => 'Procesar',
	'Cancel'     => 'Cancelar',
	'Confirm'     => 'Confirmar',
	'Finish' => 'Terminar',
	'Undo Confirm'     => 'Deshacer Confirmar',
	'Select'     => 'Seleccionar',
	'Delete'     => 'Eliminar',
	'Continue'     => 'Continuar',
	'Back'     => 'Volver',
	'Back to'     => 'Volver a',
	'Go to'     => 'Ir a',
	'Send'     => 'Enviar',
	'Send to Customer'     => 'Enviar al Cliente',
	'Quick Send to Customer'     => 'Envío rápido',
	'Custom Send to Customer'    => 'Envío personalizado',
	'Send to Supplier'     => 'Enviar al Proveedor',
	'Sending...'     => 'Enviando...',
	'Send eMail'     => 'Enviar eMail',
	'Send by eMail'     => 'Enviar por eMail',
	'PDF Export'     => 'Exportar PDF',
	'Print'     => 'Imprimir',
	'Print selected Documents'     => 'Imprimir Documentos seleccionados',
	'Publish'     => 'Publicar',
	'Upload File'     => 'Subir Fichero',
	'Upload Image'     => 'Subir Imagen',
	'Browse'     => 'Seleccionar',
	'Help'     => 'Ayuda',
	'Home'     => 'Inicio',
	'Fetch' => 'Extraer',
	'View Data' => 'Ver Datos',
	'Fetch Data' => 'Extraer Datos',

	'Set on-hold'     => 'Click para poner en espera',
	'Unset on-hold'     => 'Click para liberar',

	'Document closed'     => 'Documento cerrado',
	'Close'     => 'Cerrar Documento',
	'Close Document'     => 'Click para Cerrar el Documento',
	'Unclose'     => 'Reabrir Documento',
	'Unclose Document'     => 'Click para Reabrir el documento',

	'Add New Item'     => 'Añadir Nuevo',
	'Add'     => 'Añadir',
	'Add New'     => 'Nuevo',
	'Add to Document'     => 'Añadir al Documento',

	''     => '',
	'Document'     => 'Documento',
	'Quotation'     => 'Presupuesto',
	'Order'     => 'Pedido',
	'Shipping Slip'     => 'Albarán',
	'Invoice'     => 'Factura',
	'Voucher'     => 'Recibo',
	'All Vouchers'     => 'Todos los Recibos',
	'Add Document'     => 'Añadir Documento',

	'View Carts'     => 'Ver Carritos',

	'File Attachments' => 'Ficheros Adjuntos',
	'Attachments' => 'Adjuntos',

	''     => '',

//	'Send eMail'     => 'Enviar Email',
	'Send an Email'     => 'Enviar un Email',
	'To (name)'     => 'Para (nombre)',
	'To (email)'     => 'Para (email)',
	''     => '',
	'Subject'     => 'Asunto',
	'Message'     => 'Mensaje',
	'Your Message'     => 'Su Mensaje',
	'From (name)'     => 'De (nombre)',
	'From (email)'     => 'De (email)',
	'Copy to (comma separated list of emails)' => 'Copia a (lista de emails separados por una coma)',

	'-- Please, select --'     => '-- Seleccione --',
	'-- Click to Select --'     => '-- Haga click para Seleccionar --',
	'-- All --'     => '-- Todos --',
	'-- None --'    => '-- Ninguno --',
	'-- Default --' => '-- Por defecto --',
	'All'     => 'Todos',
	'None'     => 'Ninguno',
	'Loading...'     => 'Cargando...',
	'Processing...'     => 'Procesando...',
	'Updating...' => 'Actualizando...',

	'You are going to PERMANENTLY delete a record. Are you sure?' => 'Está a punto de borrar PERMANENTEMENTE un registro. ¿Está seguro?',
	'You are going to delete a record. Are you sure?' => 'Está a punto de borrar un registro. ¿Está seguro?',
	'No records found' => 'No se han encontrado registros',

	'Found :nbr record(s)'     => 'Hay :nbr registro(s)',
	'Items per page'     => 'Registros por página',
	'Per page'     => 'Por página',
	'Show records:'     => 'Mostrar registros:',

	'This record has been already published with id=:id'     => 'Este registro ya se ha publicado en la Tienda Web con id=:id',

	'Drag to Sort.'     => 'Arrastre para reordenar.',
	'Use multiples of 10. Use other values to interpolate.' => 'Use múltiplos de 10. Use otros valores para intercalar.',

	'Draft'     => 'Borrador',
	'DRAFT'     => 'BORRADOR',
	'Pending'     => 'Pendiente',
	'NOT Saved'     => 'NO se ha Guardado',

	'Default?'     => '¿Principal?',


	/*
	|--------------------------------------------------------------------------
	| Common Language Lines :: Contact form
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Ask, Report an error or give us an Idea'  => 'Preguntar, Informar de un Error o Dar una Idea',
	'Your feed-back is welcome.'  => 'Sus Comentarios son muy valiosos para seguir desarrollando aBillander.',
	'Your Comments' => 'Su Mensaje',
	'Email'     => 'Correo Electrónico',
	'Name'     => 'Nombre',
	'The Comments field is required.'     => 'El campo Mensaje es requerido.',
	'There was an error. Your message could not be sent.'     => 'Se ha producido un error. Su mensaje no pudo enviarse.',
	'Your email has been sent!'     => 'Su email ¡ha sido enviado!',
	''     => '',
	''     => '',
	''     => '',


	/*
	|--------------------------------------------------------------------------
	| Auth Language Lines :: login
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Login'     => 'Login',
	'Please, log-in'     => 'Por favor, identifíquese',
	'<strong>Whoops!</strong> There were some problems with your input.'     => '<strong>Whoops!</strong> Hay algunos problemas con sus datos.',
	'E-Mail Address'     => 'Dirección de Correo',
	'Email'     => 'Correo Electrónico',
	'Password'     => 'Contraseña',
	'Confirm Password'     => 'Confirmar Contraseña',
	'Remember Me'     => 'Recuérdame',
	'Forgot Your Password?'     => '¿Olvidó su Contraseña?',
	'Send Password Reset Link.'     => 'Si olvidó la contraseña de acceso, introduzca su dirección de correo electrónico. Una vez enviado este formulario, recibirá en su correo las instrucciones para actualizar la contraseña.',
	'Reset Password'     => 'Cambiar Contraseña',
	'Customer Reset Password'     => 'Cambiar Contraseña para el Centro de Clientes',
	'Login information' => 'Datos de acceso',

	'Customers' => 'Clientes',
	'Customers - Create' => 'Clientes - Crear',
	'Main Data'     => 'Datos Generales',
	'New Customer'     => 'Nuevo Cliente',
	'Fiscal Name'     => 'Nombre Fiscal',
	'Commercial Name'     => 'Nombre Comercial',
	'Identification'     => 'NIF / CIF',
	'Your request has been sent. Check your email for further instructions.'     => 'Su solicitud ha sido enviada. Compruebe su correo electrónico para más instrucciones.',
	''     => '',
	''     => '',
	''     => '',
	''     => '',
	''     => '',


	/*
	|--------------------------------------------------------------------------
	| Series Language Lines :: Not Found
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'There is not any Sequence for this type of Document &#58&#58 You must create one first'    
	 => 'No existe ninguna Serie para este tipo de Documento &#58&#58 Debe crear una primero',

	'There is not any Payment Method &#58&#58 You must create one first'    
	 => 'No existe ninguna Forma de Pago &#58&#58 Debe crear una primero',


	/*
	|--------------------------------------------------------------------------
	| Calendar :: Months & Days
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'January' => 'Enero',
	'February' => 'Febrero',
	'March' => 'Marzo',
	'April' => 'Abril',
	'May' => 'Mayo',
	'June' => 'Junio',
	'July' => 'Julio',
	'August' => 'Agosto',
	'September' => 'Septiembre',
	'October' => 'Octubre',
	'November' => 'Noviembre',
	'December' => 'Diciembre',

//	'monthNames' => ['Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],

//	'dayNames' => ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],			// firstDay: 1,
              

];
