<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProductionSheet;

use App\Configuration;

class ProductionSheetProductionOrdersController extends Controller
{


   protected $productionSheet;

   public function __construct(ProductionSheet $productionSheet)
   {
        $this->productionSheet = $productionSheet;
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
    public function productionordersIndex($id, Request $request)
    {
        $sheet = $this->productionSheet
                        ->with('productionorders')
                        ->with('productionorders.product')
                        ->findOrFail($id);

        return view('production_sheet_production_orders.index', compact('sheet'));
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
    public function finishProductionOrders( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $request->production_sheet_id], 'layouts'));
        
        abi_r('You naughty, naughty!');
        abi_r( $request->all() , true);

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

        return $this->processFinishProductionOrders( $document_group, $params );
    }

    
    /**
     * Create Shipping Slips after a list of Customer Orders (id's).
     * Hard work is done here
     *
     */
    public function processFinishProductionOrders( $list, $params )
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

                    $customers = $documents_by_ws_by_addrr_by_meth->unique('customer_id')->pluck('customer_id')->all();

                    foreach ($customers as $customer_id) {
                        # code...
                        $documents_by_ws_by_addrr_by_meth_by_cust = $documents_by_ws_by_addrr_by_meth->where('customer_id', $customer_id);

                        $customer = $this->customer->find($customer_id);

                        // Do something at last...
                        $extra_params = [
                            'warehouse_id'        => $warehouse_id,
                            'shipping_address_id' => $address_id,
                            'shipping_method_id'  => $method_id,

                            'customer'            => $customer,
                        ];

                        if ( $params['should_group'] > 0 )
                        {
                            $shippingslip = $this->shippingslipDocumentGroup( $documents_by_ws_by_addrr_by_meth_by_cust, $params + $extra_params);

                            $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $shippingslip->id], 'layouts');

                            // abi_r($documents_by_ws_by_addrr_by_meth_by_cust->pluck('id')->all());

                        } else {
                            //
                            $ids = $documents_by_ws_by_addrr_by_meth_by_cust->unique('id')->pluck('id')->all();

                            foreach ($ids as $id) {
                                # code...
                                $docs = $documents_by_ws_by_addrr_by_meth_by_cust->where('id', $id);

                                $shippingslip = $this->shippingslipDocumentGroup( $docs, $params + $extra_params);

                                $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $shippingslip->id], 'layouts');
                            }
                        }
                    }
                }
            }
        }

        // die();

        return redirect()
                ->route('productionsheet.orders', $params['production_sheet_id'])
                ->with('success', $success);

    }

}
