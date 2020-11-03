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
	| Product MRP Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\Product.manual'  => 'Manual',  //  => manualy place manufacture or purchase orders
    'App\Product.onorder' => 'Bajo Pedido',  //  => manufactured or purchased on order
    'App\Product.reorder' => 'Punto de Pedido',  //  => Reorder Point Planning
    // 'forecast' => Forecast Based Planning
    // 'phased'   => Time-phased Planning (planning cycles)


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

    'CustomerQuotation' => 'Presupuesto de Cliente',
	'CustomerOrder' => 'Pedido de Cliente',
	'CustomerShippingSlip' => 'Albarán de Cliente',
	'CustomerInvoice' => 'Factura de Cliente',

    'SupplierQuotation' => 'Presupuesto de Proveedor',
	'SupplierOrder' => 'Pedido a Proveedor',
	'SupplierShippingSlip' => 'Albarán de Proveedor',
	'SupplierInvoice' => 'Factura de Proveedor',

    'StockCount' => 'Inventario de Almacén',
    'WarehouseShippingSlip' => 'Transferencias entre Almacenes',


	/*
	|--------------------------------------------------------------------------
	| Templateable Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'template.CustomerQuotationPdf'   => 'Presupuesto de Cliente (Pdf)',
    'template.CustomerOrderPdf'   => 'Pedido de Cliente (Pdf)',
    'template.CustomerShippingSlipPdf'   => 'Albarán de Cliente (Pdf)',
	'template.CustomerInvoicePdf' => 'Factura de Cliente (Pdf)',
    'template.WarehouseShippingSlipPdf' => 'Transferencias entre Almacenes (Pdf)',
    'template.SupplierOrderPdf'   => 'Pedido a Proveedor (Pdf)',
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
	| Document rounding methods
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'rounding.line' => 'Redondeo por linea',
	'rounding.total' => 'Redondeo en el total',
	'rounding.none' => 'Sin redondeo',
	'rounding.custom' => 'Personalizado',

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

	'App\CustomerOrder.draft' => 'Borrador',
	'App\CustomerOrder.confirmed'    => 'Confirmado',
	'App\CustomerOrder.closed'    => 'Cerrado',
    'App\CustomerOrder.canceled' => 'Cancelado',

	/*
	|--------------------------------------------------------------------------
	| Customer Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerShippingSlip.draft' => 'Borrador',
	'App\CustomerShippingSlip.confirmed'    => 'Confirmado',
	'App\CustomerShippingSlip.closed'    => 'Cerrado',
    'App\CustomerShippingSlip.canceled' => 'Cancelado',

    'App\CustomerShippingSlip.pending' => 'Pendiente',
    'App\CustomerShippingSlip.processing' => 'Preparación',
    'App\CustomerShippingSlip.transit' => 'Reparto',
    'App\CustomerShippingSlip.exception' => 'Excepción',
    'App\CustomerShippingSlip.delivered' => 'Entregado',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\CustomerInvoice.invoice'    => 'Factura',
    'App\CustomerInvoice.corrective' => 'Factura Rectificativa',
    'App\CustomerInvoice.credit'     => 'Nota de Abono',
    'App\CustomerInvoice.deposit'    => 'Anticipo',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerInvoice.draft'     => 'Borrador',
	'App\CustomerInvoice.confirmed' => 'Confirmado',
	'App\CustomerInvoice.closed'    => 'Cerrado',
    'App\CustomerInvoice.canceled'  => 'Cancelado',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerInvoice.pending'  => 'Pendiente',
	'App\CustomerInvoice.halfpaid' => 'Parcialmente Pagado',
	'App\CustomerInvoice.paid'     => 'Pagado',
    'App\CustomerInvoice.overdue'  => 'Vencido',
    'App\CustomerInvoice.doubtful' => 'Pago Dudoso',
    'App\CustomerInvoice.archived' => 'Archivado',


	/*
	|--------------------------------------------------------------------------
	| Supplier Order Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SupplierOrder.draft' => 'Borrador',
	'App\SupplierOrder.confirmed'    => 'Confirmado',
	'App\SupplierOrder.closed'    => 'Cerrado',
    'App\SupplierOrder.canceled' => 'Cancelado',    

    'App\SupplierShippingSlip.pending' => 'Pendiente',
    'App\SupplierShippingSlip.partial' => 'Parcialmente',
    'App\SupplierShippingSlip.received ' => 'Recibido',

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
	'uncollectible' => 'Incobrable',

	/*
	|--------------------------------------------------------------------------
	| Cheque Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Cheque.pending' => 'Pendiente',// Pendiente de depositar
    'App\Cheque.deposited' => 'Depositado',	// Depositado
    'App\Cheque.paid' => 'Pagado',			// or cleared: pagaddo (ingresado en el banco)
    'App\Cheque.voided' => 'Anulado',		// Anulado
    'App\Cheque.rejected' => 'Rechazado',		// or dishonored, or returned, or bounced

	/*
	|--------------------------------------------------------------------------
	| Commission Settlement Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CommissionSettlement.pending' => 'Pendiente',
	'App\CommissionSettlement.closed'    => 'Cerrado',

	/*
	|--------------------------------------------------------------------------
	| Shipping Method Billing Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\ShippingMethod.price'  => 'Precio',
	'App\ShippingMethod.weight' => 'Peso',

	/*
	|--------------------------------------------------------------------------
	| Sales Representative Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SalesRep.external'  => 'Agente Externo',
	'App\SalesRep.employee' => 'Empleado',


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
