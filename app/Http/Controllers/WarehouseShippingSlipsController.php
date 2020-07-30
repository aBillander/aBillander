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

        $templateList = $this->document->templateList();

        if ( !(count($sequenceList)>0) )
            return redirect('warehouseshippingslips')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
        
        return view('warehouse_shipping_slips.create', compact('sequenceList', 'templateList'));
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
     * @param  \App\WarehouseShippingSlip  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseShippingSlip $warehouseShippingSlip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WarehouseShippingSlip  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function edit(WarehouseShippingSlip $warehouseShippingSlip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WarehouseShippingSlip  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarehouseShippingSlip $warehouseShippingSlip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WarehouseShippingSlip  $warehouseShippingSlip
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarehouseShippingSlip $warehouseShippingSlip)
    {
        //
    }
}
