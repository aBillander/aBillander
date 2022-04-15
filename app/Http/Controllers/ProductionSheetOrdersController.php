<?php

namespace App\Http\Controllers;

use App\Events\CustomerOrderConfirmed;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceLine;
use App\Models\CustomerInvoiceLineTax;
use App\Models\CustomerOrder as Document;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderLine as DocumentLine;
use App\Models\CustomerOrderLineTax as DocumentLineTax;
use App\Models\CustomerShippingSlip;
use App\Models\CustomerShippingSlipLine;
use App\Models\CustomerShippingSlipLineTax;
use App\Helpers\DocumentAscription;
use App\Models\ProductionSheet;
use App\Models\Sequence;
use App\Models\Template;
use App\Traits\BillableGroupableControllerTrait;
use App\Traits\BillableProductionSheetableControllerTrait;
use App\Traits\BillableShippingSlipableControllerTrait;
use Illuminate\Http\Request;

    // php artisan make:controller ProductionSheetOrdersController --resource
    // php artisan make:controller ProductionSheetShippingSlipsController --resource
    // php artisan make:controller ProductionSheetInvoicesController --resource

class ProductionSheetOrdersController extends BillableController
{

   use BillableGroupableControllerTrait;
   use BillableShippingSlipableControllerTrait;
   use BillableProductionSheetableControllerTrait;


   protected $productionSheet;

   public function __construct(ProductionSheet $productionSheet, Customer $customer, Document $document, DocumentLine $document_line)
   {
        parent::__construct();

        $this->productionSheet = $productionSheet;

        $this->model_class = Document::class;

        $this->customer = $customer;
        $this->document = $document;
        $this->document_line = $document_line;
   }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ordersIndex($id, Request $request)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList       = Sequence::listFor( CustomerShippingSlip::class );
        // $order_sequenceList = Sequence::listFor( Document::class );

        $templateList = Template::listFor( CustomerShippingSlip::class );

        $statusList = CustomerShippingSlip::getStatusList();
        // $order_statusList = Document::getStatusList();

        // Make sense:
        unset($statusList['closed']);
        unset($statusList['canceled']);
        // unset($order_statusList['closed']);
        // unset($order_statusList['canceled']);

        $productionSheet = $this->productionSheet->findOrFail($id);

        $documents = $this->document
                            ->where('production_sheet_id', $id)
                            ->with(['customer' => function ($query) {
                                $query->withCount('addresses as nbr_addresses');
                            }])
//                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('shipping_method_id', 'asc')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath($id);

        // abi_r( $this->modelVars() , true);

        $this->model_path = 'customerorders';

        return view('production_sheet_orders.index', $this->modelVars() + compact('productionSheet', 'documents', 'sequenceList', 'templateList', 'statusList', 'items_per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     *
     * Let' rock >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.
     *
     */


    /**
     * Create Shipping Slips for a Production Sheet (after Production Sheet Customer Orders).
     * Prepare data for processCreateShippingSlips()
     *
     */
    public function createShippingSlips( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $request->production_sheet_id], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );
        $request->merge( ['shippingslip_date' => $request->input('document_date')] );   // According to $rules_createshippingslip
        
        $request->merge( ['shippingslip_delivery_date' => $request->input('delivery_date')] );   // According to $rules_createshippingslip

        $rules = $this->document::$rules_createshippingslip;

        $this->validate($request, $rules);

        // abi_r($request->all(), true);

        // Set params for group
        $params = $request->only('production_sheet_id', 'should_group', 'template_id', 'sequence_id', 'document_date', 'delivery_date', 'status');

        // abi_r($params, true);

        return $this->processCreateShippingSlips( $document_group, $params );
    }

    
    /**
     * Create Shipping Slips after a list of Customer Orders (id's).
     * Hard work is done here
     *
     */
    public function processCreateShippingSlips( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $productionSheet = $this->productionSheet
                                ->findOrFail($params['production_sheet_id']);

            $documents = $this->document
                                ->where('production_sheet_id', $params['production_sheet_id'])
                                ->where('status', '<>', 'closed')
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
    //                            ->orderBy('document_date', 'asc')
    //                            ->orderBy('id', 'asc')
                                ->findOrFail( $list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
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

                    // Payment Method
                    $pay_methods = $documents_by_ws_by_addrr_by_meth->unique('payment_method_id')->pluck('payment_method_id')->all();

                    foreach ($pay_methods as $pay_method_id) {
                        # code...
                        // Select Documents
                        $documents_by_ws_by_addrr_by_meth_by_pmeth = $documents_by_ws_by_addrr_by_meth->where('payment_method_id', $pay_method_id);

/* Custmers inner loop */

                    $customers = $documents_by_ws_by_addrr_by_meth_by_pmeth->unique('customer_id')->pluck('customer_id')->all();

                    foreach ($customers as $customer_id) {
                        # code...
                        $documents_by_ws_by_addrr_by_meth_by_pmeth_by_cust = $documents_by_ws_by_addrr_by_meth_by_pmeth->where('customer_id', $customer_id);

                        $customer = $this->customer->find($customer_id);

                        // Do something at last...
                        $extra_params = [
                            'warehouse_id'        => $warehouse_id,
                            'shipping_address_id' => $address_id,
                            'shipping_method_id'  => $method_id,
                            'payment_method_id'   => $pay_method_id,

                            'customer'            => $customer,
                        ];

                        if ( $params['should_group'] > 0 )
                        {
                            $shippingslip = $this->shippingslipDocumentGroup( $documents_by_ws_by_addrr_by_meth_by_pmeth_by_cust, $params + $extra_params);

                            $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $shippingslip->id], 'layouts');

                            // abi_r($documents_by_ws_by_addrr_by_meth_by_cust->pluck('id')->all());

                        } else {
                            //
                            $ids = $documents_by_ws_by_addrr_by_meth_by_pmeth_by_cust->unique('id')->pluck('id')->all();

                            foreach ($ids as $id) {
                                # code...
                                $docs = $documents_by_ws_by_addrr_by_meth_by_pmeth_by_cust->where('id', $id);

                                $shippingslip = $this->shippingslipDocumentGroup( $docs, $params + $extra_params);

                                $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $shippingslip->id], 'layouts');
                            }
                        }
                    }
/* */

                    }
                }
            }
        }

        // die();

        return redirect()
                ->route('productionsheet.orders', $params['production_sheet_id'])
                ->with('success', $success);

    }

    
    /**
     * Create ONE Shipping Slip after a collection of Customer Orders.
     * All Customer Orders **MUST** have the same Customer
     *
     */
    public function shippingslipDocumentGroup( $documents, $params )
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

            'created_via' => 'production_sheet',

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

            // notes
            'notes_from_customer' => $documents->reduce(function($carry, $item){
                                        if ( $item["notes_from_customer"] )
                                            return $carry . $item["notes_from_customer"] . ' | ';
                                        else
                                            return $carry;
                                    }, ''),

            'notes'               => $documents->reduce(function($carry, $item){
                                        if ( $item["notes"] )
                                            return $carry . $item["notes"] . ' | ';
                                        else
                                            return $carry;
                                    }, ''),

            'notes_to_customer'   => $documents->reduce(function($carry, $item){
                                        if ( $item["notes_to_customer"] )
                                            return $carry . $item["notes_to_customer"] . ' | ';
                                        else
                                            return $carry;
                                    }, ''),

            'status' => 'draft',
            'onhold' => 0,
            'locked' => 0,

            'is_invoiceable' => $customer->is_invoiceable,

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
            'payment_method_id' => $params['payment_method_id'] > 0 ? $params['payment_method_id'] : $customer->getPaymentMethodId(),
            'template_id' => (int) $params['template_id'] > 0 ? $params['template_id'] : $customer->getShippingSlipTemplateId() ,
        ];

        // Model specific data
        $extradata = [
//            'type' => 'invoice',
//            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];

        if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
        {
            $extradata = $extradata + ['production_sheet_id' => $params['production_sheet_id']];
        }


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
            $your_order = '';
            if ( $document->reference_customer )
                $your_order = ' - '.l('Your Order: ').$document->reference_customer;
            $line_data = [
                'line_sort_order' => $i*10, 
                'line_type' => 'comment', 
                'name' => l('Order: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)]).$your_order,
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
                unset( $data['customer_order_id'] );
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
                    unset( $data['customer_order_line_id'] );
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

                // Oops! Move Lot allocations from Customer Order to Customer Shipping Slip
                if ( Configuration::isTrue('ENABLE_LOTS') && 
                    ($line->line_type == 'product')       && 
                    ($line->product->lot_tracking > 0) )
                {
                    //
                    $lotitems = $line->lotitems;

                    foreach ($lotitems as $lot_item) {
                        // code...
                        $shippingslip_line->lotitems()->save($lot_item);
                    }                    
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
                'leftable_type'  => CustomerOrder::class,  // Document::class,   // CustomerOrder::class,

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
