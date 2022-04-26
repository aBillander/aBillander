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

    'App\Models\Product.manual'  => 'Manual',  //  => manualy place manufacture or purchase orders
    'App\Models\Product.onorder' => 'Bajo Pedido',  //  => manufactured or purchased on order
    'App\Models\Product.reorder' => 'Punto de Pedido',  //  => Reorder Point Planning
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
    'template.SupplierShippingSlipPdf'   => 'Albarán de Proveedor (Pdf)',
	'template.SupplierInvoicePdf' => 'Factura de Proveedor (Pdf)',

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

	'App\Models\CustomerOrder.draft' => 'Borrador',
	'App\Models\CustomerOrder.confirmed'    => 'Confirmado',
	'App\Models\CustomerOrder.closed'    => 'Cerrado',
    'App\Models\CustomerOrder.canceled' => 'Cancelado',

	/*
	|--------------------------------------------------------------------------
	| Customer Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\CustomerShippingSlip.draft' => 'Borrador',
	'App\Models\CustomerShippingSlip.confirmed'    => 'Confirmado',
	'App\Models\CustomerShippingSlip.closed'    => 'Cerrado',
    'App\Models\CustomerShippingSlip.canceled' => 'Cancelado',

    'App\Models\CustomerShippingSlip.pending' => 'Pendiente',
    'App\Models\CustomerShippingSlip.processing' => 'Preparación',
    'App\Models\CustomerShippingSlip.transit' => 'Reparto',
    'App\Models\CustomerShippingSlip.exception' => 'Excepción',
    'App\Models\CustomerShippingSlip.delivered' => 'Entregado',

	/*
	|--------------------------------------------------------------------------
	| Warehouse Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\WarehouseShippingSlip.draft' => 'Borrador',
	'App\Models\WarehouseShippingSlip.confirmed'    => 'Confirmado',
	'App\Models\WarehouseShippingSlip.closed'    => 'Cerrado',
    'App\Models\WarehouseShippingSlip.canceled' => 'Cancelado',

    'App\Models\WarehouseShippingSlip.pending' => 'Pendiente',
    'App\Models\WarehouseShippingSlip.processing' => 'Preparación',
    'App\Models\WarehouseShippingSlip.transit' => 'Reparto',
    'App\Models\WarehouseShippingSlip.exception' => 'Excepción',
    'App\Models\WarehouseShippingSlip.delivered' => 'Entregado',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\Models\CustomerInvoice.invoice'    => 'Factura',
    'App\Models\CustomerInvoice.corrective' => 'Factura Rectificativa',
    'App\Models\CustomerInvoice.credit'     => 'Nota de Abono',
    'App\Models\CustomerInvoice.deposit'    => 'Anticipo',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\CustomerInvoice.draft'     => 'Borrador',
	'App\Models\CustomerInvoice.confirmed' => 'Confirmado',
	'App\Models\CustomerInvoice.closed'    => 'Cerrado',
    'App\Models\CustomerInvoice.canceled'  => 'Cancelado',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\CustomerInvoice.pending'  => 'Pendiente',
	'App\Models\CustomerInvoice.halfpaid' => 'Parcialmente Pagado',
	'App\Models\CustomerInvoice.paid'     => 'Pagado',
    'App\Models\CustomerInvoice.overdue'  => 'Vencido',
    'App\Models\CustomerInvoice.doubtful' => 'Pago Dudoso',
    'App\Models\CustomerInvoice.archived' => 'Archivado',


	/*
	|--------------------------------------------------------------------------
	| Supplier Order Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\SupplierOrder.draft' => 'Borrador',
	'App\Models\SupplierOrder.confirmed'    => 'Confirmado',
	'App\Models\SupplierOrder.closed'    => 'Cerrado',
    'App\Models\SupplierOrder.canceled' => 'Cancelado',    

    'App\Models\SupplierOrder.pending' => 'Pendiente',
    'App\Models\SupplierOrder.partial' => 'Parcialmente',
    'App\Models\SupplierOrder.received' => 'Recibido',

	/*
	|--------------------------------------------------------------------------
	| Supplier Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\SupplierShippingSlip.draft' => 'Borrador',
	'App\Models\SupplierShippingSlip.confirmed'    => 'Confirmado',
	'App\Models\SupplierShippingSlip.closed'    => 'Cerrado',
    'App\Models\SupplierShippingSlip.canceled' => 'Cancelado',

    'App\Models\SupplierShippingSlip.pending' => 'Pendiente',
    'App\Models\SupplierShippingSlip.processing' => 'Preparación',
    'App\Models\SupplierShippingSlip.transit' => 'Reparto',
    'App\Models\SupplierShippingSlip.exception' => 'Excepción',
    'App\Models\SupplierShippingSlip.delivered' => 'Entregado',

	/*
	|--------------------------------------------------------------------------
	| Supplier Invoice Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\Models\SupplierInvoice.invoice'    => 'Factura',
    'App\Models\SupplierInvoice.corrective' => 'Factura Rectificativa',
    'App\Models\SupplierInvoice.credit'     => 'Nota de Abono',
    'App\Models\SupplierInvoice.deposit'    => 'Anticipo',

	/*
	|--------------------------------------------------------------------------
	| Supplier Invoice Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\SupplierInvoice.draft'     => 'Borrador',
	'App\Models\SupplierInvoice.confirmed' => 'Confirmado',
	'App\Models\SupplierInvoice.closed'    => 'Cerrado',
    'App\Models\SupplierInvoice.canceled'  => 'Cancelado',

	/*
	|--------------------------------------------------------------------------
	| Supplier Invoice Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\SupplierInvoice.pending'  => 'Pendiente',
	'App\Models\SupplierInvoice.halfpaid' => 'Parcialmente Pagado',
	'App\Models\SupplierInvoice.paid'     => 'Pagado',
    'App\Models\SupplierInvoice.overdue'  => 'Vencido',
    'App\Models\SupplierInvoice.doubtful' => 'Pago Dudoso',
    'App\Models\SupplierInvoice.archived' => 'Archivado',

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

	'App\Models\Cheque.pending' => 'Pendiente',// Pendiente de depositar
    'App\Models\Cheque.deposited' => 'Depositado',	// Depositado
    'App\Models\Cheque.paid' => 'Pagado',			// or cleared: pagaddo (ingresado en el banco)
    'App\Models\Cheque.voided' => 'Anulado',		// Anulado
    'App\Models\Cheque.bounced' => 'Devuelto',		// or dishonored, or returned, or rejected
    'App\Models\Cheque.rejected' => 'Rechazado',		// or dishonored, or returned, or bounced

	/*
	|--------------------------------------------------------------------------
	| DownPayment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\DownPayment.pending' => 'Pendiente',
    'App\Models\DownPayment.applied' => 'Aplicado',

	/*
	|--------------------------------------------------------------------------
	| Commission Settlement Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\CommissionSettlement.pending' => 'Pendiente',
	'App\Models\CommissionSettlement.closed'    => 'Cerrado',

	/*
	|--------------------------------------------------------------------------
	| Shipping Method Billing Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\ShippingMethod.price'  => 'Precio',
	'App\Models\ShippingMethod.weight' => 'Peso',

	/*
	|--------------------------------------------------------------------------
	| Sales Representative Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\SalesRep.external'  => 'Agente Externo',
	'App\Models\SalesRep.employee' => 'Empleado',

	/*
	|--------------------------------------------------------------------------
	| Lot generators
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\Lot.Default' => 'Por defecto',
    'App\Models\Lot.LongCaducity' => 'Caducidad larga',
    'App\Models\Lot.ShortCaducity' => 'Caducidad corta',
    'App\Models\Lot.CaducityDate' => 'Fecha Caducidad',

	/*
	|--------------------------------------------------------------------------
	| Lot policies
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\Lot.FIFO' => 'Más antiguo primero',
    'App\Models\Lot.LIFO' => 'Más reciente primero',

	/*
	|--------------------------------------------------------------------------
	| Production Order Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Models\ProductionOrder.released'    => 'Lanzada',
    'App\Models\ProductionOrder.finished'  => 'Terminada',


/* **************************************************************************** */


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

    'App\Models\MeasureUnit.Quantity'     => 'Cantidad',
	'App\Models\MeasureUnit.Length'     => 'Longitud',
	'App\Models\MeasureUnit.Area'     => 'Superficie',
	'App\Models\MeasureUnit.Liquid Volume'     => 'Volumen (líquidos)',
	'App\Models\MeasureUnit.Dry Volume'     => 'Volumen (sólidos)',
	'App\Models\MeasureUnit.Mass'     => 'Masa',
	'App\Models\MeasureUnit.Time'     => 'Tiempo',


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
