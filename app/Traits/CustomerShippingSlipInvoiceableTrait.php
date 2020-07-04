<?php 

namespace App\Traits;

use App\Customer;

use App\CustomerShippingSlip;

use App\CustomerInvoice;
use App\CustomerInvoiceLine;
use App\CustomerInvoiceLineTax;

use App\Configuration;
use App\DocumentAscription;

trait CustomerShippingSlipInvoiceableTrait
{

    /**
     * Create an Invoice after a list of Customer Shippiong Slips. 
     *
     * @param  $list  : list of Id's of Customer Shippiong Slips
     * @param  $params[] : array of params
     *                      Most used: 'group_by_customer', 'group_by_shipping_address', 'document_date', 'status'
     *                      Aditional: 'sequence_id', 'template_id', 'created_via', 'payment_method_id', 
     * @return 
     */
    public static function invoiceDocumentList( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

//            $customer = Customer::
//                                  with('currency')
//                                ->findOrFail($params['customer_id']);

            $documents = CustomerShippingSlip::
//                                  where('customer_id', $params['customer_id'])
                                  where('status', 'closed')
                                ->where('is_invoiceable', '>', 0)
                                ->where('invoiced_at', null)
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('customer')
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


        return CustomerShippingSlip::invoiceDocumentCollection( $documents, $params );
    }

    

    /**
     * Create an Invoice after a collection of Customer Shippiong Slips. 
     * Group Shippiong Slips by Customer. 
     *
     * @param  $documents  : collectiom of Customer Shippiong Slips
     * @param  $params[] : array of params
     * @return CustomerInvoice Object
     */
    public static function invoiceDocumentCollection( $documents, $params )
    {
        // Group Shippiong Slips by Customer.
        $customers = $documents->unique('customer_id')->pluck('customer_id')->all();

        foreach ($customers as $customer_id) {
            # code...
            $documents_by_cid = $documents->where('customer_id', $customer_id);

            $extra_params = [
                        'customer_id'            => $customer_id,
                    ];


            // Should group? i.e.: One invoice per Customer?
            if ( array_key_exists('group_by_customer', $params) && ( $params['group_by_customer'] == 0 ) ) {

                // Every single document

                foreach ($documents_by_cid as $document) {
                    # code...
                    // Select Documents
                    $documents_by_doc = collect($document);

                    CustomerShippingSlip::invoiceDocumentsByCustomer( $documents_by_doc, $params + $extra_params );
                }

            } else {

                CustomerShippingSlip::invoiceDocumentsByCustomer( $documents_by_cid, $params + $extra_params );
            }
        }

    }

    

    /**
     * Create an Invoice after a collection of Customer Shippiong Slips. 
     * ALL Customer Shippiong Slips belongs to the same Customer. 
     * Group Customer Shippiong Slips according to some criteria. 
     *
     * @param  $documents  : collectiom of Customer Shippiong Slips
     * @param  $params[] : array of params
     * @return CustomerInvoice Object
     */
    public static function invoiceDocumentsByCustomer( $documents, $params )
    {
        // Pre-process Documents

        foreach ($documents as $document)
        {
            # code...
            $document->payment_method_id = $document->getPaymentMethodId();
        }

        // Group by payment method
        $pmethods = $documents->unique('payment_method_id')->pluck('payment_method_id')->all();

        foreach ($pmethods as $payment_method_id) {
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

                    return CustomerShippingSlip::invoiceCustomerDocuments( $documents_by_pm_by_addrr, $params );
                }

            } else {

                return CustomerShippingSlip::invoiceCustomerDocuments( $documents_by_pm, $params );
            }
        }


    }

    

    /**
     * Create an Invoice after a collection of Customer Shippiong Slips. 
     * ALL Customer Shippiong Slips belongs to the same Customer. 
     *
     * @param  $documents  : collectiom of Customer Shippiong Slips
     * @param  $params['customer_id'] : Customer Id of ALL Customer Shippiong Slips
     * @return CustomerInvoice Object
     */
    public static function invoiceCustomerDocuments( $documents, $params )
    {
        // abi_r($params);die();

//        1.- Recuperar los documntos. Skip not invoiceable
        $documents = $documents->reject(function ($item, $key) {
                                    return $item->is_invoiceable == 0;
                                });

//        2.- Comprobar que están todos los de la lista ( comparando count() )

        $customer = Customer::
                              with('currency')
                            ->findOrFail($params['customer_id']);


//        4.- Cear cabecera

        // Header
        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'customer_id' => $customer->id,
//            'user_id' => $this->,

            'sequence_id' => array_key_exists('sequence_id', $params) ?
                                    $params['sequence_id'] : $customer->getInvoiceSequenceId(),

            'created_via' => array_key_exists('created_via', $params) ?
                                    $params['created_via'] : 'aggregate_shipping_slips',

            'document_date' => array_key_exists('document_date', $params) ?
                                    $params['document_date'] : \Carbon\Carbon::now()->toDateString(),

            'currency_conversion_rate' => $customer->currency->conversion_rate,
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

            'invoicing_address_id' => $customer->invoicing_address_id,
//            'shipping_address_id' => $this->shipping_address_id,
//            'warehouse_id' => $this->warehouse_id,
//            'shipping_method_id' => $this->shipping_method_id ?? $this->customer->shipping_method_id ?? Configuration::getInt('DEF_CUSTOMER_SHIPPING_METHOD'),
//            'carrier_id' => $this->carrier_id,
            'sales_rep_id' => $customer->sales_rep_id,
            'currency_id' => $customer->currency->id,
            'payment_method_id' => array_key_exists('payment_method_id', $params) ?
                                    $params['payment_method_id'] : $customer->getPaymentMethodId(),
            'template_id' => array_key_exists('template_id', $params) ?
                                    $params['template_id'] : $customer->getInvoiceTemplateId(),
        ];

        // Model specific data
        $extradata = [
            'type' => 'invoice',
            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];

        if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
        {
            if ( array_key_exists('production_sheet_id', $params) && ( $params['production_sheet_id'] > 0 ) )
            {
                $extradata = $extradata + ['production_sheet_id' => $params['production_sheet_id']];
            }
        }


        // Let's get dirty
//        CustomerInvoice::unguard();
        $invoice = CustomerInvoice::create( $data + $extradata );
//        CustomerInvoice::reguard();


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
        $alltaxes = \App\Tax::get()->sortByDesc('percent');

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
                'name' => l('Shipping Slip: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)], 'customershippingslips'),
//                'product_id' => , 
//                'combination_id' => , 
                'reference' => $document->document_reference, 
//                'name', 
                'quantity' => 1, 
                'measure_unit_id' => Configuration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS'),
//                    'cost_price', 'unit_price', 'unit_customer_price', 
//                    'prices_entered_with_tax',
//                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
//                    'unit_final_price', 'unit_final_price_tax_inc', 
//                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                'total_tax_incl' => 0,
                'total_tax_excl' => 0,
//                    'tax_percent', 'commission_percent', 
                'notes' => '', 
                'locked' => 0,
 //                 'customer_shipping_slip_id',
                'tax_id' => Configuration::get('DEF_TAX'),  // Just convenient
 //               'sales_rep_id'
                'customer_shipping_slip_id' => $document->id,
            ];

            $invoice_line = CustomerInvoiceLine::create( $line_data );

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

                    $name = l('Shipping Slip: :id [dicount Tax :percent %]', ['id' => $document->document_reference, 'percent' => $alltax->as_percentable( $alltax->percent )], 'customershippingslips');

                    // Create Discount Line
                    $line_data = [
                        'line_type' => 'discount',
                        'name' => $name,
    //                    'prices_entered_with_tax' => $document->customer->currentPricesEnteredWithTax( $document->document_currency ),
                        'prices_entered_with_tax' => 0,
                        'cost_price' => -$discountByTax,
                        'unit_price' => -$discountByTax,
                        'discount_percent' => 0.0,
                        'unit_customer_price' => -$discountByTax,
                        'unit_customer_final_price' => -$discountByTax,
                        'tax_id' => $alltax->id,
                        'sales_equalization' => $customer->sales_equalization,

                        'line_sort_order' => $i*10,
                        'notes' => '',

//                        'customer_shipping_slip_id' => $document->id,
                    ];

                    $invoice_line = $invoice->addServiceLine( $product_id, $combination_id, $quantity, $line_data );

                    $invoice_line->update(['customer_shipping_slip_id' => $document->id]);
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
                unset( $data['customer_shipping_slip_id'] );
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
                        'customer_shipping_slip_id' => $document->id,
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);


                // Let's get dirty
                CustomerInvoiceLine::unguard();
                $invoice_line = CustomerInvoiceLine::create( $data + $extradata );
                CustomerInvoiceLine::reguard();

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
                    unset( $data['customer_shipping_slip_line_id'] );
                    // Dates
                    unset( $data['created_at'] );
                    unset( $data['deleted_at'] );

                    // Model specific data
                    $extradata = [
                    ];


                    // Let's get dirty
                    CustomerInvoiceLineTax::unguard();
                    $invoice_line_tax = CustomerInvoiceLineTax::create( $data + $extradata );
                    CustomerInvoiceLineTax::reguard();

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
            //     rightable is Customer Invoice Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $invoice->id,
                'rightable_type' => CustomerInvoice::class,

                'type' => 'traceability',
                ];

            $link = DocumentAscription::create( $link_data );
}

        // Good boy, so far
        } // Document loop ends


        // Not so fast, Sony Boy

        $invoice->makeTotals();

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




        // abi_r($grouped_lines, true);


        return $invoice;



//        3.- Si algún documento tiene plantilla diferente, generar factura para él <= Tontá: el albarán NO tiene plantilla de Factura

//        6.- Crear línea de texto con los albaranes ???

//        7.- Crear líneas agrupadas ???

//        8.- Manage estados de documento, pago y stock
    }



    /**
     * Modify an Invoice adding a single Customer Shippiong Slip. 
     *
     * @param  $document : Customer Shippiong Slips
     * @param  $invoice  : Customer Invoice
     * @param  $params[] : array
     * @return  : Customer Invoice
     */
    public static function addDocumentToInvoice( $document, $invoice, $params = [] )
    {
        if ( ($document->customer_id != $invoice->customer_id) || ($document->status != 'closed') || ($document->invoiced_at != null) || ($invoice->status == 'closed') )
            return false;

        $customer = $invoice->customer;

        // Handy:
        $alltaxes = \App\Tax::get()->sortByDesc('percent');

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
                'name' => l('Shipping Slip: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)], 'customershippingslips'),
//                'product_id' => , 
//                'combination_id' => , 
                'reference' => $document->document_reference, 
//                'name', 
                'quantity' => 1, 
                'measure_unit_id' => Configuration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS'),
//                    'cost_price', 'unit_price', 'unit_customer_price', 
//                    'prices_entered_with_tax',
//                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
//                    'unit_final_price', 'unit_final_price_tax_inc', 
//                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                'total_tax_incl' => 0,
                'total_tax_excl' => 0,
//                    'tax_percent', 'commission_percent', 
                'notes' => '', 
                'locked' => 0,
 //                 'customer_shipping_slip_id',
                'tax_id' => Configuration::get('DEF_TAX'),  // Just convenient
 //               'sales_rep_id'
                'customer_shipping_slip_id' => $document->id,
            ];

            $invoice_line = CustomerInvoiceLine::create( $line_data );

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

                    $name = l('Shipping Slip: :id [dicount Tax :percent %]', ['id' => $document->document_reference, 'percent' => $alltax->as_percentable( $alltax->percent )], 'customershippingslips');

                    // Create Discount Line
                    $line_data = [
                        'line_type' => 'discount',
                        'name' => $name,
    //                    'prices_entered_with_tax' => $document->customer->currentPricesEnteredWithTax( $document->document_currency ),
                        'prices_entered_with_tax' => 0,
                        'cost_price' => -$discountByTax,
                        'unit_price' => -$discountByTax,
                        'discount_percent' => 0.0,
                        'unit_customer_price' => -$discountByTax,
                        'unit_customer_final_price' => -$discountByTax,
                        'tax_id' => $alltax->id,
                        'sales_equalization' => $customer->sales_equalization,

                        'line_sort_order' => ($i_offset+$i)*10,
                        'notes' => '',

//                        'customer_shipping_slip_id' => $document->id,
                    ];

                    $invoice_line = $invoice->addServiceLine( $product_id, $combination_id, $quantity, $line_data );

                    $invoice_line->update(['customer_shipping_slip_id' => $document->id]);
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
                unset( $data['customer_shipping_slip_id'] );
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
                        'customer_shipping_slip_id' => $document->id,
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);


                // Let's get dirty
                CustomerInvoiceLine::unguard();
                $invoice_line = CustomerInvoiceLine::create( $data + $extradata );
                CustomerInvoiceLine::reguard();

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
                    unset( $data['customer_shipping_slip_line_id'] );
                    // Dates
                    unset( $data['created_at'] );
                    unset( $data['deleted_at'] );

                    // Model specific data
                    $extradata = [
                    ];


                    // Let's get dirty
                    CustomerInvoiceLineTax::unguard();
                    $invoice_line_tax = CustomerInvoiceLineTax::create( $data + $extradata );
                    CustomerInvoiceLineTax::reguard();

                    $invoice_line->taxes()->save($invoice_line_tax);

                }
            }


            // Final touches
            $document->invoiced_at = \Carbon\Carbon::now();
            $document->save();


            // Document traceability
            //     leftable  is this document
            //     rightable is Customer Invoice Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $invoice->id,
                'rightable_type' => CustomerInvoice::class,

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