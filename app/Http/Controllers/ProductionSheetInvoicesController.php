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
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath($id);

        // abi_r( $this->modelVars() , true);

        $this->model_path = 'customershippingslips';

        return view('production_sheet_invoices.index', $this->modelVars() + compact('productionSheet', 'documents', 'sequenceList', 'templateList', 'statusList', 'items_per_page'));
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
}
