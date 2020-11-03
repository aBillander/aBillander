<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Supplier;
use App\SupplierOrder as Document;
use App\SupplierOrderLine as DocumentLine;
use App\SupplierOrderLineTax as DocumentLineTax;

use App\SupplierInvoice;
use App\SupplierInvoiceLine;
use App\SupplierInvoiceLineTax;

use App\SupplierShippingSlip;
use App\SupplierShippingSlipLine;
use App\SupplierShippingSlipLineTax;
use App\DocumentAscription;

use App\Configuration;
use App\Sequence;
use App\Template;

use App\Events\SupplierOrderConfirmed;

// use App\Traits\BillableGroupableControllerTrait;
// use App\Traits\BillableShippingSlipableControllerTrait;

class SupplierOrdersController extends BillableController
{

//   use BillableGroupableControllerTrait;
//   use BillableShippingSlipableControllerTrait;

   public function __construct(Supplier $supplier, Document $document, DocumentLine $document_line)
   {
        parent::__construct();

        $this->model_class = Document::class;

        $this->supplier = $supplier;
        $this->document = $document;
        $this->document_line = $document_line;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );
        
        $model_path = $this->model_path;
        $view_path = $this->view_path;


        

        $documents = $this->document
                            ->filter( $request->all() )
                            ->with('supplier')
//                            ->with('currency')
//                            ->with('paymentmethod')
//                            ->orderBy('document_date', 'desc')
                            // ->orderBy('document_reference', 'desc');
// https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
                            ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');
//                          ->orderBy('id', 'desc');        // ->get();


        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath($this->model_path);
        

        $statusList = $this->model_class::getStatusList();

        return view($this->view_path.'.index', $this->modelVars() + compact('documents', 'statusList'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexBySupplier($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );
        
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList = Sequence::listFor( 'App\\SupplierInvoice' );

        $templateList = Template::listFor( 'App\\SupplierInvoice' );

        $supplier = $this->supplier->findOrFail($id);

        $documents = $this->document
                            ->filter( $request->all() )
                            ->where('supplier_id', $id)
//                            ->with('supplier')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
//                            ->orderBy('id', 'desc');        // ->get();
                            ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');

        $documents = $documents->paginate( $items_per_page );

        $documents->setPath($id);

        
        if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
        {
            $suffix = '_export_mfg';

            $manufacturing_statusList = [
                            'unasigned' => 'No asignados',
                            'asigned' => 'Asignados a Hojas de Producción abiertas',
                            'ongoing' => 'Los dos anteriores (Pedidos en curso)',
            ];
            
        } else {
            $suffix = '';
            
            $manufacturing_statusList = [];
        }

        
        if ( Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
        {
            $wooc_statusList = [
                        '1' => 'Sólo Pedidos WooCommerce',
            ];

        } else {

            $wooc_statusList = [];
        }


        $statusList = $this->model_class::getStatusList();

        return view($this->view_path.'.index'.$suffix.'_by_supplier', $this->modelVars() + compact('supplier', 'documents', 'sequenceList', 'templateList', 'items_per_page', 'statusList', 'manufacturing_statusList', 'wooc_statusList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;
//        $model_snake_case = $this->model_snake_case;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();

        $templateList = $this->document->templateList();

        if ( !(count($sequenceList)>0) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
        
        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList', 'templateList'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithSupplier($supplier_id)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        // Some checks to start with:

        $sequenceList = $this->document->sequenceList();

        if ( !count($sequenceList) )
            return redirect($this->model_path)
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));


        // Do the Mambo!!!
        try {
            $supplier = Supplier::with('addresses')->findOrFail( $supplier_id );

        } catch(ModelNotFoundException $e) {
            // No Supplier available, ask for one
            return redirect()->back()
                    ->with('error', l('The record with id=:id does not exist', ['id' => $supplier_id], 'layouts'));
        }
        
        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList', 'supplier_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{supplier_id}', $request->input('supplier_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        $supplier = $this->supplier->with('addresses')->find(  $request->input('supplier_id') );

        // Extra data
//        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
//        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

                        'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),
                        
                        'template_id'          => $request->input('template_id') ?? optional($supplier)->getOrderTemplateId(),

                        'document_discount_percent' => (float) optional($supplier)->discount_percent,
                        'document_ppd_percent'      => (float) optional($supplier)->discount_ppd_percent,

                        'created_via'          => 'manual',
                        'status'               =>  'draft',
                        'locked'               => 0,

                        'payment_method_id' => $supplier ? $supplier->getPaymentMethodId() : Configuration::getInt('DEF_SUPPLIER_PAYMENT_METHOD'),
                     ];

        $request->merge( $extradata );

        $document = $this->document->create($request->all());

        // Move on
        if ($request->has('nextAction'))
        {
            switch ( $request->input('nextAction') ) {
                case 'saveAndConfirm':
                    # code...
                    $document->confirm();

                    break;
                
                default:
                    # code...
                    break;
            }
        }

        // Maybe...
//        if (  Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') )
//            $document->confirm();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SupplierOrder  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Document $supplierOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $supplierOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $supplierOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $supplierOrder)
    {
        //
    }
}
