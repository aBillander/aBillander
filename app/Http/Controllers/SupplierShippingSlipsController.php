<?php

namespace App\Http\Controllers;

use App\Events\SupplierShippingSlipConfirmed;
use App\Helpers\DocumentAscription;
use App\Helpers\Price;
use App\Models\ActivityLogger;
use App\Models\Combination;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Models\MeasureUnit;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SalesRep;
use App\Models\Sequence;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceLine;
use App\Models\SupplierInvoiceLineTax;
use App\Models\SupplierShippingSlip as Document;
use App\Models\SupplierShippingSlip;
use App\Models\SupplierShippingSlipLine as DocumentLine;
use App\Models\Tax;
use App\Models\Template;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SupplierShippingSlipsController extends BillableController
{

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

        $shipment_statusList = $this->model_class::getShipmentStatusList();

        return view($this->view_path.'.index', $this->modelVars() + compact('documents', 'statusList', 'shipment_statusList'));
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

        $sequenceList = Sequence::listFor( SupplierInvoice::class );

        $templateList = Template::listFor( SupplierInvoice::class );

        $supplier = $this->supplier->findOrFail($id);

        $documents = $this->document
                            ->filter( $request->all() )
                            ->where('supplier_id', $id)
//                            ->with('supplier')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath($id);

        $statusList = $this->model_class::getStatusList();

//        $shipment_statusList = $this->model_class::getShipmentStatusList();

        return view($this->view_path.'.index_by_supplier', $this->modelVars() + compact('supplier', 'documents', 'statusList', 'sequenceList', 'templateList', 'items_per_page'));
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

        $supplier = $this->supplier->with('addresses')->findOrFail(  $request->input('supplier_id') );

        // Extra data
//        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
//        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => Context::getContext()->user->id,

                        'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),

                        'document_discount_percent' => $supplier->discount_percent,
                        'document_ppd_percent'      => $supplier->discount_ppd_percent,

                        'created_via'          => 'manual',
                        'status'               =>  'draft',
                        'locked'               => 0,
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
//            $supplierOrder->confirm();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupplierShippingSlip  $suppliershippingslip
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect($this->model_path.'/'.$id.'/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierShippingSlip  $suppliershippingslip
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        // Little bit Gorrino style...
        // Find by document_reference (if supplied one)
        if ( $request->has('document_reference') )
        {
            $document = $this->document->where('document_reference', $request->input('document_reference'))->firstOrFail();

            // $request->request->remove('document_reference');
            // $this->edit($document->id, $request);

            return redirect($this->model_path.'/'.$document->id.'/edit');
        }
        else
        {
            $document = $this->document->findOrFail($id);
        }

        $sequenceList = $this->document->sequenceList();

        $templateList = $this->document->templateList();

        $supplier = Supplier::find( $document->supplier_id );

        $addressBook       = $supplier->addresses;

        $theId = $supplier->invoicing_address_id;
        $invoicing_address = $addressBook->filter(function($item) use ($theId) {    // Filter returns a collection!
            return $item->id == $theId;
        })->first();

        $addressbookList = array();
        foreach ($addressBook as $address) {
            $addressbookList[$address->id] = $address->alias;
        }

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'delivery_date', 'export_date'], $document );

        $units = MeasureUnit::whereIn('id', [Configuration::getInt('DEF_VOLUME_UNIT'), Configuration::getInt('DEF_WEIGHT_UNIT')])->get();
        $volume_unit = $units->where('id', Configuration::getInt('DEF_VOLUME_UNIT'))->first();
        $weight_unit = $units->where('id', Configuration::getInt('DEF_WEIGHT_UNIT'))->first();


        return view($this->view_path.'.edit', $this->modelVars() + compact('supplier', 'invoicing_address', 'addressBook', 'addressbookList', 'document', 'sequenceList', 'templateList', 'volume_unit', 'weight_unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupplierShippingSlip  $suppliershippingslip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $suppliershippingslip)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{supplier_id}', $request->input('supplier_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);
/*
        // Extra data
        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'document_prefix'      => $seq->prefix,
                        'document_id'          => $doc_id,
                        'document_reference'   => $seq->getDocumentReference($doc_id),

                        'user_id'              => \App\Context::getContext()->user->id,

                        'created_via'          => 'manual',
                        'status'               =>  \App\Configuration::get('CUSTOMER_ORDERS_NEED_VALIDATION') ? 'draft' : 'confirmed',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );
*/
        $document = $suppliershippingslip;

        $need_update_totals = (
            ($request->input('document_ppd_percent', $document->document_ppd_percent) != $document->document_ppd_percent) ||
            ($request->input('currency_conversion_rate', $document->currency_conversion_rate) != $document->currency_conversion_rate)
        ) ? true : false;

        $document->fill($request->all());

        // Reset Export date
        // if ( $request->input('export_date_form') == '' ) $document->export_date = null;

        $document->save();

        if ( $need_update_totals ) $document->makeTotals();

        // Move on
        if ($request->has('nextAction'))
        {
            switch ( $request->input('nextAction') ) {
                case 'saveAndConfirm':
                    # code...
                    $document->confirm();

                    break;
                
                case 'saveAndContinue':
                    # code...

                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $nextAction = $request->input('nextAction', '');
        
        if ( $nextAction == 'saveAndContinue' ) 
            return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        return redirect($this->model_path)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierShippingSlip  $suppliershippingslip
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $document = $this->document->findOrFail($id);

        if( !$document->deletable )
            return redirect()->back()
                ->with('error', l('This record cannot be deleted because its Status &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        if ($request->input('open_parents', 0))
        {
            // Open parent Documents (Purchase orders)
        }

        $document->delete();

        return redirect($this->model_path)      // redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    /**
     * Manage Status.
     *
     * ******************************************************************************************************************************* *
     * 
     */

    protected function confirm(Document $document)
    {
        // Can I?
        if ( $document->lines->count() == 0 )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document has no Lines', 'layouts'));
        }

        if ( $document->onhold )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is on-hold', 'layouts'));
        }

        // Confirm
        if ( $document->confirm() )
            return redirect()->back()           // ->route($this->model_path.'.index')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }

    protected function unConfirm(Document $document)
    {
        // Can I?
        if ( $document->status != 'confirmed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document has no Lines', 'layouts'));
        }

        // UnConfirm
        if ( $document->unConfirmDocument() )
            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


    protected function onholdToggle(Document $document)
    {
        // No checks. A closed document can be set to "onhold". Maybe usefull...

        // Toggle
        $toggle = $document->onhold > 0 ? 0 : 1;
        $document->onhold = $toggle;
        
        $document->save();

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
    }


    protected function close(Document $document)
    {
        // Can I?
        if ( $document->lines->count() == 0 )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document has no Lines', 'layouts'));
        }

        if ( $document->onhold )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is on-hold', 'layouts'));
        }

        if ( Configuration::isTrue('ENABLE_LOTS') && !$document->lines_has_required_lots )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document Lines do not have enough Lots', 'layouts'));
        }

        // Close
        if ( $document->close() )
            return redirect()->back()           // ->route($this->model_path.'.index')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


    protected function unclose(Document $document)
    {

        if ( $document->status != 'closed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is not closed', 'layouts'));
        }

        // Unclose (back to "confirmed" status)
        if ( $document->unclose() )
            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');


        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


    protected function deliver($id, Request $request)
    {
        $document = $this->document->findOrFail($id);

        // Can I?
        if ( $document->status != 'closed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is not closed', 'layouts'));
        }

        if ( $document->onhold )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is on-hold', 'layouts'));
        }

        // Deliver
        if ( $document->deliver() )
            return redirect()->back()           // ->route($this->model_path.'.index')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }

    protected function undeliver($id, Request $request)
    {
        $document = $this->document->findOrFail($id);

        // Can I?
        if ( $document->status != 'closed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is not closed', 'layouts'));
        }
/*
        if ( $document->onhold )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is on-hold', 'layouts'));
        }
*/
        // unDeliver
        if ( $document->undeliver() )
            return redirect()->back()           // ->route($this->model_path.'.index')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }




    protected function getTodaysShippingSlips()
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $documents = $this->document
                            ->where('delivery_date', \Carbon\Carbon::now())
                            ->orWhere('delivery_date', null)
                            ->with('supplier')
//                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('delivery_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath('today');

        return view($this->view_path.'.index_for_today', $this->modelVars() + compact('documents'));
    }


    protected function getInvoiceableShippingSlips($id, Request $request)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList = Sequence::listFor( SupplierInvoice::class );

        $templateList = Template::listFor( SupplierInvoice::class );

        $payment_methodList = PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        $statusList = SupplierInvoice::getStatusList();

        // Make sense:
        unset($statusList['canceled']);

        $supplier = $this->supplier->findOrFail($id);

        $documents = $this->document
                            ->where('supplier_id', $id)
                            ->where('status', 'closed')
                            ->where('invoiced_at', null)
//                            ->with('supplier')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath('invoiceables');

        return view($this->view_path.'.index_by_supplier_invoiceables', $this->modelVars() + compact('supplier', 'documents', 'sequenceList', 'templateList', 'statusList', 'items_per_page', 'payment_methodList'));
    }


    public function createGroupInvoice( Request $request )
    {
        //
        $document_list = $request->input('document_group', []);

        if ( count( $document_list ) == 0 ) 
            return redirect()->route('supplier.invoiceable.shippingslips', $request->input('supplier_id'))
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['document_date'], $request );

        $rules = $this->document::$rules_createinvoice;

        $this->validate($request, $rules);

        // Set params for group
        $params = $request->only('supplier_id', 'template_id', 'sequence_id', 'document_date', 'status', 'group_by_shipping_address', 'payment_method_id', 'testing');

        // abi_r($params, true);

        // => old schools :: return $this->invoiceDocumentList( $document_list, $params );


        // Start Logger
        $logger = ActivityLogger::setup( 'Invoice Supplier Shipping Slips', __METHOD__ )
                    ->backTo( route('supplier.invoiceable.shippingslips', $params['supplier_id']) );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $logger->log("INFO", 'Se facturarán los Albaranes del Proveedor: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => $params['supplier_id']]);

        $logger->log("INFO", 'Se facturarán los Albaranes: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $document_list)]);

        $logger->log("INFO", 'Se facturarán un total de <span class="log-showoff-format">{nbr}</span> Albaranes del Proveedor.', ['nbr' => count($document_list)]);

        $flattened = $params;
        array_walk($flattened, function(&$value, $key) {
            $value = "{$key} => {$value}";
        });

        $logger->log("INFO", 'Opciones:  <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $flattened)]);


        $params['logger'] = $logger;


        $result = SupplierShippingSlip::invoiceDocumentList( $document_list, $params );
        if ( is_array($result) && array_key_exists('error', $result))
        {
            return redirect()->back()
                    ->with('error', $result['error']);
        }



        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han facturado los Albaranes seleccionados <strong>:file</strong> .', ['file' => '']));

        // return redirect()->back()
        //         ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts'));

//        return redirect('supplierinvoices/'.optional($invoice)->id.'/edit')
//                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => optional($invoice)->id], 'layouts'));
    } 

    public function createInvoice($id)
    {
        $document = $this->document
                            ->with('supplier')
                            ->findOrFail($id);
        
        $document_list = [$id];

        $supplier = $document->supplier;

        $params = [
            'supplier_id'   => $supplier->id, 
            'template_id'   => $supplier->getInvoiceTemplateId(), 
            'sequence_id'   => $supplier->getInvoiceSequenceId(), 
            'document_date' => \Carbon\Carbon::now()->toDateString(),
            
            'status'        => 'closed', 
            'payment_method_id' => $document->getPaymentMethodId(),
        ];
        

        // Start Logger
        $logger = ActivityLogger::setup( 'Invoice Supplier Shipping Slips', __METHOD__ )
                    ->backTo( route('supplier.invoiceable.shippingslips', $params['supplier_id']) );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $logger->log("INFO", 'Se facturarán los Albaranes del Proveedor: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => $params['supplier_id']]);

        $logger->log("INFO", 'Se facturarán los Albaranes: <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $document_list)]);

        $logger->log("INFO", 'Se facturarán un total de <span class="log-showoff-format">{nbr}</span> Albaranes del Proveedor.', ['nbr' => count($document_list)]);

        $flattened = $params;
        array_walk($flattened, function(&$value, $key) {
            $value = "{$key} => {$value}";
        });

        $logger->log("INFO", 'Opciones:  <span class="log-showoff-format">{suppliers}</span> .', ['suppliers' => implode(', ', $flattened)]);


        $params['logger'] = $logger;


        $result = SupplierShippingSlip::invoiceDocumentList( $document_list, $params );
        if ( is_array($result) && array_key_exists('error', $result))
        {
            return redirect()->back()
                    ->with('error', $result['error']);
        }
        
        // return $this->invoiceDocumentList( [$id], $params );


        $logger->stop();

        $invoice = $document->supplierinvoice();

        return redirect('supplierinvoices/'.$invoice->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $invoice->id], 'layouts'));
    }



    
    // Deprecated?
    public function invoiceDocumentList( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $supplier = $this->supplier
                                ->with('currency')
                                ->findOrFail($params['supplier_id']);

            $documents = $this->document
                                ->where('status', 'closed')
                                ->where('invoiced_at', null)
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('supplier')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
                                ->orderBy('document_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->findOrFail( $list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }

//        4.- Cear cabecera

        // Header
        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'supplier_id' => $supplier->id,
//            'user_id' => $this->,

            'sequence_id' => $params['sequence_id'],

            'created_via' => 'aggregate_shipping_slips',

            'document_date' => $params['document_date'],

            'currency_conversion_rate' => $supplier->currency->conversion_rate,
//            'down_payment' => $this->down_payment,

            'document_discount_percent' => array_key_exists('document_discount_percent', $params) ?
                                                 $params['document_discount_percent'] : $supplier->discount_percent,

            'document_ppd_percent'      => array_key_exists('document_ppd_percent', $params) ?
                                                 $params['document_ppd_percent']      : $supplier->discount_ppd_percent,


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
//            'shipping_method_id' => $this->shipping_method_id ?? $this->supplier->shipping_method_id ?? Configuration::getInt('DEF_CUSTOMER_SHIPPING_METHOD'),
//            'carrier_id' => $this->carrier_id,
            'sales_rep_id' => $supplier->sales_rep_id,
            'currency_id' => $supplier->currency->id,
            'payment_method_id' => $supplier->getPaymentMethodId(),
            'template_id' => $params['template_id'],
        ];

        // Model specific data
        $extradata = [
            'type' => 'invoice',
            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];


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

        // Initialize line sort order
        $i = 0;

        foreach ($documents as $document) {
            # code...
            $i++;

            // Text line announces Shipping Slip
            $line_data = [
                'line_sort_order' => $i*10, 
                'line_type' => 'comment', 
                'name' => l('Shipping Slip: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)]),
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
            ];

            $invoice_line = SupplierInvoiceLine::create( $line_data );

            $invoice->lines()->save($invoice_line);

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
                $data['locked'] = ( $line->line_type == 'comment' ? 0 : 1 ); 

                // Model specific data
                $extradata = [
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
                    unset( $data[$this->getParentModelSnakeCase().'_line_id'] );
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

            // Not so fast, Sony Boy

            $invoice->makeTotals();

            // Manage Invoice Status
            switch ( $params['status'] ) {
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




        // abi_r($grouped_lines, true);



        return redirect('supplierinvoices/'.$invoice->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $invoice->id], 'layouts'));





//        3.- Si algún documento tiene plantilla diferente, generar factura para él <= Tontá: el albarán NO tiene plantilla de Factura

//        6.- Crear línea de texto con los albaranes ???

//        7.- Crear líneas agrupadas ???

//        8.- Manage estados de documento, pago y stock


        // Prepare Logger
        $logger = \aBillander\WooConnect\WooOrderImporter::logger();

        $logger->empty();
        $logger->start();

        // Do the Mambo!
        foreach ( $list as $oID ) 
        {
            $logger->log("INFO", 'Se descargará el Pedido: <span class="log-showoff-format">{oid}</span> .', ['oid' => $oID]);

            $importer = \aBillander\WooConnect\WooOrderImporter::processOrder( $oID );
        }

        $logger->stop();

        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $logger->id], 'layouts'));
    }





    /*
    |--------------------------------------------------------------------------
    | Not CRUD stuff here
    |--------------------------------------------------------------------------
    */


/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxLineSearch(Request $request)
    {
        // Request data
        $line_id         = $request->input('line_id');
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id', 0);
        $supplier_id     = $request->input('supplier_id');
        $sales_rep_id    = $request->input('sales_rep_id', 0);
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $supplier_id, $currency_id";

        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('tax')->find(intval($product_id));
        }

        $supplier = Supplier::find(intval($supplier_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
            $sales_rep = SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
            $sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == Context::getContext()->currency->id) ?
                    Context::getContext()->currency :
                    Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$supplier || !$currency ) {
            // Die silently
            return '';
        }

        $tax = $product->tax;

        // Calculate price per $supplier_id now!
        $price = $product->getPriceBySupplier( $supplier, 1, $currency );
        $tax_percent = $tax->getFirstRule()->percent;
        $price->applyTaxPercent( $tax_percent );

        $data = [
//          'id' => '',
            'line_sort_order' => '',
            'line_type' => 'product',
            'product_id' => $product->id,
            'combination_id' => $combination_id,
            'reference' => $product->reference,
            'name' => $product->name,
            'quantity' => 1,
            'cost_price' => $product->cost_price,
            'unit_price' => $product->price,
            'unit_supplier_price' => $price->getPrice(),
            'unit_final_price' => $price->getPrice(),
            'unit_final_price_tax_inc' => $price->getPriceWithTax(),
            'unit_net_price' => $price->getPrice(),
            'sales_equalization' => $supplier->sales_equalization,
            'discount_percent' => 0.0,
            'discount_amount_tax_incl' => 0.0,
            'discount_amount_tax_excl' => 0.0,
            'total_tax_incl' => 0.0,
            'total_tax_excl' => 0.0,
            'tax_percent' => $product->as_percentable($tax_percent),
            'commission_percent' => $sales_rep->commission_percent,
            'notes' => '',
            'locked' => 0,
//          'supplier_invoice_id' => '',
            'tax_id' => $product->tax_id,
            'sales_rep_id' => $sales_rep->id,
        ];

        $line = new DocumentLine( $data );

        return view($this->view_path.'._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
    }


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxLineOtherSearch(Request $request)
    {
        // Request data
        $line_id         = $request->input('line_id');
        $other_json      = $request->input('other_json');
        $supplier_id     = $request->input('supplier_id');
        $sales_rep_id    = $request->input('sales_rep_id', 0);
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $supplier_id, $currency_id";

        if ($other_json) {
            $product = (object) json_decode( $other_json, true);
        } else {
            $product = $other_json;
        }

        $supplier = Supplier::find(intval($supplier_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
            $sales_rep = SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
            $sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == Context::getContext()->currency->id) ?
                    Context::getContext()->currency :
                    Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$supplier || !$currency ) {
            // Die silently
            return '';
        }

        $tax = Tax::find($product->tax_id);

        // Calculate price per $supplier_id now!
        $amount_is_tax_inc = Configuration::get('PRICES_ENTERED_WITH_TAX');
        $amount = $amount_is_tax_inc ? $product->price_tax_inc : $product->price;
        $price = new Price( $amount, $amount_is_tax_inc, $currency );
        $tax_percent = $tax->getFirstRule()->percent;
        $price->applyTaxPercent( $tax_percent );

        $data = [
//          'id' => '',
            'line_sort_order' => '',
            'line_type' => $product->line_type,
            'product_id' => 0,
            'combination_id' => 0,
            'reference' => DocumentLine::getTypeList()[$product->line_type],
            'name' => $product->name,
            'quantity' => 1,
            'cost_price' => $product->cost_price,
            'unit_price' => $product->price,
            'unit_supplier_price' => $price->getPrice(),
            'unit_final_price' => $price->getPrice(),
            'unit_final_price_tax_inc' => $price->getPriceWithTax(),
            'unit_net_price' => $price->getPrice(),
            'sales_equalization' => $supplier->sales_equalization,
            'discount_percent' => 0.0,
            'discount_amount_tax_incl' => 0.0,
            'discount_amount_tax_excl' => 0.0,
            'total_tax_incl' => 0.0,
            'total_tax_excl' => 0.0,
            'tax_percent' => $price->as_percentable($tax_percent),
            'commission_percent' => $sales_rep->commission_percent,
            'notes' => '',
            'locked' => 0,
//          'supplier_invoice_id' => '',
            'tax_id' => $product->tax_id,
            'sales_rep_id' => $sales_rep->id,
        ];

        $line = new DocumentLine( $data );

        return view($this->view_path.'._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
    }

}
