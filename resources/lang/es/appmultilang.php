<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Product Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'simple'     => 'Simple', 			// a collection of related products that can be purchased individually and only consist of simple products. Simple products are shipped and have no combitions.
	'virtual'    => 'Servicio', 		// one that doesn’t require shipping or stock management (Services, downloads...)
	'combinable' => 'Combinable', 		// a product with combitions, each of which may have a different SKU, price, stock option, etc.
	'grouped'    => 'Agrupado',			// a collection of related products that can be purchased individually and only consist of simple products. 


	/*
	|--------------------------------------------------------------------------
	| Product Procurement Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'purchase'    => 'Compras',				// Via Purchase Order.
	'manufacture' => 'Fabricación',			// Via Manufacturing Order.
	'none'        => 'Ninguno',				// One that doesn’t require shipping or stock management (Services, downloads...).
	'assembly'    => 'Semi-Elaborado',		// Intermediate Product.


	/*
	|--------------------------------------------------------------------------
	| Tax Rule Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'sales' => 'Ventas',	// Regular sales tax
    'sales_equalization' => 'Recargo de Equivalencia',	// Apply "Recargo de Equivalencia" (sales equalization tax in Spain and Belgium only). Vendors must charge these customers a sales equalization tax in addition to output tax. 


	/*
	|--------------------------------------------------------------------------
	| Document Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'Product' => 'Producto', 
    'Customer' => 'Cliente', 
	'CustomerOrder' => 'Pedido de Cliente',
	'CustomerInvoice' => 'Factura de Cliente',
    'StockCount' => 'Inventario de Almacén',


	/*
	|--------------------------------------------------------------------------
	| Templateable Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'template.CustomerOrderPdf'   => 'Pedido de Cliente (Pdf)',
	'template.CustomerInvoicePdf' => 'Factura de Cliente (Pdf)',
    'template.Pdf' => 'Plantilla Pdf',
    'template.Mail' => 'Plantilla Mail',
	

	/*
	|--------------------------------------------------------------------------
	| Stock Movement Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	10 => 'Stock inicial',

	12 => 'Ajuste de stock',

	20 => 'Orden de compra',

	21 => 'Devolución de compras',

	30 => 'Orden de venta',

	31 => 'Devolución de ventas',

	40 => 'Transferencia (Salida)',

	41 => 'Transferencia (Entrada)',

	50 => 'Consumo de fabricación',

	51 => 'Devolución de fabricación',

	55 => 'Producto de fabricación',


	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Line Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'product' => 'Producto', 
    'service' => 'Servicio', 
	'shipping' => 'Envío',
    'discount' => 'Descuento',
    'comment' => 'Comentario', 


	/*
	|--------------------------------------------------------------------------
	| Customer Document Line Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'customerDocumentLine.product' => 'Producto', 
    'customerDocumentLine.service' => 'Servicio', 
	'customerDocumentLine.shipping' => 'Envío',
    'customerDocumentLine.discount' => 'Descuento',
    'customerDocumentLine.comment' => 'Comentario', 

	/*
	|--------------------------------------------------------------------------
	| Margin calculation methods
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'CST' => 'Sobre el Precio de Coste',	// Markup Percentage = (Sales Price – Unit Cost)/Unit Cost
	'PRC' => 'Sobre el Precio de Venta',	// Gross Margin Percentage = (Gross Profit/Sales Price) X 100

	/*
	|--------------------------------------------------------------------------
	|Price input methods
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Prices are entered inclusive of tax' => 'Los Precios se introducen con el IVA incluido',	//  I will enter prices inclusive of tax
	'Prices are entered exclusive of tax' => 'Los Precios se introducen con el IVA excluido',	//  I will enter prices exclusive of tax

	/*
	|--------------------------------------------------------------------------
	| Price List Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'price' => 'Precio fijo',
	'discount' => 'Porcentaje de descuento',
	'margin' => 'Porcentaje de margen',

	/*
	|--------------------------------------------------------------------------
	| Customer Order Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'customerOrder.draft' => 'Borrador',
	'customerOrder.confirmed'    => 'Confirmado',
	'customerOrder.closed'    => 'Cerrado',
    'customerOrder.canceled' => 'Cancelado',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'draft' => 'Borrador',
	'pending' => 'Pendiente',
	'halfpaid'    => 'Parcialmente Pagado',
	'paid'    => 'Pagado',
    'overdue' => 'Vencido',
    'doubtful'    => 'Pago Dudoso',
    'archived' => 'Archivado',

	/*
	|--------------------------------------------------------------------------
	| Payment Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'receivable' => 'Cobro',
	'payable'    => 'Pago',

	/*
	|--------------------------------------------------------------------------
	| Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'pending' => 'Pendiente',
	'bounced' => 'Devuelto',
	'paid'    => 'Pagado',

	/*
	|--------------------------------------------------------------------------
	| Paper Orientation Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Portrait' => 'Vertical',
	'Landscape' => 'Horizontal',
	'portrait' => 'Vertical',
	'landscape' => 'Horizontal',
	
	'orientation.portrait' => 'Vertical',
	'orientation.landscape' => 'Horizontal',


	/*
	|--------------------------------------------------------------------------
	| Measure Unit Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'Quantity'     => 'Cantidad',
	'Length'     => 'Longitud',
	'Area'     => 'Superficie',
	'Liquid Volume'     => 'Volumen (líquidos)',
	'Dry Volume'     => 'Volumen (sólidos)',
	'Mass'     => 'Masa',
	'Time'     => 'Tiempo',


	/*
	|--------------------------------------------------------------------------
	| Months
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'monthNames' => [
			'Enero',
			'Febrero',
			'Marzo',
			'Abril',
			'Mayo',
			'Junio',
			'Julio',
			'Agosto',
			'Septiembre',
			'Octubre',
			'Noviembre',
			'Diciembre',
	]

//	'dayNames' => ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],			// firstDay: 1,
	
);
