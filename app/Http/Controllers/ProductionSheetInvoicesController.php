<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProductionSheet;

use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderLine;
use App\CustomerOrderLineTax;

use App\CustomerShippingSlip;
use App\CustomerShippingSlipLine;
use App\CustomerShippingSlipLineTax;

use App\CustomerInvoice as Document;
use App\CustomerInvoiceLine as DocumentLine;
use App\CustomerInvoiceLineTax as DocumentLineTax;
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

class ProductionSheetInvoicesController extends BillableController
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
    public function invoicesIndex($id, Request $request)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList       = Sequence::listFor( 'App\\CustomerShippingSlip' );
        // $order_sequenceList = Sequence::listFor( Document::class );

        $templateList = Template::listFor( 'App\\CustomerShippingSlip' );

        // $statusList = CustomerInvoice::getStatusList();
        // $order_statusList = Document::getStatusList();
        $statusList = Document::getStatusList();

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
                            ->orderBy('document_date', 'desc')
                            ->orderBy('document_reference', 'desc');        // ->get();

        $documents_total_count = $documents->count();

        // Apply URL filters
        if ( $request->has('draft') )
            $documents->where('status', 'draft');
        else
        if ( $request->has('not_closed') )
            $documents->where('status', 'confirmed');
        else
        if ( $request->has('closed') )
            $documents->where('status', 'closed');

        // abi_toSql($documents);die();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath($id);

        // abi_r( $this->modelVars() , true);

        $this->model_path = 'customerinvoices';

        return view('production_sheet_invoices.index', $this->modelVars() + compact('productionSheet', 'documents', 'sequenceList', 'templateList', 'statusList', 'items_per_page', 'documents_total_count'));
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
     * Close Shipping Slips for a Production Sheet.
     * Prepare data for processCreateShippingSlips()
     *
     */
    public function closeInvoices( Request $request )
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

        return $this->processCloseInvoices( $document_group, $params );
    }

    
    /**
     * Close Shipping Slips after a list of Customer Shipping (id's).
     * Hard work is done here
     *
     */
    public function processCloseInvoices( $list, $params )
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
