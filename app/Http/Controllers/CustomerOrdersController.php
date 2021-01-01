<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
use App\MeasureUnit;

use App\Events\CustomerOrderConfirmed;

use App\Traits\BillableGroupableControllerTrait;
use App\Traits\BillableShippingSlipableControllerTrait;
use App\Traits\BillableProductionSheetableControllerTrait;

class CustomerOrdersController extends BillableController
{

   use BillableGroupableControllerTrait;
   use BillableShippingSlipableControllerTrait;
   use BillableProductionSheetableControllerTrait;

   public function __construct(Customer $customer, Document $document, DocumentLine $document_line)
   {
        parent::__construct();

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
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );
        
        $model_path = $this->model_path;
        $view_path = $this->view_path;


        
        if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
        {
            $documents = $this->document
                                ->filter( $request->all() )
                                ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
    //                            ->orderBy('document_date', 'desc')
                                // ->orderBy('document_reference', 'desc');
    // https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
                                // ->orderByRaw('DATE(document_date) DESC, document_reference IS NOT NULL, document_reference DESC');
                                ->orderByRaw('document_reference IS NOT NULL, document_reference DESC, id DESC');
    //                          ->orderBy('id', 'desc');        // ->get();
            
        } else {
            $documents = $this->document
                                ->filter( $request->all() )
                                ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
    //                            ->orderBy('document_date', 'desc')
                                // ->orderBy('document_reference', 'desc');
    // https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
                                ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');
    //                          ->orderBy('id', 'desc');        // ->get();

        }

        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath($this->model_path);
        
        if ( Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
        {
            //
            $dest_clientes = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CCLCFG');

            $dest_pedidos  = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CPVCFG');
            
            // Calculate last minute stuff!
            try {

                $anyClient = count(File::files( $dest_clientes ));

                $anyOrder  = count(File::files( $dest_pedidos ));

            } catch (\Exception $e) {

                $anyClient = 0;

                $anyOrder  = 0;

                // dd($e->getMessage());

            }


            // The difference between files and allFiles is that allFiles will recursively search sub-directories unlike files. 
        } else {

            $anyClient = 0;
            
            $anyOrder  = 0;
        }

        
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

        return view($this->view_path.'.index'.$suffix, $this->modelVars() + compact('documents', 'anyClient', 'anyOrder', 'statusList', 'manufacturing_statusList', 'wooc_statusList'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexByCustomer($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );
        
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList = Sequence::listFor( 'App\\CustomerInvoice' );

        $templateList = Template::listFor( 'App\\CustomerInvoice' );

        $customer = $this->customer->findOrFail($id);

        $documents = $this->document
                            ->filter( $request->all() )
                            ->where('customer_id', $id)
//                            ->with('customer')
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

        return view($this->view_path.'.index'.$suffix.'_by_customer', $this->modelVars() + compact('customer', 'documents', 'sequenceList', 'templateList', 'items_per_page', 'statusList', 'manufacturing_statusList', 'wooc_statusList'));
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
    public function createWithCustomer($customer_id)
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
            $customer = Customer::with('addresses')->findOrFail( $customer_id );

        } catch(ModelNotFoundException $e) {
            // No Customer available, ask for one
            return redirect()->back()
                    ->with('error', l('The record with id=:id does not exist', ['id' => $customer_id], 'layouts'));
        }
        
        return view($this->view_path.'.create', $this->modelVars() + compact('sequenceList', 'customer_id'));
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

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        $customer = Customer::with('addresses')->findOrFail(  $request->input('customer_id') );

        // Extra data
//        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
//        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

                        'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),
                        
                        'template_id'          => $request->input('template_id') ?? $customer->getOrderTemplateId(),

                        'document_discount_percent' => $customer->discount_percent,
                        'document_ppd_percent'      => $customer->discount_ppd_percent,

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
        if (  Configuration::isFalse('CUSTOMER_ORDERS_NEED_VALIDATION') )
            $document->confirm();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect($this->model_path.'/'.$id.'/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
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

        $customer = Customer::find( $document->customer_id );

        $addressBook       = $customer->addresses;

        $theId = $customer->invoicing_address_id;
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

        return view($this->view_path.'.edit', $this->modelVars() + compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'document', 'sequenceList', 'templateList', 'volume_unit', 'weight_unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $customerorder)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
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
        $document = $customerorder;

        $need_update_totals = (
            $request->input('document_ppd_percent', $document->document_ppd_percent) != $document->document_ppd_percent 
        ) ? true : false;

        $document->fill($request->all());

        // Reset Export date
        if ( Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
        if ( $request->input('export_date_form', '') == '' ) $document->export_date = null;

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
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = $this->document->findOrFail($id);

        if ( Configuration::isTrue('ENABLE_MANUFACTURING') && ($document->production_sheet_id > 0) )
            return redirect()->back()
                ->with('error', l('This record cannot be deleted because it belongs to a Production Sheet &#58&#58 (:id) ', ['id' => $document->production_sheet_id], 'productionsheets'));

        if( !$document->deletable )
            return redirect()->back()
                ->with('error', l('This record cannot be deleted because its Status &#58&#58 (:id) ', ['id' => $id], 'layouts'));

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
            return redirect()->back()       // ->route($this->model_path.'.index')
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
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: ');
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

        // Close
        if ( $document->close() )
            return redirect()->back()       // ->route($this->model_path.'.index')
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




    protected function getTodaysOrders()
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $documents = $this->document
                            ->where('delivery_date', \Carbon\Carbon::now())
                            ->orWhere('delivery_date', null)
                            ->with('customer')
//                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('delivery_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath('today');

        return view($this->view_path.'.index_for_today', $this->modelVars() + compact('documents'));
    }


    protected function getShippingSlipableOrders($id, Request $request)
    {
        $model_path = $this->model_path;
        $view_path = $this->view_path;

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $sequenceList       = Sequence::listFor( 'App\\CustomerShippingSlip' );
        $order_sequenceList = Sequence::listFor( Document::class );

        $templateList = Template::listFor( 'App\\CustomerShippingSlip' );

        $statusList = CustomerShippingSlip::getStatusList();
        $order_statusList = Document::getStatusList();

        // Make sense:
        unset($statusList['closed']);
        unset($statusList['canceled']);
        unset($order_statusList['closed']);
        unset($order_statusList['canceled']);

        $customer = $this->customer->findOrFail($id);

        $documents = $this->document
                            ->where('customer_id', $id)
                            ->where('status', '<>', 'closed')
//                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc')
                            ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        // abi_r($this->model_path, true);

        $documents->setPath('shippingslipables');

        
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

        return view($this->view_path.'.index'.$suffix.'_by_customer_shippingslipables', $this->modelVars() + compact('customer', 'documents', 'sequenceList', 'order_sequenceList', 'templateList', 'statusList', 'order_statusList', 'items_per_page'));
    }


    public function createAggregateOrder( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->route('customer.shippingslipable.orders', $request->input('customer_id'))
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['order_document_date'], $request );

        $rules = $this->document::$rules_createaggregate;

        $this->validate($request, $rules);

        // Set params for group
        $params = [
            'customer_id'   => $request->input('customer_id'),
            'template_id'   => $request->input('order_template_id'),
            'sequence_id'   => $request->input('order_sequence_id'),
            'document_date' => $request->input('order_document_date'),
            'status'        => $request->input('order_status'),
        ];

        return $this->aggregateDocumentList( $document_group, $params );
    }



/*    
*  public function createGroupShippingSlip( Request $request )
*/



/*    
*  public function aggregateDocumentList( $list, $params )
*/



/*    
*  public function shippingslipDocumentList( $list, $params )
*/





    public function getDocumentAvailability($id, Request $request)
    {
        // abi_r($request->all(), true);

        $onhand_only = $request->input('onhand_only', 0);

        $document = $this->document
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        if ($onhand_only)
            foreach ($document->lines as $line) {
                # code...
                if ( $line->line_type == 'product' ) {
                    $quantity = $line->quantity;
                    $onhand   = $line->product->quantity_onhand > 0 ? $line->product->quantity_onhand : 0;
    
                    $line->quantity_onhand =  $quantity > $onhand ? $onhand : $quantity;

                } else {
                    $line->quantity_onhand = $line->quantity;

                }
            }
        else
            foreach ($document->lines as $line) {
                # code...

                $line->quantity_onhand = $line->quantity;
            }

        $sequenceList = Sequence::listFor( 'App\\CustomerShippingSlip' );

        $templateList = Template::listFor( 'App\\CustomerShippingSlip' );

        return view($this->view_path.'._panel_document_availability', $this->modelVars() + compact('document', 'sequenceList', 'templateList', 'onhand_only'));
    }


    public function createSingleShippingSlip( Request $request )
    {
        $document = $this->document
                            ->with('customer')
                            ->with('currency')
                            ->with('lines')
                            ->findOrFail( $request->input('document_id') );
        
        $customer = $document->customer;

        $dispatch = $request->input('dispatch', []);

        // To do: check document status ???

        if ( $document->lines->where('line_type', 'product')->count() != count( $dispatch ) ) 
            return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['shippingslip_date'], $request );

        $rules = $this->document::$rules_createshippingslip;

        $this->validate($request, $rules);

        // Set params
        $params = [
            'customer_id'   => $customer->id, 
            'template_id'   => $request->input('shippingslip_template_id'), 
            'sequence_id'   => $request->input('shippingslip_sequence_id'), 
            'document_date' => $request->input('shippingslip_date'),
            'backorder'     => $request->input('backorder', Configuration::isTrue('ALLOW_CUSTOMER_BACKORDERS')),

            'dispatch'      => $request->input('dispatch', []), 
        ];

        // Header
        $shipping_method_id = $document->shipping_method_id ?? 
                              $document->customer->getShippingMethodId();

        $shipping_method = \App\ShippingMethod::find($shipping_method_id);
        $carrier_id = $shipping_method ? $shipping_method->carrier_id : null;

        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'customer_id' => $customer->id,
//            'user_id' => $this->,

            'sequence_id' => $params['sequence_id'],

            'created_via' => 'aggregate_orders',

            'document_date' => $params['document_date'],

            'currency_conversion_rate' => $document->currency->conversion_rate,
//            'down_payment' => $this->down_payment,

            'document_discount_percent' => $document->document_discount_percent,
            'document_ppd_percent'      => $document->document_ppd_percent,

            'delivery_date' => $document->delivery_date,

//            'total_currency_tax_incl' => $document->total_currency_tax_incl,
//            'total_currency_tax_excl' => $document->total_currency_tax_excl,
//            'total_currency_paid' => $this->total_currency_paid,

//            'total_tax_incl' => $document->total_tax_incl,
//            'total_tax_excl' => $document->total_tax_excl,

//            'commission_amount' => $this->commission_amount,

            // Skip notes

            'status' => 'draft',
            'onhold' => 0,
            'locked' => 0,

            'invoicing_address_id' => $document->invoicing_address_id,
            'shipping_address_id' => $document->shipping_address_id,
            'warehouse_id' => $document->warehouse_id,
            'shipping_method_id' => $shipping_method_id,
            'carrier_id' => $carrier_id,
            'sales_rep_id' => $document->sales_rep_id,
            'currency_id' => $document->currency->id,
            'payment_method_id' => $document->payment_method_id,
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

        // Close Order (if needed)
        $document->confirm();


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

//        foreach ($documents as $document) {
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

            // Need Backorder? We'll see in a moment:
            $need_backorder = false;
            $bo_quantity    = [];       // Backorder quantity

            // Add current Shipping Slip lines to Invoice
            foreach ($document->lines as $line) {
                # code...
                $i++;

                // $shippingslip_line = $line->toInvoiceLine();

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
                // Quantity
                if ( array_key_exists($line->id, $dispatch) && ( $dispatch[$line->id] < $line->quantity ) )
                {
                    $data['quantity'] = $dispatch[$line->id];

                    $need_backorder = true;
                    $bo_quantity[$line->id] = $line->quantity - $dispatch[$line->id];
                }
                // Locked 
                $data['locked'] = 1; 

                // Model specific data
                $extradata = [
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);

                // We do not want zero-quantity lines:
                if ( $data['quantity'] == 0 ) continue;


                // Let's get dirty
                CustomerShippingSlipLine::unguard();
                $shippingslip_line = CustomerShippingSlipLine::create( $data + $extradata );
                CustomerShippingSlipLine::reguard();

                $shippingslip->lines()->save($shippingslip_line);
            }

            // Update lines
            $shippingslip->load(['lines']);

            foreach ($shippingslip->lines as $line) {

//                if ($line->line_type == 'comment')                   continue;

                $shippingslip->updateLine( $line->id, [ 'line_type' => $line->line_type, 'unit_customer_final_price' => $line->unit_customer_final_price ] );
            }



            // Not so fast, Sony Boy

            $shippingslip->makeTotals();

            // Confirm Shipping Slip
            $shippingslip->confirm();
            
            // Final touches
            $document->shipping_slip_at = \Carbon\Carbon::now();
            $document->save();      // Maybe not needed, because we are to close 
            
            $document->close();


            // Document traceability
            //     leftable  is this document
            //     rightable is Customer Shipping Slip Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $shippingslip->id,
                'rightable_type' => CustomerShippingSlip::class,

                'type' => 'traceability',
                ];

            $link = DocumentAscription::create( $link_data );
//        }

        // Good boy, so far

        // Backorder stuff
        if ( $need_backorder && $params['backorder'] )
        {
            // Create Backorder now!

/*
    Header
*/

        // Duplicate
        $clone = $document->replicate();

        // Extra data
        $seq = Sequence::findOrFail( $document->sequence_id );

        $clone->user_id              = \App\Context::getContext()->user->id;

        $clone->document_reference = null;
        $clone->reference = '';
        $clone->reference_customer = '';
        $clone->reference_external = '';

        $clone->created_via          = 'backorder';
        $clone->status               = 'draft';
        $clone->locked               = 0;
        
        $clone->document_date = \Carbon\Carbon::now();
        $clone->payment_date = null;
        $clone->validation_date = null;
        $clone->delivery_date = null;
        $clone->delivery_date_real = null;
        $clone->close_date = null;
        
        $clone->tracking_number = null;

        $clone->parent_document_id = null;

        $clone->production_sheet_id = null;
        $clone->export_date = null;
        
        $clone->secure_key = null;
        $clone->import_key = '';


        $clone->save();

/*
    Backorder lines
*/


        // Duplicate Lines
        foreach ($document->lines as $line) {

            if ( !array_key_exists($line->id, $bo_quantity) )
                continue;

            $clone_line = $line->replicate();

            $clone->lines()->save($clone_line);

            $clone->updateProductLine( $clone_line->id, [ 'quantity' => $bo_quantity[$line->id], 'use_measure_unit_id' => 'measure_unit_id' ] );
        }

        // Save Customer document
        $clone->push();

        // Good boy:
        $clone->confirm();


        $document->backordered_at = \Carbon\Carbon::now();
        $document->save();

            // Document traceability
            //     leftable  is this document
            //     rightable is Customer Shipping Slip Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $clone->id,
                'rightable_type' => $document->getClassName(),

                'type' => 'backorder',
                ];

            $link = DocumentAscription::create( $link_data );

            
            return redirect('customershippingslips/'.$shippingslip->id.'/edit')
                    ->with('warning', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $clone->id], 'layouts').'[ '.l('Backorder').' ]')
                    ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));


        }   // // Backorder stuff ENDS


        return redirect('customershippingslips/'.$shippingslip->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
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
        $customer_id     = $request->input('customer_id');
        $sales_rep_id    = $request->input('sales_rep_id', 0);
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $customer_id, $currency_id";

        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->find(intval($product_id));
        }

        $customer = \App\Customer::find(intval($customer_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
            $sales_rep = \App\SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
            $sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$customer || !$currency ) {
            // Die silently
            return '';
        }

        $tax = $product->tax;

        // Calculate price per $customer_id now!
        $price = $product->getPriceByCustomer( $customer, 1, $currency );
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
            'unit_customer_price' => $price->getPrice(),
            'unit_final_price' => $price->getPrice(),
            'unit_final_price_tax_inc' => $price->getPriceWithTax(),
            'unit_net_price' => $price->getPrice(),
            'sales_equalization' => $customer->sales_equalization,
            'discount_percent' => 0.0,
            'discount_amount_tax_incl' => 0.0,
            'discount_amount_tax_excl' => 0.0,
            'total_tax_incl' => 0.0,
            'total_tax_excl' => 0.0,
            'tax_percent' => $product->as_percentable($tax_percent),
            'commission_percent' => $sales_rep->commission_percent,
            'notes' => '',
            'locked' => 0,
//          'customer_invoice_id' => '',
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
        $customer_id     = $request->input('customer_id');
        $sales_rep_id    = $request->input('sales_rep_id', 0);
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $customer_id, $currency_id";

        if ($other_json) {
            $product = (object) json_decode( $other_json, true);
        } else {
            $product = $other_json;
        }

        $customer = \App\Customer::find(intval($customer_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
            $sales_rep = \App\SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
            $sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$customer || !$currency ) {
            // Die silently
            return '';
        }

        $tax = \App\Tax::find($product->tax_id);

        // Calculate price per $customer_id now!
        $amount_is_tax_inc = Configuration::get('PRICES_ENTERED_WITH_TAX');
        $amount = $amount_is_tax_inc ? $product->price_tax_inc : $product->price;
        $price = new \App\Price( $amount, $amount_is_tax_inc, $currency );
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
            'unit_customer_price' => $price->getPrice(),
            'unit_final_price' => $price->getPrice(),
            'unit_final_price_tax_inc' => $price->getPriceWithTax(),
            'unit_net_price' => $price->getPrice(),
            'sales_equalization' => $customer->sales_equalization,
            'discount_percent' => 0.0,
            'discount_amount_tax_incl' => 0.0,
            'discount_amount_tax_excl' => 0.0,
            'total_tax_incl' => 0.0,
            'total_tax_excl' => 0.0,
            'tax_percent' => $price->as_percentable($tax_percent),
            'commission_percent' => $sales_rep->commission_percent,
            'notes' => '',
            'locked' => 0,
//          'customer_invoice_id' => '',
            'tax_id' => $product->tax_id,
            'sales_rep_id' => $sales_rep->id,
        ];

        $line = new DocumentLine( $data );

        return view($this->view_path.'._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
    }

}
