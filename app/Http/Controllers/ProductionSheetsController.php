<?php

namespace App\Http\Controllers;

use App\ProductionSheet;
use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class ProductionSheetsController extends Controller
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

        // Delete Production Orders from Web
        $porders = $sheet->productionorders()->isFromWeb()->get();
        foreach ($porders as $order) {
            $order->delete();
        }

        $errors = [];

        // Do the Mambo!
        foreach ($sheet->customerorderlinesGrouped() as $pid => $line) {
            // Create Production Order
            $order = \App\ProductionOrder::createWithLines([
                'created_via' => 'webshop',
//                'status' => 'released',
                'product_id' => $pid,
//                'product_reference' => $line['reference'],
//                'product_name' => $line['name'],
                'planned_quantity' => $line['quantity'],
//                'product_bom_id' => 1,
                'due_date' => $sheet->due_date,
                'notes' => '',
//                
//                'work_center_id' => 2,
//                'warehouse_id' => 0,
                'production_sheet_id' => $sheet->id,
            ]);

            if (!$order) $errors[] = '<li>['.$line['reference'].'] '.$line['name'].'</li>';
        }

        if (count($errors)) 
            return redirect('productionsheets/'.$id)
                ->with('error', l('No se ha podido crear una Orden de Fabricación para los siguientes Productos, porque no se ha encontrado una Lista de Materiales:') . '<ul>' . implode('', $errors) . '</ul>');
        
        else 
            return redirect('productionsheets/'.$id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function addOrders(Request $request, $id)
    {
        if ( count($request->input('worders', [])) == 0 ) 
            return redirect()->route('worders.index')
                ->with('warning', l('No se ha seleccionado ningún Pedido, y no se ha realizado ninguna acción.'));

//        if ( intval($id) <= 0 ) $id = $request->input('production_sheet_id', 0);

        // Need to create Production Sheet?
        if ( $request->input('production_sheet_mode', '') == 'new' ) {

            $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );

            $sheet = $this->productionSheet->create($request->all() + ['is_dirty' => 0]);
        } else {

            $i = intval($request->input('production_sheet_id', ''));

            if ( $i > 0 )
                $sheet = $this->productionSheet->findOrFail($i);
            else
                $sheet = $this->productionSheet->findOrFail($id);
        }


        $errors = [];

        // Do the Mambo!
        foreach ( $request->input('worders', []) as $oID ) {
            // Retrieve order
            $params = [
//                'dp'   => $wc_currency->decimalPlaces,
            ];

            $order = WooCommerce::get('orders/'.$oID, $params);

            $state = \App\State::findByIsoCode( (strpos($order['shipping']['state'], '-') ? '' : $order['shipping']['country'].'-').$order['shipping']['state'] );
            $state_name = $state ? $state->name : $order['shipping']['state'];

            $abi_order = \App\CustomerOrder::create([
                'created_via' => 'webshop',
                'reference' => $order['id'], 
                'date_created' => \Carbon\Carbon::parse($order['date_created']), 
                'date_paid' => \Carbon\Carbon::parse($order['date_paid']), 
                'total' => $order['total'], 
                'customer' => serialize( $order["shipping"] + ["phone" => $order["billing"]["phone"], 'state_name' => $state_name]), 
                'customer_note' => $order['customer_note'], 
//                'production_at' => , 
                'production_sheet_id' => $sheet->id
            ]);

//            $abi_order->save();

            foreach ( $order['line_items'] as $item ) {

                $abi_product = \App\Product::where('reference', $item['sku'])->first();

                if ( !$abi_product ) {
                    // Create
                    $abi_product = \App\Product::create([
                        'product_type' => 'simple',
                        'procurement_type' => 'manufacture', 
                        'name' => $item['name'],
                        'reference' => $item['sku'],
                        'quantity_decimal_places' => \App\Configuration::get('DEF_QUANTITY_DECIMALS'),
                        'manufacturing_batch_size' => 1,
                        'measure_unit_id' => \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS'),

                    ]);

                    $errors[] = '<li>['.$item['sku'].'] '.$item['name'].'</li>';
                }

                $abi_order_line = \App\CustomerOrderLine::create([
                    'product_id' => $abi_product->id, 
                    'woo_product_id' => $item['product_id'],
                    'woo_variation_id' => $item['variation_id'],
                    'reference' => $item['sku'],
                    'name' => $item['name'], 
                    'quantity' => $item['quantity'], 
//                    'customer_order_id' => $abi_order->id,
                ]);

                $abi_order->customerorderlines()->save($abi_order_line);

            }
        }

        $extra='';
        $msg='success';

        if (count($errors)) {
            $extra='Se han creado los siguientes Productos, porque no se han encontrado:' . '<ul>' . implode('', $errors) . '</ul>';
            $msg='warning';
        }

        return redirect()->route('worders.index')
                ->with($msg, l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet->id], 'layouts').$extra);
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
}
