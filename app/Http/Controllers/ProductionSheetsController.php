<?php

namespace App\Http\Controllers;

use App\ProductionSheet;
// use App\ProductionSheetRegistry;
use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class ProductionSheetsController extends Controller
{


   protected $productionSheet;

   // public function __construct(ProductionSheetRegistry $registry)
   public function __construct(ProductionSheet $productionSheet)
   {
        // $this->productionSheet = $registry->get('new');
        $this->productionSheet = $productionSheet;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sheets = $this->productionSheet->orderBy('due_date', 'desc');

        $sheets = $sheets->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $sheets->setPath('productionsheets');

        return view('production_sheets.index', compact('sheets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('production_sheets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );

        $this->validate($request, ProductionSheet::$rules);

        $sheet = $this->productionSheet->create($request->all() + ['is_dirty' => 0]);

        return redirect('productionsheets')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $sheet->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        return view('production_sheets.show', compact('sheet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        $sheet->due_date = abi_date_form_short($sheet->due_date);
        
        return view('production_sheets.edit', compact('sheet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );

        $this->validate($request, ProductionSheet::$rules);

        $sheet->update($request->all());

        return redirect('productionsheets')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductionSheet $productionSheet)
    {
        //
    }

    /**
     *
     *
     *
     */


    public function calculate($id)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        $sheet->calculateProductionOrders( \App\Configuration::isTrue('MRP_WITH_STOCK') );

        return redirect('productionsheets/'.$id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function addOrders(Request $request, $id)
    {
        if ( count($request->input('corders', [])) == 0 ) 
            return redirect()->route('customerorders.index')
                ->with('warning', l('No se ha seleccionado ningún Pedido, y no se ha realizado ninguna acción.'));

//        if ( intval($id) <= 0 ) $id = $request->input('production_sheet_id', 0);

        $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );

        // Need to create Production Sheet?
        if ( $request->input('production_sheet_mode', '') == 'new' ) {

            // $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );
            $this->validate($request, ProductionSheet::$rules);

            $sheet = $this->productionSheet->create($request->all() + ['is_dirty' => 0]);
        } else {

            $i = intval($request->input('production_sheet_id', ''));

            if ( $i > 0 )
                $sheet = $this->productionSheet->findOrFail($i);
            else
                $sheet = $this->productionSheet->findOrFail($id);
        }

        if ( !$sheet ) 
            return redirect()->route('customerorders.index')
                ->with('error', l('No se ha seleccionado una Hoja de Producción válida, y no se ha realizado ninguna acción.'));


        // Do the Mambo!
        foreach ( $request->input('corders', []) as $oID ) {

            // Retrieve order
            $order = \App\CustomerOrder::findOrFail( $oID );

            $order->update(['production_sheet_id' => $sheet->id]);

        }

        return redirect()->route('customerorders.index')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function pickinglist($id)
    {
        $sheet = $this->productionSheet
                      ->with('customerorders')
                      ->with('customerorders.customerorderlines')
                      ->findOrFail($id);

        return view('production_sheets.pickinglist', compact('sheet'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function getProducts($id)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        return 'getProducts '.$id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function getSummary(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);
        // $sheet->customerorders()->load(['customer', 'customerorderlines']);

        $columns = $sheet->customerorderlinesGroupedByWorkCenter($work_center->id);

        // abi_r($sheet, true);
        // abi_r($columns, true);

        return view('production_sheets.summary_table', compact('work_center', 'sheet', 'columns'));
    }






    /**
     * AJAX Stuff.
     *
     * 
     */

    public function getCustomerOrdersSummary($id)
    {
        $sheet = \App\ProductionSheet::findOrFail($id);

        return view('production_sheets.ajax._panel_customer_order_summary', compact('sheet'));
    }
    
    public function getCustomerOrderOrderLines($id)
    {
        $order = \App\CustomerOrder::with('CustomerOrderLines')
                        ->with('CustomerOrderLines.product')
                        ->findOrFail($id);

        return view('production_sheets.ajax._panel_customer_order_lines', compact('order'));
    }


/* ********************************************************************************************* */    

    /**
     * PDF Stuff.
     *
     * See: ProductionSheetsPdfController
     * 
     */

/* ********************************************************************************************* */    


}
