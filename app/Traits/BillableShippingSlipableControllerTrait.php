<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Customer;
use App\CustomerOrder as Document;
use App\CustomerOrderLine as DocumentLine;
use App\CustomerOrderLineTax as DocumentLineTax;

use App\CustomerInvoice;
use App\CustomerInvoiceLine;
use App\CustomerInvoiceLineTax;

use App\CustomerShippingSlip;
use App\CustomerShippingSlipLine;
use App\CustomerShippingSlipLineTax;
use App\DocumentAscription;

use App\Configuration;
use App\Sequence;
use App\Template;

trait BillableShippingSlipableControllerTrait
{
    /**
    * Temporary stuff. Waiting for final location
    */

    public function createGroupShippingSlip( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->route('customer.shippingslipable.orders', $request->input('customer_id'))
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );
        $request->merge( ['shippingslip_date' => $request->input('document_date')] );   // According to $rules_createshippingslip
        
        $request->merge( ['shippingslip_delivery_date' => $request->input('delivery_date')] );   // According to $rules_createshippingslip

        $rules = $this->document::$rules_createshippingslip;

        $this->validate($request, $rules);

//        abi_r($request->all(), true);

        // Set params for group
        $params = $request->only('customer_id', 'template_id', 'sequence_id', 'document_date', 'delivery_date', 'status');

        // abi_r($params, true);

        return $this->shippingslipDocumentList( $document_group, $params );
    } 

    public function createShippingSlip($id)
    {
        $document = $this->document
                            ->with('customer')
                            ->findOrFail($id);
        
        $customer = $document->customer;

        $params = [
            'customer_id'   => $customer->id, 
            'template_id'   => Configuration::getInt('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE'), 
            'sequence_id'   => Configuration::getInt('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE'), 
            'document_date' => \Carbon\Carbon::now()->toDateString(),

            'document_discount_percent' => $document->document_discount_percent,
            'document_ppd_percent'      => $document->document_ppd_percent,

            'status' => 'confirmed',
        ];

        // abi_r($params, true);
        
        return $this->shippingslipDocumentList( [$id], $params );
    }

    
    public function shippingslipDocumentList( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $customer = $this->customer
                                ->with('currency')
                                ->findOrFail($params['customer_id']);

            $documents = $this->document
                                ->where('customer_id', $params['customer_id'])
                                ->where('status', '<>', 'closed')
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
                                ->orderBy('document_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->findOrFail( $list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }

        // Hummmm!
        // Lets check Production Sheet!
        if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
        {
            //
            $sheets = $documents->unique('production_sheet_id')->pluck('production_sheet_id')->all();

            if ( count($sheets) > 1 )   // More than one Production Sheet
                return redirect()->back()
                    ->with('error', l('Los Registros seleccionados pertenecen a varias Hojas de prodcucción: [:ps] ', ['ps' => implode(', ', $sheets)]).l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));
        }


//        3a.- Pre-process Orders

        foreach ($documents as $document)
        {
            # code...
            $document->warehouse_id        = $document->getWarehouseId();
            $document->shipping_address_id = $document->getShippingAddressId();
            $document->shipping_method_id  = $document->getShippingMethodId();
        }


//        3b.- Group Orders

        $success =[];

        // Warehouses
        $warehouses = $documents->unique('warehouse_id')->pluck('warehouse_id')->all();

        foreach ($warehouses as $warehouse_id) {
            # code...
            // Select Documents
            $documents_by_ws = $documents->where('warehouse_id', $warehouse_id);

            // Adresses
            $addresses = $documents_by_ws->unique('shipping_address_id')->pluck('shipping_address_id')->all();

            foreach ($addresses as $address_id) {
                # code...
                // Select Documents
                $documents_by_ws_by_addrr = $documents_by_ws->where('shipping_address_id', $address_id);

                // Shipping Method
                $methods = $documents_by_ws_by_addrr->unique('shipping_method_id')->pluck('shipping_method_id')->all();

                foreach ($methods as $method_id) {
                    # code...
                    // Select Documents
                    $documents_by_ws_by_addrr_by_meth = $documents_by_ws_by_addrr->where('shipping_method_id', $method_id);

                    // Do something at last...
                    $extra_params = [
                        'warehouse_id'        => $warehouse_id,
                        'shipping_address_id' => $address_id,
                        'shipping_method_id'  => $method_id,

                        'customer'            => $customer,
                    ];

                    $shippingslip = $this->shippingslipDocuments( $documents_by_ws_by_addrr_by_meth, $params + $extra_params);

                    $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $shippingslip->id], 'layouts');

                    // abi_r($documents_by_ws_by_addrr_by_meth->pluck('id')->all());
                }
            }
        }


//        abi_r($warehouses);
//        die();



        return redirect()
                ->route('customer.shippingslips', [$customer->id])
                ->with('success', $success);

    }

    
    public function shippingslipDocuments( $documents, $params )
    {

//        abi_r($params); return null;

        $customer = $params['customer'];

//        4.- Cear cabecera

        // Header
        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'customer_id' => $customer->id,
//            'user_id' => $this->,

            'sequence_id' => $params['sequence_id'],

            'created_via' => 'aggregate_orders',

            'document_date' => $params['document_date'],

            'delivery_date' => $params['delivery_date'],

            'currency_conversion_rate' => $customer->currency->conversion_rate,
//            'down_payment' => $this->down_payment,

            'document_discount_percent' => array_key_exists('document_discount_percent', $params) ?
                                                 $params['document_discount_percent'] : $customer->discount_percent,

            'document_ppd_percent'      => array_key_exists('document_ppd_percent', $params) ?
                                                 $params['document_ppd_percent']      : $customer->discount_ppd_percent,

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
            'shipping_address_id' => $params['shipping_address_id'],
            'warehouse_id' => $params['warehouse_id'],
            'shipping_method_id' => $params['shipping_method_id'],
//            'shipping_address_id' => $document->shipping_address_id,
//            'warehouse_id' => $document->warehouse_id,
//            'shipping_method_id' => $document->shipping_method_id,
//            'carrier_id' => $carrier_id,              // Not needed: calculated al saving event
            'sales_rep_id' => $customer->sales_rep_id,
            'currency_id' => $customer->currency->id,
            'payment_method_id' => $customer->getPaymentMethodId(),
            'template_id' => $params['template_id'],
        ];

        // Model specific data
        $extradata = [
//            'type' => 'invoice',
//            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];


        // Let's get dirty
//        CustomerInvoice::unguard();
        $shippingslip = CustomerShippingSlip::create( $data + $extradata );
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

        // Initialize line sort order
        $i = 0;

        foreach ($documents as $document) {

            // Confirm Order (if needed)
            $document->confirm();

            # code...
            $i++;

            // Text line announces Shipping Slip
            $line_data = [
                'line_sort_order' => $i*10, 
                'line_type' => 'comment', 
                'name' => l('Order: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)]),
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

            $shippingslip_line = CustomerShippingSlipLine::create( $line_data );

            $shippingslip->lines()->save($shippingslip_line);

            // Add current Shipping Slip lines to Invoice
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
                unset( $data[$this->getParentModelSnakeCase().'_id'] );
                // Dates
                unset( $data['created_at'] );
                unset( $data['deleted_at'] );
                // linetaxes
                unset( $data['linetaxes'] );
                // Sort order
                $data['line_sort_order'] = $i*10; 
                // Locked 
                $data['locked'] = 1; 

                // Model specific data
                $extradata = [
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);


                // Let's get dirty
                CustomerShippingSlipLine::unguard();
                $shippingslip_line = CustomerShippingSlipLine::create( $data + $extradata );
                CustomerShippingSlipLine::reguard();

                $shippingslip->lines()->save($shippingslip_line);

                foreach ($line->taxes as $linetax) {

                    // $invoice_line_tax = $this->lineTaxToInvoiceLineTax( $linetax );
                    // Common data
                    $data = [
                    ];

                    $data = $linetax->toArray();
                    // id
                    unset( $data['id'] );
                    // Parent document
                    unset( $data[$this->getParentModelSnakeCase().'_line_id'] );
                    // Dates
                    unset( $data['created_at'] );
                    unset( $data['deleted_at'] );

                    // Model specific data
                    $extradata = [
                    ];


                    // Let's get dirty
                    CustomerShippingSlipLineTax::unguard();
                    $shippingslip_line_tax = CustomerShippingSlipLineTax::create( $data + $extradata );
                    CustomerShippingSlipLineTax::reguard();

                    $shippingslip_line->taxes()->save($shippingslip_line_tax);

                }
            }

            // Not so fast, Sony Boy
            
            // Final touches
            $document->shipping_slip_at = \Carbon\Carbon::now();
            $document->save();      // Maybe not needed, because we are to close 

            // Close Invoice
            $document->close();


            // Document traceability
            //     leftable  is this document
            //     rightable is Customer Invoice Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $shippingslip->id,
                'rightable_type' => CustomerShippingSlip::class,

                'type' => 'traceability',
                ];

            $link = DocumentAscription::create( $link_data );
        }

        // Good boy, so far

        $shippingslip->makeTotals();

        if ( $params['status'] == 'confirmed' )
            $shippingslip->confirm();




        // abi_r($grouped_lines, true);

        return $shippingslip;





//        3.- Si algún documento tiene plantilla diferente, generar factura para él <= Tontá: el albarán NO tiene plantilla de Factura

//        6.- Crear línea de texto con los albaranes ???

//        7.- Crear líneas agrupadas ???

//        8.- Manage estados de documento, pago y stock


    }

}