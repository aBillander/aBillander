<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProductionSheet;

use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderLine;
use App\CustomerOrderLineTax;

use App\CustomerInvoice;
use App\CustomerInvoiceLine;
use App\CustomerInvoiceLineTax;

use App\CustomerShippingSlip as Document;
use App\CustomerShippingSlipLine as DocumentLine;
use App\CustomerShippingSlipLineTax as DocumentLineTax;
use App\DocumentAscription;

use App\Configuration;
use App\Sequence;
use App\Template;

// use App\Events\CustomerOrderConfirmed;

use App\Traits\BillableGroupableControllerTrait;
use App\Traits\BillableShippingSlipableControllerTrait;
use App\Traits\BillableProductionSheetableControllerTrait;

    // php artisan make:controller ProductionSheetOrdersController --resource
    // php artisan make:controller ProductionSheetShippingSlipsController --resource
    // php artisan make:controller ProductionSheetInvoicesController --resource

class ProductionSheetShippingSlipsController extends BillableController
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
    public function shippingslipsIndex($id, Request $request)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList       = Sequence::listFor( 'App\\CustomerInvoice' );
        // $order_sequenceList = Sequence::listFor( Document::class );

        $templateList = Template::listFor( 'App\\CustomerInvoice' );

        $statusList = CustomerInvoice::getStatusList();
        // $order_statusList = Document::getStatusList();

        // Make sense:
        unset($statusList['closed']);
        unset($statusList['canceled']);
        // unset($order_statusList['closed']);
        // unset($order_statusList['canceled']);

        $productionSheet = $this->productionSheet->findOrFail($id);

        $documents = $this->document
                            ->where('production_sheet_id', $id)
//                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('shipping_method_id', 'asc')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents_total_count = $documents->count();

        // Apply URL filters
        if ( $request->has('not_closed') )
            $documents->where('status', 'confirmed');
        else
        if ( $request->has('closed') )
            $documents->where('status', 'closed');
        else
        if ( $request->has('closed_not_invoiced') )
            $documents->where('status', 'closed')
                      ->where('invoiced_at', null);
        else
        if ( $request->has('invoiced') )
            $documents->where('invoiced_at', '!=', null);

        // abi_toSql($documents);die();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath($id);

        // abi_r( $this->modelVars() , true);

        $this->model_path = 'customershippingslips';

        return view('production_sheet_shipping_slips.index', $this->modelVars() + compact('productionSheet', 'documents', 'sequenceList', 'templateList', 'statusList', 'items_per_page', 'documents_total_count'));
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
     * Create Invoices for a Production Sheet (after Production Sheet Customer Shipping Slips).
     * Prepare data for processCreateShippingSlips()
     *
     */
    public function createInvoices( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $request->production_sheet_id], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['document_date'], $request );
        $request->merge( ['invoice_date' => $request->input('document_date')] );   // According to $rules_createinvoice

        // $rules = $this->document::$rules_createinvoice;
        $rules = [
                            'document_date' => 'required|date',
//                            'customer_id' => 'exists:customers,id',
                            'sequence_id' => 'exists:sequences,id',
                            'template_id' => 'exists:templates,id',
               ];

        $this->validate($request, $rules);

        // abi_r($request->all(), true);

        // Set params for group
        // Excluded: 'template_id', 'sequence_id', 
        $params = $request->only('production_sheet_id', 'group_by_customer', 'group_by_shipping_address', 'document_date', 'status');

        // abi_r($params, true);

        return $this->processCreateInvoices( $document_group, $params );
    }

    
    /**
     * Create Shipping Slips after a list of Customer Orders (id's).
     * Hard work is done here
     *
     */
    public function processCreateInvoices( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $productionSheet = $this->productionSheet
                                ->findOrFail($params['production_sheet_id']);

            $documents = $this->document
                                ->where('production_sheet_id', $params['production_sheet_id'])
                                ->where('is_invoiceable', '>', 0)
                                ->where('status', 'closed')
                                ->where('invoiced_at', null)
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
    //                            ->orderBy('document_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->find( $list );

                                // abi_r($documents->pluck('document_reference', 'id')->toArray());die();
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }

        $success =[];

          $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $productionSheet->id], 'layouts');


        // abi_r($params);
        // abi_r($documents->pluck('id')->toArray());

        Document::invoiceDocumentCollection( $documents, $params );

        return redirect()
                ->route('productionsheet.shippingslips', $params['production_sheet_id'])
                ->with('success', $success);

    }

    

    /**
     *
     * Let' rock >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.
     *
     */


    /**
     * Close Shipping Slips for a Production Sheet.
     * Prepare data for processCreateShippingSlips()
     *
     */
    public function closeShippingSlips( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $request->production_sheet_id], 'layouts'));

        // abi_r($request->all(), true);

        // Set params for group
        $params = $request->only('production_sheet_id');

        // abi_r($params, true);

        return $this->processCloseShippingSlips( $document_group, $params );
    }

    
    /**
     * Close Shipping Slips after a list of Customer Shipping (id's).
     * Hard work is done here
     *
     */
    public function processCloseShippingSlips( $list, $params )
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
    //                            ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
    //                            ->orderBy('document_date', 'asc')
    //                            ->orderBy('id', 'asc')
                                ->find( $list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }


//        3.- Close Documents

        $fails = [];

        foreach ($documents as $document)
        {
            # code...
            if ( !$document->close() )
                $fails[] = l('Unable to close this document &#58&#58 (:id) ', ['id' => $document->document_reference], 'layouts');
        }

        if (count($fails) > 0) {
            $result  = 'error';
            $message = $fails;
        } else {
            $result  = 'success';
            $message = l('These records have been successfully updated &#58&#58 (:id) ', ['id' => $params['production_sheet_id']], 'layouts');
        }

        // die();

        return redirect()->back()
//                ->route('productionsheet.orders', $params['production_sheet_id'])
                ->with($result, $message);

    }

}
