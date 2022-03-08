<?php

namespace App\Http\Controllers;

// 

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Supplier;
use App\SupplierInvoice;
use App\SupplierInvoiceLine;

use App\SupplierShippingSlip;

use App\Configuration;
use App\Sequence;
use App\PaymentMethod;

use App\Events\SupplierShippingSlipConfirmed;

class SupplierInvoicesController extends BillableController
{

   public function __construct(Supplier $supplier, SupplierInvoice $document, SupplierInvoiceLine $document_line)
   {
        parent::__construct();

        $this->model_class = SupplierInvoice::class;

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
                            ->with('currency')
                            ->with('paymentmethod')
//                            ->orderBy('document_date', 'desc')
                            // ->orderBy('document_reference', 'desc');
// https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
                            ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');
//                          ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath($this->model_path);

        $statusList = $this->model_class::getStatusList();

        $payment_statusList = $this->model_class::getPaymentStatusList();

        $payment_methodList = PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view($this->view_path.'.index', $this->modelVars() + compact('documents', 'statusList', 'payment_statusList', 'payment_methodList'));
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

        $documents->setPath($id);

        $statusList = $this->model_class::getStatusList();

        $payment_statusList = $this->model_class::getPaymentStatusList();

        $payment_methodList = PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view($this->view_path.'.index_by_supplier', $this->modelVars() + compact('supplier', 'documents', 'items_per_page', 'statusList', 'payment_statusList', 'payment_methodList'));
    }

    /**
     * Show the form for creating a new supplierinvoice
     *
     * @return Response
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

        $payments = \App\PaymentMethod::count();
        if ( !$payments )
            return redirect($this->model_path)
                ->with('error', l('There is not any Payment Method &#58&#58 You must create one first', [], 'layouts'));


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
        
        if ( !(count($sequenceList)>0) )
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
     * Store a newly created supplierinvoice in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

        $rules['shipping_address_id'] = str_replace('{supplier_id}', $request->input('supplier_id'), $rules['shipping_address_id']);
        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        $supplier = Supplier::with('addresses')->findOrFail(  $request->input('supplier_id') );

        // Extra data
//        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
//        $doc_id = $seq->getNextDocumentId();

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

//                      'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),
                        'sequence_id'          => $request->input('sequence_id') ?? $supplier->getInvoiceSequenceId(),

                        'template_id'          => $request->input('template_id') ?? $supplier->getInvoiceTemplateId(),

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
//        if (  Configuration::isFalse('SUPPLIER_ORDERS_NEED_VALIDATION') )
//            $supplierOrder->confirm();

        return redirect($this->model_path.'/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        /* *********************************************************************** */

#       $document = $this->storeOrUpdate( $request );

        /* *********************************************************************** */
    }

    public function storeOrUpdate( Request $request, $id = null )
    {
        $supplier_id = intval($request->input('supplier_id', 0));

        /* *********************************************************************** */


        // Do the Mambo!
        $document = ( $id == null ) 
                            ? new SupplierInvoice() 
                            : $this->document->findOrFail($id);

        // STEP 1 : validate data

        // (Basic) Check Shipping Address
        if ( $request->input('shipping_address_id') < 1 ) 
            $request->merge( array('shipping_address_id' => $request->input('invoicing_address_id')) );

        $document_date = $request->input('document_date_form') ?
                          \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('document_date_form') ) : 
                          \Carbon\Carbon::now();
        
        $delivery_date = $request->input('delivery_date_form') ?
                          \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('delivery_date_form') ) :
                          null;

        $dates = [
                        'document_date' => $document_date,
                        'delivery_date' => $delivery_date,
                 ];
        $request->merge( $dates );


        $rules = SupplierInvoice::$rules;
        // Complete rules for selected Supplier
        foreach ($rules as $k => $v) {
            $rules[$k] = str_replace('{supplier_id}', $supplier_id, $v);
        }
        $rules['nbrlines'] = 'required|numeric|min:' . count( $request->input('lines', []) );

        if ( !$request->input('delivery_date') ) unset( $rules['delivery_date'] );


        $this->validate($request, $rules);

// abi_r($request->all(), true);

        // STEP 2 : build objects

        if ( !$document->document_reference )
        if ( $request->input('save_as', 'draft') == 'invoice' ) {
            $seq = \App\Sequence::find( $request->input('sequence_id') );
            $doc_id = $seq->getNextDocumentId();
            $extradata = [  'document_prefix'      => $seq->prefix,
                            'document_id'          => $doc_id,
                            'document_reference'   => $seq->getDocumentReference($doc_id),
                            'status'               => 'pending',
                         ];
            $request->merge( $extradata );
        }

//      $document = $this->document->create($request->all());
        $document->fill($request->all());
        $document->save();


        // 
        // Lines stuff
        // 

        // STEP 3 : Delete current lines

        // $document->lines()->delete();
        foreach( $document->lines as $line)
        {
            if ( $line->locked ) continue;      // Skip locked lines
            
            $line->delete();                    // Trigger ondelete events
        }

        // STEP 4 : Create new lines

        // Prepare Invoice totals
        $total_tax_incl = 0.0;
        $total_tax_excl = 0.0;

        $line = $this->document_line;

        // Loop...
//      $address  = \App\Address::find( $request->input('invoicing_address_id'));
        $supplier = \App\Supplier::with('address')->find( $document->supplier_id );
        $address  = $supplier->address;
//      $n = intval($request->input('nbrlines', 0));
        $form_lines = $request->input('lines');

        // Locked lines :: Add ammounts to document total
        foreach( $document->lines as $line)     // only locked lines are left
        {
            $total_tax_incl += $line->total_tax_incl;
            $total_tax_excl += $line->total_tax_excl;

//              abi_r($total_tax_incl.' - '.$total_tax_excl.' - '.$line->total_tax_incl.' - '.$line->total_tax_excl);
        }

        // Regular lines
        for($i = 0; $i < $request->input('nbrlines'); $i++)
        {
            if ( !$request->has('lines.'.$i.'.lineid') ) continue;  // Line was deleted on View

            if ( $form_lines[$i]['locked'] ) {      // Skip locked lines
                continue;
            }   // Skip locked lines

            if ( !$request->has('lines.'.$i.'.sales_equalization') ) $form_lines[$i]['sales_equalization'] = 0;
            // Controller: $request->merge(['sales_equalization' => $request->input('sales_equalization', 0)]);

            $line = $this->document_line->create( $form_lines[$i] );

            // Calculate Taxes & Totals
            if ($form_lines[$i]['product_id']>0) {
                $product  = \App\Product::with('tax')->find( $form_lines[$i]['product_id']);
                $tax = $product->tax;
            } else  {
                // No database persistance, please!
                $product  = new \App\Product(['product_type' => 'simple', 'name' => $line->name, 'tax_id' => $line->tax_id]);
                $tax = $product->tax;
            }
            $supplier->sales_equalization = $line->sales_equalization;
            $rules = $product->getTaxRules( $address,  $supplier );

            $base_price = $line->quantity*$line->unit_final_price*(1.0-$line->discount_percent/100.0) - $line->discount_amount_tax_excl;                // unit_net_price = unit_final_price - discount_percent

            // Don't know a value for $line->total_tax_excl, since $base_price should be rounded
            $IKnowBase = false;
            $line->total_tax_excl = 0.0;
            $line->total_tax_incl = 0.0;    // After this, loop to add line taxes

            foreach ( $rules as $rule ) {
                $line_tax = new \App\SupplierInvoiceLineTax();

                $line_tax->name = $tax->name . ' | ' . $rule->name;
                $line_tax->tax_rule_type = $rule->rule_type;

                $p = \App\Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $document->currency, $document->currency_conversion_rate);

                if ($IKnowBase == false) {
                    $p->applyRounding( );

                    $line->total_tax_incl = $line->total_tax_excl = $base_price = $p->getPrice();
                    // Establish $base_price. We do not want different values in different lines due to rounding
                    $IKnowBase = !$IKnowBase;
                } else {
                    $p->applyRoundingOnlyTax( );
                }

                $line_tax->taxable_base = $base_price;
                $line_tax->percent = $rule->percent;
                $line_tax->amount = $rule->amount;
                $line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

                $line_tax->position = $rule->position;

                $line_tax->tax_id = $tax->id;
                $line_tax->tax_rule_id = $rule->id;

                $line_tax->save();
                $line->total_tax_incl += $line_tax->total_line_tax;

                $line->SupplierInvoiceLineTaxes()->save($line_tax);
            }

//          $line->save();

            $document->SupplierInvoiceLines()->save($line);

            $total_tax_incl += $line->total_tax_incl;
            $total_tax_excl += $line->total_tax_excl;

        }

        $p = \App\Price::create([$total_tax_excl, $total_tax_incl], $document->currency, $document->currency_conversion_rate);
        $p->applyDiscountPercent( $document->document_discount );

        $document->total_tax_excl = $p->getPrice();
        $document->total_tax_incl = $p->getPriceWithTax();
        // Open balance: only payments can modify it
        $document->open_balance = $document->total_tax_incl;

        $document->save();

        return $document;
    }

    /**
     * Display the specified supplierinvoice.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $cinvoice = $this->document
                            ->with('supplier')
                            ->with('invoicingAddress')
                            ->with('lines')
                            ->with('currency')
                            ->with('payments')
                            ->findOrFail($id);

        $company = \App\Context::getContext()->company;

//      abi_r($cinvoice, true);

        return view($this->view_path.'.show', compact('cinvoice', 'company'));
    }

    /**
     * Show the form for editing the specified supplierinvoice.
     *
     * @param  int  $id
     * @return Response
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

        return view($this->view_path.'.edit', $this->modelVars() + compact('supplier', 'invoicing_address', 'addressBook', 'addressbookList', 'document', 'sequenceList', 'templateList'));
    }

    /**
     * Update the specified supplierinvoice in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, SupplierInvoice $supplierinvoice)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );

        $rules = $this->document::$rules;

//        $rules['shipping_address_id'] = str_replace('{supplier_id}', $request->input('supplier_id'), $rules['shipping_address_id']);
//        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

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
                        'status'               =>  \App\Configuration::get('SUPPLIER_ORDERS_NEED_VALIDATION') ? 'draft' : 'confirmed',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );
*/

        $document = $supplierinvoice;

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



        /* *********************************************************************** */

#       $document = $this->storeOrUpdate( $request, $id );

        /* *********************************************************************** */






        /* *********************************************************************** */

        $nextAction = $request->input('nextAction', '');

        if ( $nextAction == 'showInvoice' ) 
            return $this->show($document->id);
        
        if ( $nextAction == 'completeInvoice' ) 
            return redirect('supplierinvoices/' . $document->id . '/edit')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
        
        if ( $nextAction == 'saveAndContinue' ) 
            return redirect('supplierinvoices/' . $document->id . '/edit')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        return redirect('supplierinvoices')
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }

    /**
     * Remove the specified supplierinvoice from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $document = $this->document->findOrFail($id);

        if( !$document->deletable )
            return redirect()->back()
                ->with('error', l('This record cannot be deleted because its Status &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        $shippingslips = $document->shippingslips;

        foreach ($shippingslips as $shippingslip) {
            # code...
            $shippingslip->invoiced_at = null;
            $shippingslip->save();
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

    protected function confirm(SupplierInvoice $document)
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

    protected function unConfirm(SupplierInvoice $document)
    {
        // Can I?
        if ( $document->status != 'confirmed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document has no Lines', 'layouts'));
        }

        // UnConfirm
        if ( $document->unConfirm() )
            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


    protected function onholdToggle(SupplierInvoice $document)
    {
        // No checks. A closed document can be set to "onhold". Maybe usefull...

        // Toggle
        $toggle = $document->onhold > 0 ? 0 : 1;
        $document->onhold = $toggle;
        
        $document->save();

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');
    }


    protected function close(SupplierInvoice $document)
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


    protected function unclose(SupplierInvoice $document)
    {

        if ( $document->status != 'closed' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document is not closed', 'layouts'));
        }

        if ( ! $document->uncloseable )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' :: '.l('Document has Payments', 'layouts'));
        }

        // Unclose (back to "confirmed" status)
        if ( $document->unclose() )
            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts').' ['.$document->document_reference.']');


        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }





    public function addShippingSlipToInvoice($id, Request $request)
    {
        $invoice = SupplierInvoice::where('status', '!=', 'closed')->findOrFail($id);

        // Get Shipping Slip
        $document_id = $request->input('invoiceable');
        
        $document = SupplierShippingSlip::with('lines')->where('document_reference', $document_id)->first();

        if ( !$document )
            $document = SupplierShippingSlip::with('lines')->where('id', $document_id)->first();

        if ( !$document )
            return redirect()->back()
                    ->with('error', l('Unable to load this record &#58&#58 (:id) ', ['id' => $document_id], 'layouts'));

        // Can add Document? (is invoiceable?)
        if ( ($document->status != 'closed') || ($document->invoiced_at != null) )
            return redirect()->back()
                    ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document_id], 'layouts') . l('Document is not closed', 'layouts'));


        // Wanna dance?
        $document->load('lines', 'lines.linetaxes');


        // Set params
        $params = [];
        $new_invoice = \App\SupplierShippingSlip::addDocumentToInvoice( $document, $invoice, $params );

        if ( !$new_invoice )
            return redirect()->back()
                    ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $document_id], 'layouts') . l('Document is not closed', 'layouts'));

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $new_invoice->id], 'layouts'));



        abi_r($id);
        abi_r($request->toArray());die();





        $document = $this->document
                            ->with('supplier')
                            ->findOrFail($id);
        
        // Get Invoice for this Shipping Slip
        $invoice = $document->supplierinvoice();

        // Get Lines to delete
        $lines = $invoice->lines->where('supplier_shipping_slip_id', $document->id);

        foreach ($lines as $line) {
            # code...
            $line->delete();
        }

        // Not so fast, Sony Boy
        $invoice->makeTotals();

        // Final touches
        $document->invoiced_at = null;
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

        $link = \App\DocumentAscription::where( $link_data )->first();

        $link->delete();

        // Good boy, so far

        // abi_r($lines->pluck('id')->toArray());die();

        // abi_r($invoice);die();        
        
        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
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
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $supplier_id, $currency_id";

        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->find(intval($product_id));
        }

        $supplier = \App\Supplier::find(intval($supplier_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
            $sales_rep = \App\SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
            $sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

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

        $line = new SupplierInvoiceLine( $data );

        return view('supplier_invoices._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
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
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);

//        return "$product_id, $combination_id, $supplier_id, $currency_id";

        if ($other_json) {
            $product = (object) json_decode( $other_json, true);
        } else {
            $product = $other_json;
        }

        $supplier = \App\Supplier::find(intval($supplier_id));

        $sales_rep = null;
        if ($sales_rep_id>0)
            $sales_rep = \App\SalesRep::find(intval($sales_rep_id));
        if (!$sales_rep)
            $sales_rep = (object) ['id' => 0, 'commission_percent' => 0.0]; 
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$supplier || !$currency ) {
            // Die silently
            return '';
        }

        $tax = \App\Tax::find($product->tax_id);

        // Calculate price per $supplier_id now!
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
            'reference' => SupplierInvoiceLine::getTypeList()[$product->line_type],
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

        $line = new SupplierInvoiceLine( $data );

        return view('supplier_invoices._invoice_line', [ 'i' => $line_id, 'line' => $line ] );
    }

}
