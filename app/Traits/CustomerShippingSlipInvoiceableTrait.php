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
     * ALL Customer Shippiong Slips belongs to the same Customer. 
     *
     * @param  $list  : list of Id's of Customer Shippiong Slips
     * @param  $params['customer_id'] : Customer Id of ALL Customer Shippiong Slips
     * @return 
     */
    public static function invoiceDocumentList( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $customer = Customer::
                                  with('currency')
                                ->findOrFail($params['customer_id']);

            $documents = CustomerShippingSlip::
                                  where('customer_id', $params['customer_id'])
                                ->where('status', 'closed')
                                ->where('invoiced_at', null)
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
                                ->orderBy('document_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->findOrFail( $list );

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
     * ALL Customer Shippiong Slips belongs to the same Customer. 
     *
     * @param  $documents  : collectiom of Customer Shippiong Slips
     * @param  $params['customer_id'] : Customer Id of ALL Customer Shippiong Slips
     * @return 
     */
    public static function invoiceDocumentCollection( $documents, $params )
    {

//        1.- Recuperar los documntos
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

            'sequence_id' => $customer->getInvoiceSequenceId(),

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
            'payment_method_id' => $customer->getPaymentMethodId(),
            'template_id' => $customer->getInvoiceTemplateId(),
        ];

        // Model specific data
        $extradata = [
            'type' => 'invoice',
            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];


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
            ];

            $invoice_line = CustomerInvoiceLine::create( $line_data );

            $invoice->lines()->save($invoice_line);

            //
            // Document Discounts
            //
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
                ];

                $invoice_line = $invoice->addServiceLine( $product_id, $combination_id, $quantity, $line_data );
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


$testing = true;
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
}




        // abi_r($grouped_lines, true);


        return $invoice;



//        3.- Si algún documento tiene plantilla diferente, generar factura para él <= Tontá: el albarán NO tiene plantilla de Factura

//        6.- Crear línea de texto con los albaranes ???

//        7.- Crear líneas agrupadas ???

//        8.- Manage estados de documento, pago y stock
    }

}