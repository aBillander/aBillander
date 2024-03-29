<?php 

namespace App\Traits;

use App\Models\ActivityLogger;
use App\Models\Configuration;
use App\Helpers\DocumentAscription;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceLine;
use App\Models\SupplierInvoiceLineTax;
use App\Models\SupplierShippingSlip;
use App\Models\Tax;

trait SupplierShippingSlipInvoiceableTrait
{

    /**
     * Create an Invoice after a list of Supplier Shippiong Slips. 
     *
     * @param  $list  : list of Id's of Supplier Shippiong Slips
     * @param  $params[] : array of params
     *                      Most used: 'group_by_supplier', 'group_by_shipping_address', 'document_date', 'status'
     *                      Aditional: 'sequence_id', 'template_id', 'created_via', 'payment_method_id', 
     * @return 
     */
    public static function invoiceDocumentList( $list, $params )
    {
        if ( array_key_exists('logger', $params) && $params['logger'] )
        {
            // So far, so good. We have a Logger

        } else {
            // Start a Logger, and propagate
            $logger = ActivityLogger::setup( 'Invoice Some Shipping Slips', __METHOD__ );
//                        ->backTo( route('productionsheet.shippingslips', $params['production_sheet_id']) );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


            $logger->empty();

            $logger->log("ERROR", 'No se ha podido establecer el punto de llamada para este Logger');

            $params['logger'] = $logger;
        }

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

//            $supplier = Supplier::
//                                  with('currency')
//                                ->findOrFail($params['supplier_id']);

            $documents = SupplierShippingSlip::
//                                  where('supplier_id', $params['supplier_id'])
                                  where('status', 'closed')
//                                ->where('is_invoiceable', '>', 0)
                                ->where('invoiced_at', null)
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('supplier')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
                                ->orderBy('document_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->find( $list );
    //                            ->findOrFail( $list );

            // abi_r($documents, true);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return null;

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }


        // Check Currency Conversion Rate. Must be the same for Shipping Slips bundled into a Invoice
        $rates = $documents->unique('currency_conversion_rate')->pluck('currency_conversion_rate')->all();  // Array

        if( count($rates) > 1 )
        {
            $list = $documents->pluck('id')->all();

            $params['logger']->log("ERROR", 'No todos los Albaranes tienen el mismo tipo de cambio: '.implode(', ', $list));

            return [
                'error' => l('Records in the list [ :id ] are not groupable, because ":field" is not the same. ', ['id' => implode(', ', $list), 'field' => 'currency_conversion_rate'], 'layouts')
            ];
        }


        return SupplierShippingSlip::invoiceDocumentCollection( $documents, $params );
    }

    

    /**
     * Create an Invoice after a collection of Supplier Shippiong Slips. 
     * Group Shippiong Slips by Supplier. 
     *
     * @param  $documents  : collectiom of Supplier Shippiong Slips
     * @param  $params[] : array of params
     * @return SupplierInvoice Object
     */
    public static function invoiceDocumentCollection( $documents, $params )
    {
        if ( array_key_exists('logger', $params) && $params['logger'] )
        {
            // So far, so good. We have a Logger

        } else {
            // Start a Logger, and propagate
            $logger = ActivityLogger::setup( 'Invoice Some Shipping Slips', __METHOD__ );
//                        ->backTo( route('productionsheet.shippingslips', $params['production_sheet_id']) );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


            $logger->empty();

            $logger->log("ERROR", 'No se ha podido establecer el punto de llamada para este Logger');

            $params['logger'] = $logger;
        }

        $logger = $params['logger'];

        // Group Shippiong Slips by Supplier.
        $suppliers  = $documents->unique('supplier_id')->pluck('supplier_id')->all();
        $currencies = $documents->unique('currency_id')->pluck('currency_id')->all();   // Should be only one
        $rates      = $documents->unique('currency_conversion_rate')->pluck('currency_conversion_rate')->all();   // Should be only one

        foreach ($suppliers as $supplier_id) {
            # code...
            $documents_by_cid = $documents->where('supplier_id', $supplier_id);

            $extra_params = [
                   
                        'supplier_id'              => $supplier_id,
                        'currency_id'              => $currencies[0],
                        'currency_conversion_rate' => $rates[0],
                    ];


            $logger->log("INFO", 'Comienza la facturación de la colección de los Albaranes del Proveedor: <span class="log-showoff-format">{supplier}</span> .', ['supplier' => $supplier_id]);

            // Should group? i.e.: One invoice per Supplier?
            if ( array_key_exists('group_by_supplier', $params) && ( $params['group_by_supplier'] == 0 ) ) {

                // Every single document

                foreach ($documents_by_cid as $document) {
                    # code...
                    // Select Documents
                    $documents_by_doc = collect($document);

                    $logger->log("INFO", 'Se facturará el Albarán: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $documents_by_doc->pluck('id')->all())]);

                    SupplierShippingSlip::invoiceDocumentsBySupplier( $documents_by_doc, $params + $extra_params );
                }

            } else {

                $logger->log("INFO", 'Se facturarán agrupados los Albaranes: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $documents_by_cid->pluck('id')->all())]);

                SupplierShippingSlip::invoiceDocumentsBySupplier( $documents_by_cid, $params + $extra_params );
            }
        }

    }

    

    /**
     * Create an Invoice after a collection of Supplier Shippiong Slips. 
     * ALL Supplier Shippiong Slips belongs to the same Supplier. 
     * Group Supplier Shippiong Slips according to some criteria. 
     *
     * @param  $documents  : collectiom of Supplier Shippiong Slips
     * @param  $params[] : array of params
     * @return SupplierInvoice Object
     */
    public static function invoiceDocumentsBySupplier( $documents, $params )
    {
        $logger = $params['logger'];

        // Pre-process Documents

        foreach ($documents as $document)
        {
            # code...
            $document->payment_method_id = $document->getPaymentMethodId();
        }

        // Group by payment method
        $pmethods = $documents->unique('payment_method_id')->pluck('payment_method_id')->all();

        foreach ($pmethods as $payment_method_id) {
            
            $logger->log("INFO", 'Comienza la facturación de Albaranes del Proveedor por Método de Pago: <span class="log-showoff-format">{supplier}</span> .', ['supplier' => $payment_method_id]);

            # code...
            // Select Documents
            $documents_by_pm = $documents->where('payment_method_id', $payment_method_id);

            $params['payment_method_id'] = $payment_method_id;

            // Should group by Shipping Address?
            if ( array_key_exists('group_by_shipping_address', $params) && ($params['group_by_shipping_address'] > 0) ) {

                // Adresses
                $addresses = $documents_by_pm->unique('shipping_address_id')->pluck('shipping_address_id')->all();

                foreach ($addresses as $address_id) {
                    # code...
                    // Select Documents
                    $documents_by_pm_by_addrr = $documents_by_pm->where('shipping_address_id', $address_id);

                    $logger->log("INFO", 'Se facturarán los Albaranes: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $documents_by_pm_by_addrr->pluck('id')->all())]);

                    SupplierShippingSlip::invoiceSupplierDocuments( $documents_by_pm_by_addrr, $params );
                }

            } else {

                $logger->log("INFO", 'Se facturarán los Albaranes: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $documents_by_pm->pluck('id')->all())]);

                SupplierShippingSlip::invoiceSupplierDocuments( $documents_by_pm, $params );
            }
        }


    }

    

    /**
     * Create an Invoice after a collection of Supplier Shippiong Slips. 
     * ALL Supplier Shippiong Slips belongs to the same Supplier. 
     *
     * @param  $documents  : collectiom of Supplier Shippiong Slips
     * @param  $params['supplier_id'] : Supplier Id of ALL Supplier Shippiong Slips
     * @return SupplierInvoice Object
     */
    public static function invoiceSupplierDocuments( $documents, $params )
    {
        $logger = $params['logger'];

        // abi_r($params, true);

        // abi_r($params);die();

//        1.- Recuperar los documntos. Skip not invoiceable
        $documents = $documents->reject(function ($item, $key) {
                                    return $item->is_invoiceable == 0;
                                });

//        2.- Comprobar que están todos los de la lista ( comparando count() )

        $supplier = Supplier::
                              with('currency')
                            ->findOrFail($params['supplier_id']);


//        4.- Cear cabecera

        // Header
        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'supplier_id' => $supplier->id,
//            'user_id' => $this->,

            'sequence_id' => array_key_exists('sequence_id', $params) ?
                                    $params['sequence_id'] : $supplier->getInvoiceSequenceId(),

            'created_via' => array_key_exists('created_via', $params) ?
                                    $params['created_via'] : 'aggregate_shipping_slips',

            'document_date' => array_key_exists('document_date', $params) ?
                                    $params['document_date'] : \Carbon\Carbon::now()->toDateString(),

            'currency_conversion_rate' => array_key_exists('currency_conversion_rate', $params) ?
                                    $params['currency_conversion_rate'] : $supplier->currency->conversion_rate,
//            'down_payment' => $this->down_payment,

            'document_discount_percent' => 0.0,

            'document_ppd_percent'      => 0.0,


            'total_currency_tax_incl' => $documents->sum('total_currency_tax_incl'),
            'total_currency_tax_excl' => $documents->sum('total_currency_tax_excl'),
//            'total_currency_paid' => $this->total_currency_paid,

            'total_tax_incl' => $documents->sum('total_tax_incl'),
            'total_tax_excl' => $documents->sum('total_tax_excl'),

//            'commission_amount' => $this->commission_amount,

            // Skip notes

            'status' => 'draft',
            'onhold' => 0,
            'locked' => 0,

            'invoicing_address_id' => $supplier->invoicing_address_id,
//            'shipping_address_id' => $this->shipping_address_id,
//            'warehouse_id' => $this->warehouse_id,
//            'shipping_method_id' => $this->shipping_method_id ?? $this->supplier->shipping_method_id ?? Configuration::getInt('DEF_SUPPLIER_SHIPPING_METHOD'),
//            'carrier_id' => $this->carrier_id,
//            'sales_rep_id' => $supplier->sales_rep_id,
            'currency_id' => array_key_exists('currency_id', $params) ?
                                    $params['currency_id'] : $supplier->currency->id,
            'payment_method_id' => array_key_exists('payment_method_id', $params) ?
                                    $params['payment_method_id'] : $supplier->getPaymentMethodId(),
            'template_id' => array_key_exists('template_id', $params) ?
                                    $params['template_id'] : $supplier->getInvoiceTemplateId(),
        ];

        // Model specific data
        $extradata = [
            'type' => 'invoice',
            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];
/*
        if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
        {
            if ( array_key_exists('production_sheet_id', $params) && ( $params['production_sheet_id'] > 0 ) )
            {
                $extradata = $extradata + ['production_sheet_id' => $params['production_sheet_id']];
            }
        }
*/

        // Let's get dirty
//        SupplierInvoice::unguard();
        $invoice = SupplierInvoice::create( $data + $extradata );
//        SupplierInvoice::reguard();


//        5a.- Añadir Albarán
//        5b.- Crear enlaces para trazabilidad de documentos
        // Initialize grouped lines collection
        // $grouped_lines = DocumentLine::whereIn($this->getParentModelSnakeCase().'_id', $list)->get();

        // Initialize totals
        $total_currency_tax_incl = 0;
        $total_currency_tax_excl = 0;
        $total_tax_incl = 0;
        $total_tax_excl = 0;

        // Handy:
        $alltaxes = Tax::get()->sortByDesc('percent');

        // Initialize line sort order
        $i = 0;

        foreach ($documents as $document) {
            # code...
            $i++;

            //
            // Text line announces Shipping Slip
            //
            $line_data = [
                'line_sort_order' => $i*10, 
                'line_type' => 'comment', 
                'name' => l('Shipping Slip: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)], 'suppliershippingslips'),
//                'product_id' => , 
//                'combination_id' => , 
                'reference' => $document->document_reference, 
//                'name', 
                'quantity' => 1, 
                'measure_unit_id' => Configuration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS'),
//                    'cost_price', 'unit_price', 'unit_supplier_price', 
//                    'prices_entered_with_tax',
//                    'unit_supplier_final_price', 'unit_supplier_final_price_tax_inc', 
//                    'unit_final_price', 'unit_final_price_tax_inc', 
//                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                'total_tax_incl' => 0,
                'total_tax_excl' => 0,
//                    'tax_percent', 'commission_percent', 
                'notes' => '', 
                'locked' => 0,
 //                 'supplier_shipping_slip_id',
                'tax_id' => Configuration::get('DEF_TAX'),  // Just convenient
 //               'sales_rep_id'
                'supplier_shipping_slip_id' => $document->id,
            ];

            $invoice_line = SupplierInvoiceLine::create( $line_data );

            $invoice->lines()->save($invoice_line);

            //
            // Document Discounts
            //
            if (    ( $document->document_discount_percent != 0.0 ) || 
                    ( $document->document_ppd_percent      != 0.0 )
                ) 
            {
                $documentlines = $document->documentlines;
                $taxlines = $document->documentlinetaxes;
                $reduction =  (1.0 - $document->document_discount_percent/100.0) * (1.0 - $document->document_ppd_percent/100.0);
                $currency = $document->currency;

                foreach ($alltaxes as $alltax) 
                {
                    # code...                
                    $lines = $taxlines->where('tax_id', $alltax->id);

                    if ( $lines->count() == 0 ) continue;

                    $taxbase = $documentlines->where('tax_id', $alltax->id)->sum('total_tax_excl');
                    $discountByTax = $taxbase * (1.0 - $reduction);

                    // abi_r($taxbase .' - '.$discountByTax);

                    $i++;

                    $product_id     = null;
                    $combination_id = null;
                    $quantity       = 1.0;

                    $name = l('Shipping Slip: :id [dicount Tax :percent %]', ['id' => $document->document_reference, 'percent' => $alltax->as_percentable( $alltax->percent )], 'suppliershippingslips');

                    // Create Discount Line
                    $line_data = [
                        'line_type' => 'discount',
                        'name' => $name,
    //                    'prices_entered_with_tax' => $document->supplier->currentPricesEnteredWithTax( $document->document_currency ),
                        'prices_entered_with_tax' => 0,
                        'cost_price' => -$discountByTax,
                        'unit_price' => -$discountByTax,
                        'discount_percent' => 0.0,
                        'unit_supplier_price' => -$discountByTax,
                        'unit_supplier_final_price' => -$discountByTax,
                        'tax_id' => $alltax->id,
                        'sales_equalization' => $supplier->sales_equalization,

                        'line_sort_order' => $i*10,
                        'notes' => '',

//                        'supplier_shipping_slip_id' => $document->id,
                    ];

                    $invoice_line = $invoice->addSupplierServiceLine( $product_id, $combination_id, $quantity, $line_data );

                    $invoice_line->update(['supplier_shipping_slip_id' => $document->id]);
                }
            }


            //
            // Add current Shipping Slip lines to Invoice
            //
            foreach ($document->lines as $line) {
                # code...
                $i++;

                // $invoice_line = $line->toInvoiceLine();

                // Common data
                $data = [
                ];

                $data = $line->toArray();
                // id
                unset( $data['id'] );
                // Parent document
                // unset( $data[$this->getParentModelSnakeCase().'_id'] );
                unset( $data['supplier_shipping_slip_id'] );
                // Dates
                unset( $data['created_at'] );
                unset( $data['deleted_at'] );
                // linetaxes
                unset( $data['linetaxes'] );
                // Sort order
                $data['line_sort_order'] = $i*10; 
                // Locked 
                $data['locked'] = ( $line->line_type == 'comment' ? 0 : 1 ); 

                // Model specific data
                $extradata = [
                        'supplier_shipping_slip_id' => $document->id,
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);


                // Let's get dirty
                SupplierInvoiceLine::unguard();
                $invoice_line = SupplierInvoiceLine::create( $data + $extradata );
                SupplierInvoiceLine::reguard();

                $invoice->lines()->save($invoice_line);

                foreach ($line->taxes as $linetax) {

                    // $invoice_line_tax = $this->lineTaxToInvoiceLineTax( $linetax );
                    // Common data
                    $data = [
                    ];

                    $data = $linetax->toArray();
                    // id
                    unset( $data['id'] );
                    // Parent document
                    // unset( $data[$this->getParentModelSnakeCase().'_line_id'] );
                    unset( $data['supplier_shipping_slip_line_id'] );
                    // Dates
                    unset( $data['created_at'] );
                    unset( $data['deleted_at'] );

                    // Model specific data
                    $extradata = [
                    ];


                    // Let's get dirty
                    SupplierInvoiceLineTax::unguard();
                    $invoice_line_tax = SupplierInvoiceLineTax::create( $data + $extradata );
                    SupplierInvoiceLineTax::reguard();

                    $invoice_line->taxes()->save($invoice_line_tax);

                }
            }



$testing = array_key_exists('testing', $params) ?
                                    (bool) $params['testing'] : false;
if ( ! $testing )
{
            // Final touches
            $document->invoiced_at = \Carbon\Carbon::now();
            $document->save();


            // Document traceability
            //     leftable  is this document
            //     rightable is Supplier Invoice Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $invoice->id,
                'rightable_type' => SupplierInvoice::class,

                'type' => 'traceability',
                ];

            $link = DocumentAscription::create( $link_data );
}

        // Good boy, so far
        } // Document loop ends


        // Not so fast, Sony Boy

        $invoice->makeTotals();


        $logger->log("INFO", 'Se ha creado la Factura: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => $invoice->id]);


        $status = array_key_exists('status', $params) ?
                                $params['status'] : 'draft';

        // Manage Invoice Status
        switch ( $status ) {
            case 'draft':
                # Noting to do
                break;
            
            case 'confirmed':
                # code...
                $invoice->confirm();
                break;
            
            case 'closed':
                # code...
                $invoice->confirm();
                $invoice->close();
                break;
            
            case 'canceled':
                # code...
                $invoice->cancel();
                break;
            
            default:
                # code...
                break;
        }



        // We are really hapy with our new-born Invoice!
        $logger->log("INFO", 'Se ha cambiado el Estado de la Factura: <span class="log-showoff-format">{suppliers}</span> a <span class="log-showoff-format">{status}</span>.', ['suppliers' => $invoice->id, 'status' => $invoice->status]);




        // abi_r($grouped_lines, true);


        return $invoice;



//        3.- Si algún documento tiene plantilla diferente, generar factura para él <= Tontá: el albarán NO tiene plantilla de Factura

//        6.- Crear línea de texto con los albaranes ???

//        7.- Crear líneas agrupadas ???

//        8.- Manage estados de documento, pago y stock
    }



    /**
     * Modify an Invoice adding a single Supplier Shippiong Slip. 
     *
     * @param  $document : Supplier Shippiong Slips
     * @param  $invoice  : Supplier Invoice
     * @param  $params[] : array
     * @return  : Supplier Invoice
     */
    public static function addDocumentToInvoice( $document, $invoice, $params = [] )
    {
        if ( ($document->supplier_id != $invoice->supplier_id) || ($document->status != 'closed') || ($document->invoiced_at != null) || ($invoice->status == 'closed') )
            return false;

        $supplier = $invoice->supplier;

        // Handy:
        $alltaxes = Tax::get()->sortByDesc('percent');

        // Initialize line sort order
        $i_offset = $invoice->getMaxLineSortOrder();
        $i = 0;

        // Add Document (loop)
            # code...
            $i++;

            //
            // Text line announces Shipping Slip
            //
            $line_data = [
                'line_sort_order' => ($i_offset+$i)*10, 
                'line_type' => 'comment', 
                'name' => l('Shipping Slip: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)], 'suppliershippingslips'),
//                'product_id' => , 
//                'combination_id' => , 
                'reference' => $document->document_reference, 
//                'name', 
                'quantity' => 1, 
                'measure_unit_id' => Configuration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS'),
//                    'cost_price', 'unit_price', 'unit_supplier_price', 
//                    'prices_entered_with_tax',
//                    'unit_supplier_final_price', 'unit_supplier_final_price_tax_inc', 
//                    'unit_final_price', 'unit_final_price_tax_inc', 
//                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                'total_tax_incl' => 0,
                'total_tax_excl' => 0,
//                    'tax_percent', 'commission_percent', 
                'notes' => '', 
                'locked' => 0,
 //                 'supplier_shipping_slip_id',
                'tax_id' => Configuration::get('DEF_TAX'),  // Just convenient
 //               'sales_rep_id'
                'supplier_shipping_slip_id' => $document->id,
            ];

            $invoice_line = SupplierInvoiceLine::create( $line_data );

            $invoice->lines()->save($invoice_line);

            //
            // Document Discounts
            //
            if (    ( $document->document_discount_percent != 0.0 ) || 
                    ( $document->document_ppd_percent      != 0.0 )
                ) 
            {
                $documentlines = $document->documentlines;
                $taxlines = $document->documentlinetaxes;
                $reduction =  (1.0 - $document->document_discount_percent/100.0) * (1.0 - $document->document_ppd_percent/100.0);
                $currency = $document->currency;

                foreach ($alltaxes as $alltax) 
                {
                    # code...                
                    $lines = $taxlines->where('tax_id', $alltax->id);

                    if ( $lines->count() == 0 ) continue;

                    $taxbase = $documentlines->where('tax_id', $alltax->id)->sum('total_tax_excl');
                    $discountByTax = $taxbase * (1.0 - $reduction);

                    // abi_r($taxbase .' - '.$discountByTax);

                    $i++;

                    $product_id     = null;
                    $combination_id = null;
                    $quantity       = 1.0;

                    $name = l('Shipping Slip: :id [dicount Tax :percent %]', ['id' => $document->document_reference, 'percent' => $alltax->as_percentable( $alltax->percent )], 'suppliershippingslips');

                    // Create Discount Line
                    $line_data = [
                        'line_type' => 'discount',
                        'name' => $name,
    //                    'prices_entered_with_tax' => $document->supplier->currentPricesEnteredWithTax( $document->document_currency ),
                        'prices_entered_with_tax' => 0,
                        'cost_price' => -$discountByTax,
                        'unit_price' => -$discountByTax,
                        'discount_percent' => 0.0,
                        'unit_supplier_price' => -$discountByTax,
                        'unit_supplier_final_price' => -$discountByTax,
                        'tax_id' => $alltax->id,
                        'sales_equalization' => $supplier->sales_equalization,

                        'line_sort_order' => ($i_offset+$i)*10,
                        'notes' => '',

//                        'supplier_shipping_slip_id' => $document->id,
                    ];

                    $invoice_line = $invoice->addServiceLine( $product_id, $combination_id, $quantity, $line_data );

                    $invoice_line->update(['supplier_shipping_slip_id' => $document->id]);
                }
            }


            //
            // Add current Shipping Slip lines to Invoice
            //
            foreach ($document->lines as $line) {
                # code...
                $i++;

                // $invoice_line = $line->toInvoiceLine();

                // Common data
                $data = [
                ];

                $data = $line->toArray();
                // id
                unset( $data['id'] );
                // Parent document
                // unset( $data[$this->getParentModelSnakeCase().'_id'] );
                unset( $data['supplier_shipping_slip_id'] );
                // Dates
                unset( $data['created_at'] );
                unset( $data['deleted_at'] );
                // linetaxes
                unset( $data['linetaxes'] );
                // Sort order
                $data['line_sort_order'] = ($i_offset+$i)*10; 
                // Locked 
                $data['locked'] = ( $line->line_type == 'comment' ? 0 : 1 ); 

                // Model specific data
                $extradata = [
                        'supplier_shipping_slip_id' => $document->id,
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);


                // Let's get dirty
                SupplierInvoiceLine::unguard();
                $invoice_line = SupplierInvoiceLine::create( $data + $extradata );
                SupplierInvoiceLine::reguard();

                $invoice->lines()->save($invoice_line);

                foreach ($line->taxes as $linetax) {

                    // $invoice_line_tax = $this->lineTaxToInvoiceLineTax( $linetax );
                    // Common data
                    $data = [
                    ];

                    $data = $linetax->toArray();
                    // id
                    unset( $data['id'] );
                    // Parent document
                    // unset( $data[$this->getParentModelSnakeCase().'_line_id'] );
                    unset( $data['supplier_shipping_slip_line_id'] );
                    // Dates
                    unset( $data['created_at'] );
                    unset( $data['deleted_at'] );

                    // Model specific data
                    $extradata = [
                    ];


                    // Let's get dirty
                    SupplierInvoiceLineTax::unguard();
                    $invoice_line_tax = SupplierInvoiceLineTax::create( $data + $extradata );
                    SupplierInvoiceLineTax::reguard();

                    $invoice_line->taxes()->save($invoice_line_tax);

                }
            }


            // Final touches
            $document->invoiced_at = \Carbon\Carbon::now();
            $document->save();


            // Document traceability
            //     leftable  is this document
            //     rightable is Supplier Invoice Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $invoice->id,
                'rightable_type' => SupplierInvoice::class,

                'type' => 'traceability',
                ];

            $link = DocumentAscription::create( $link_data );

            // Document loop ends -^



            // Not so fast, Sony Boy

            $invoice->makeTotals();

            $status = array_key_exists('status', $params) ?
                                    $params['status'] : $invoice->status;

            // Manage Invoice Status
            switch ( $status ) {
                case 'draft':
                    # Noting to do
                    break;
                
                case 'confirmed':
                    # code...
                    $invoice->confirm();
                    break;
                
                case 'closed':
                    # code...
                    $invoice->confirm();
                    $invoice->close();
                    break;
                
                case 'canceled':
                    # code...
                    $invoice->cancel();
                    break;
                
                default:
                    # code...
                    break;
            }


        return $invoice;
    }

}