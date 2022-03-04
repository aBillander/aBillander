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
	'virtual'    => 'Service', 		// one that doesn’t require shipping or stock management (Services, downloads...)
	'combinable' => 'Combinable', 		// a product with combitions, each of which may have a different SKU, price, stock option, etc.
	'grouped'    => 'Grouped',			// a collection of related products that can be purchased individually and only consist of simple products. 


	/*
	|--------------------------------------------------------------------------
	| Product Procurement Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'purchase'    => 'Purchase',				// Via Purchase Order.
	'manufacture' => 'Manufacture',			// Via Manufacturing Order.
	'none'        => 'None',				// One that doesn’t require shipping or stock management (Services, downloads...).
	'assembly'    => 'Assembly',		// Intermediate Product.


	/*
	|--------------------------------------------------------------------------
	| Product MRP Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\Product.manual'  => 'Manual',  //  => manualy place manufacture or purchase orders
    'App\Product.onorder' => 'On order',  //  => manufactured or purchased on order
    'App\Product.reorder' => 'Reorder point',  //  => Reorder Point Planning
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

    'sales' => 'Sales',	// Regular sales tax
    'sales_equalization' => 'Sales Equalization',	// Apply "Recargo de Equivalencia" (sales equalization tax in Spain and Belgium only). Vendors must charge these customers a sales equalization tax in addition to output tax. 


	/*
	|--------------------------------------------------------------------------
	| Document Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'Product' => 'Product', 
    'Customer' => 'Customer', 

    'CustomerQuotation' => 'Customer Quotation',
	'CustomerOrder' => 'Customer Order',
	'CustomerShippingSlip' => 'Customer Shipping Slip',
	'CustomerInvoice' => 'Customer Invoice',

    'SupplierQuotation' => 'Supplier Quotation',
	'SupplierOrder' => 'Supplier Order',
	'SupplierShippingSlip' => 'Supplier Shipping Slip',
	'SupplierInvoice' => 'Supplier Invoice',

    'StockCount' => 'Stock Count',
    'WarehouseShippingSlip' => 'Warehouse Shipping Slip',


	/*
	|--------------------------------------------------------------------------
	| Templateable Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'template.CustomerQuotationPdf'   => 'Customer Quotation (Pdf)',
    'template.CustomerOrderPdf'   => 'Customer Order (Pdf)',
    'template.CustomerShippingSlipPdf'   => 'Customer Shipping Slip (Pdf)',
	'template.CustomerInvoicePdf' => 'Customer Invoice (Pdf)',

    'template.WarehouseShippingSlipPdf' => 'Warehouse Shipping Slip (Pdf)',

    'template.SupplierOrderPdf'   => 'SupplierOrder (Pdf)',
    'template.SupplierShippingSlipPdf'   => 'Supplier Shipping Slip (Pdf)',
	'template.SupplierInvoicePdf' => 'Supplier Invoice (Pdf)',

    'template.Pdf' => 'Template Pdf',
    'template.Mail' => 'Template Mail',
	

	/*
	|--------------------------------------------------------------------------
	| Stock Movement Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	10 => 'Initial Stock',

	12 => 'Stock Adjustment',

	20 => 'Purchase Order',

	21 => 'Purchase return',

	30 => 'Sale order',

	31 => 'Sale return',

	40 => 'Transfer (Outbound)',

	41 => 'Transfer (Inbound)',

	50 => 'Manufacturing consumption',

	51 => 'Manufacturing return',

	55 => 'Manufacturing output',


	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Line Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'product' => 'Product', 
    'service' => 'Service', 
	'shipping' => 'Shipping',
    'discount' => 'Discount',
    'comment' => 'Comment', 


	/*
	|--------------------------------------------------------------------------
	| Customer Document Line Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'customerDocumentLine.product' => 'Product', 
    'customerDocumentLine.service' => 'Service', 
	'customerDocumentLine.shipping' => 'Shipping',
    'customerDocumentLine.discount' => 'Discount',
    'customerDocumentLine.comment' => 'Comment', 

	/*
	|--------------------------------------------------------------------------
	| Margin calculation methods
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'CST' => 'After Cost Price',	// Markup Percentage = (Sales Price – Unit Cost)/Unit Cost
	'PRC' => 'After Sales Price',	// Gross Margin Percentage = (Gross Profit/Sales Price) X 100

	/*
	|--------------------------------------------------------------------------
	| Document rounding methods
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'rounding.line' => 'Rounding by line',
	'rounding.total' => 'Rounding in total',
	'rounding.none' => 'No rounding',
	'rounding.custom' => 'Custom',

	/*
	|--------------------------------------------------------------------------
	|Price input methods
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

//	'Prices are entered inclusive of tax' => 'Los Precios se introducen con el IVA incluido',	//  I will enter prices inclusive of tax
//	'Prices are entered exclusive of tax' => 'Los Precios se introducen con el IVA excluido',	//  I will enter prices exclusive of tax

	/*
	|--------------------------------------------------------------------------
	| Price List Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'price' => 'Fixed Price',
	'discount' => 'Discount percentage',
	'margin' => 'Margin percentage',

	/*
	|--------------------------------------------------------------------------
	| Customer Order Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerOrder.draft' => 'Draft',
	'App\CustomerOrder.confirmed'    => 'Confirmed',
	'App\CustomerOrder.closed'    => 'Closed',
    'App\CustomerOrder.canceled' => 'Canceled',

	/*
	|--------------------------------------------------------------------------
	| Customer Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerShippingSlip.draft' => 'Draft',
	'App\CustomerShippingSlip.confirmed'    => 'Confirmed',
	'App\CustomerShippingSlip.closed'    => 'Closed',
    'App\CustomerShippingSlip.canceled' => 'Canceled',

    'App\CustomerShippingSlip.pending' => 'Pending',
    'App\CustomerShippingSlip.processing' => 'Processing',
    'App\CustomerShippingSlip.transit' => 'Transit',
    'App\CustomerShippingSlip.exception' => 'Exception',
    'App\CustomerShippingSlip.delivered' => 'Delivered',

	/*
	|--------------------------------------------------------------------------
	| Warehouse Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\WarehouseShippingSlip.draft' => 'Draft',
	'App\WarehouseShippingSlip.confirmed'    => 'Confirmed',
	'App\WarehouseShippingSlip.closed'    => 'Closed',
    'App\WarehouseShippingSlip.canceled' => 'Canceled',

    'App\WarehouseShippingSlip.pending' => 'Pending',
    'App\WarehouseShippingSlip.processing' => 'Processing',
    'App\WarehouseShippingSlip.transit' => 'Transit',
    'App\WarehouseShippingSlip.exception' => 'Exception',
    'App\WarehouseShippingSlip.delivered' => 'Delivered',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\CustomerInvoice.invoice'    => 'Invoice',
    'App\CustomerInvoice.corrective' => 'Corrective Invoice',
    'App\CustomerInvoice.credit'     => 'Credit Note',
    'App\CustomerInvoice.deposit'    => 'Deposit',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerInvoice.draft'     => 'Draft',
	'App\CustomerInvoice.confirmed' => 'Confirmed',
	'App\CustomerInvoice.closed'    => 'Closed',
    'App\CustomerInvoice.canceled'  => 'Canceled',

	/*
	|--------------------------------------------------------------------------
	| Customer Invoice Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CustomerInvoice.pending'  => 'Pending',
	'App\CustomerInvoice.halfpaid' => 'Half Paid',
	'App\CustomerInvoice.paid'     => 'Paid',
    'App\CustomerInvoice.overdue'  => 'Overdue',
    'App\CustomerInvoice.doubtful' => 'Doubtful',
    'App\CustomerInvoice.archived' => 'Archived',


	/*
	|--------------------------------------------------------------------------
	| Supplier Order Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SupplierOrder.draft' => 'Draft',
	'App\SupplierOrder.confirmed'    => 'Confirmed',
	'App\SupplierOrder.closed'    => 'Closed',
    'App\SupplierOrder.canceled' => 'Canceled',    

    'App\SupplierOrder.pending' => 'Pending',
    'App\SupplierOrder.partial' => 'Partial',
    'App\SupplierOrder.received' => 'Received',

	/*
	|--------------------------------------------------------------------------
	| Supplier Shipping Slip Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SupplierShippingSlip.draft' => 'Draft',
	'App\SupplierShippingSlip.confirmed'    => 'Confirmed',
	'App\SupplierShippingSlip.closed'    => 'Closed',
    'App\SupplierShippingSlip.canceled' => 'Canceled',

    'App\SupplierShippingSlip.pending' => 'Pending',
    'App\SupplierShippingSlip.processing' => 'Processing',
    'App\SupplierShippingSlip.transit' => 'Transit',
    'App\SupplierShippingSlip.exception' => 'Exception',
    'App\SupplierShippingSlip.delivered' => 'Delivered',

	/*
	|--------------------------------------------------------------------------
	| Supplier Invoice Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\SupplierInvoice.invoice'    => 'Invoice',
    'App\SupplierInvoice.corrective' => 'Corrective Invoice',
    'App\SupplierInvoice.credit'     => 'Credit Note',
    'App\SupplierInvoice.deposit'    => 'Deposit',

	/*
	|--------------------------------------------------------------------------
	| Supplier Invoice Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SupplierInvoice.draft'     => 'Draft',
	'App\SupplierInvoice.confirmed' => 'Confirmed',
	'App\SupplierInvoice.closed'    => 'Closed',
    'App\SupplierInvoice.canceled'  => 'Canceled',

	/*
	|--------------------------------------------------------------------------
	| Supplier Invoice Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SupplierInvoice.pending'  => 'Pending',
	'App\SupplierInvoice.halfpaid' => 'Half Paid',
	'App\SupplierInvoice.paid'     => 'Paid',
    'App\SupplierInvoice.overdue'  => 'Overdue',
    'App\SupplierInvoice.doubtful' => 'Doubtful',
    'App\SupplierInvoice.archived' => 'Archived',

	/*
	|--------------------------------------------------------------------------
	| Payment Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'receivable' => 'Collection',
	'payable'    => 'Payment',

	/*
	|--------------------------------------------------------------------------
	| Payment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'pending' => 'Pending',
	'bounced' => 'Bounced',
	'paid'    => 'Paid',
	'uncollectible' => 'Uncollectible',

	/*
	|--------------------------------------------------------------------------
	| Cheque Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\Cheque.pending' => 'Pending',// Pendiente de depositar
    'App\Cheque.deposited' => 'Deposited',	// Depositado
    'App\Cheque.paid' => 'Paid',			// or cleared: pagaddo (ingresado en el banco)
    'App\Cheque.voided' => 'Voided',		// Anulado
    'App\Cheque.bounced' => 'Bounced',		// or dishonored, or returned, or rejected
    'App\Cheque.rejected' => 'Rejected',		// or dishonored, or returned, or bounced

	/*
	|--------------------------------------------------------------------------
	| DownPayment Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\DownPayment.pending' => 'Pending',
    'App\DownPayment.applied' => 'Applied',

	/*
	|--------------------------------------------------------------------------
	| Commission Settlement Statuses
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\CommissionSettlement.pending' => 'Pending',
	'App\CommissionSettlement.closed'    => 'Closed',

	/*
	|--------------------------------------------------------------------------
	| Shipping Method Billing Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\ShippingMethod.price'  => 'Price',
	'App\ShippingMethod.weight' => 'Weight',

	/*
	|--------------------------------------------------------------------------
	| Sales Representative Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'App\SalesRep.external'  => 'External',
	'App\SalesRep.employee' => 'Employee',


	/*
	|--------------------------------------------------------------------------
	| Paper Orientation Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'Portrait' => 'Portrait',
	'Landscape' => 'Landscape',
	'portrait' => 'Portrait',
	'landscape' => 'Landscape',
	
	'orientation.portrait' => 'Portrait',
	'orientation.landscape' => 'Landscape',


	/*
	|--------------------------------------------------------------------------
	| Measure Unit Types
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

    'App\MeasureUnit.Quantity'     => 'Quantity',
	'App\MeasureUnit.Length'     => 'Length',
	'App\MeasureUnit.Area'     => 'Area',
	'App\MeasureUnit.Liquid Volume'     => 'Liquid Volume',
	'App\MeasureUnit.Dry Volume'     => 'Dry Volume',
	'App\MeasureUnit.Mass'     => 'Mass',
	'App\MeasureUnit.Time'     => 'Time',


	/*
	|--------------------------------------------------------------------------
	| Months
	|--------------------------------------------------------------------------
	|
	| .
	|
	*/

	'monthNames' => [
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
	]

//	'dayNames' => ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],			// firstDay: 1,
	
);
