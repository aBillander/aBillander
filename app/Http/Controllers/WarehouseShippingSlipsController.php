<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Warehouse;
use App\WarehouseShippingSlip as Document;
use App\WarehouseShippingSlipLine as DocumentLine;

use App\ShippingMethod;
use App\Carrier;

use App\Configuration;
use App\Sequence;
use App\Template;

use App\Traits\DateFormFormatterTrait;

class WarehouseShippingSlipsController extends Controller
{
   
   use DateFormFormatterTrait;

   protected $warehouse, $document, $document_line;

   public function __construct(Warehouse $warehouse, Document $document, DocumentLine $document_line)
   {
        $this->warehouse = $warehouse;
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
        // abi_r('----');die();
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $documents = $this->document
//                            ->filter( $request->all() )
                            ->with('warehouse')
                            ->with('warehousecounterpart')
                            ->with('shippingmethod')
                            ->with('carrier')
                            ->orderBy('document_date', 'desc');
                            // ->orderBy('document_reference', 'desc');
// https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
//                            ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');
//                          ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $documents->setPath('warehouseshippingslips');

        $statusList = Document::getStatusList();

        $shipment_statusList = Document::getShipmentStatusList();

        $carrierList =  Carrier::pluck('name', 'id')->toArray();

        return view('warehouse_shipping_slips.index', compact('documents', 'statusList', 'shipment_statusList', 'carrierList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $sequenceList = $this->document->sequenceList();

        if ( !(count($sequenceList)>0) )
            return redirect('warehouseshippingslips')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));


        $templateList = $this->document->templateList();

        $warehouseList =  Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();

        $shipping_methodList = ShippingMethod::pluck('name', 'id')->toArray();

        $carrierList = Carrier::pluck('name', 'id')->toArray();
        
        return view('warehouse_shipping_slips.create', compact('sequenceList', 'templateList', 'warehouseList', 'shipping_methodList', 'carrierList'));
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

//        $rules['shipping_address_id'] = str_replace('{customer_id}', $request->input('customer_id'), $rules['shipping_address_id']);
//        $rules['invoicing_address_id'] = $rules['shipping_address_id'];

        $this->validate($request, $rules);

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

                        'sequence_id'          => $request->input('sequence_id'), //  ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),

                        'created_via'          => 'manual',
                        'status'               =>  'draft',
                        'locked'               => 0,
                     ];

        $request->merge( $extradata );

        $document = $this->document->create($request->all());

        // Move on
        // Maybe ALLWAYS confirm
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

        return redirect('warehouseshippingslips/'.$document->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function show(Document $warehouseShippingSlip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    // public function edit(Document $warehouseShippingSlip)
    public function edit($id)
    {
        // $document = $warehouseShippingSlip;
        $document = $this->document->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'delivery_date'], $document );

        // Not needed, since document is saved as "confirmed":
        $sequenceList = $this->document->sequenceList();


        $templateList = $this->document->templateList();

        $warehouseList =  Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();

        $shipping_methodList = ShippingMethod::pluck('name', 'id')->toArray();

        $carrierList = Carrier::pluck('name', 'id')->toArray();

        return view('warehouse_shipping_slips.edit', compact('document', 'sequenceList', 'templateList', 'warehouseList', 'shipping_methodList', 'carrierList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $warehouseShippingSlip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $warehouseShippingSlip)
    {
        //
    }
}
