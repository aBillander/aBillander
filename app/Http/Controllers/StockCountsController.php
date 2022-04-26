<?php

namespace App\Http\Controllers;

use App\Helpers\eggTimer;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Product;
use App\Models\StockCount;
use App\Models\StockCountLine;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockCountsController extends Controller
{

   protected $stockcount;
   protected $stockcountline;

   public function __construct(StockCount $stockcount, StockCountLine $stockcountline)
   {
        $this->stockcount = $stockcount;
        $this->stockcountline = $stockcountline;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockcounts = $this->stockcount->with('warehouse')->orderBy('document_date', 'desc')->orderBy('id', 'desc')->get();

        return view('stock_counts.index', compact('stockcounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = abi_date_short( \Carbon\Carbon::now() );
/*
        $sequenceList = \App\Sequence::listFor( StockCount::class );

        if ( !$sequenceList )
            return redirect('stockcounts')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
*/
        return view('stock_counts.create', compact('date'));       // , 'sequenceList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date_raw = $request->input('document_date');
        $date = \Carbon\Carbon::createFromFormat( Context::getContext()->language->date_format_lite, $date_raw )->toDateString();

/*
        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
        $doc_id = $seq->getNextDocumentId();
        $extradata = [  'document_prefix'      => $seq->prefix,
                        'document_id'          => $doc_id,
                        'document_reference'   => $seq->getDocumentReference($doc_id),
                        'document_date' => $date,
                     ];
*/                     
        $request->merge( ['document_date' => $date] );

        // abi_r($request->all());die();

        $this->validate($request, StockCount::$rules);

        $stockcount = $this->stockcount->create($request->all());

        return redirect('stockcounts')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockcount->id], 'layouts') . $stockcount->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function show(StockCount $stockcount)
    {
        return $this->edit($stockcount);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function edit(StockCount $stockcount)
    {
        $date = abi_date_short( $stockcount->document_date );

        return view('stock_counts.edit', compact('stockcount', 'date'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCount $stockcount)
    {
        $date_raw = $request->input('document_date');
        $date = \Carbon\Carbon::createFromFormat( Context::getContext()->language->date_format_lite, $date_raw )->toDateString();

        $request->merge( ['document_date' => $date] );

        // abi_r($request->all());die();

        $this->validate($request, StockCount::$rules);

        $stockcount->update($request->all());

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $stockcount->id], 'layouts') . $stockcount->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCount $stockcount)
    {
        // Cannot delete
        if ( $stockcount->processed )
            return redirect('stockcounts');


        $id = $stockcount->id;

        $stockcount->stockcountlines()->each(function($line) {
                    $line->delete();
                });

        $stockcount->delete();

        return redirect('stockcounts')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }



/* ********************************************************************************************* */    



    public function warehouseUpdate(Request $request, $id)
    {
/*
        if ($request->ajax()) {
            
            sleep(5);

            return response()->json(['url_to' => 'OK']);
        }

        return 'ok';
*/
        // Prepare
        $roundCycle = $request->input('roundCycle', 0);
        $nextItem = $request->input('nextItem', 0);

        // Go for it
        $roundCycle++;

        $stockcount = $this->stockcount
                    ->with('warehouse')
                    ->with('stockcountlines')
 //                   ->with('stockcountlines.product')
 //                   ->with('stockcountlines.product.tax')
                    ->findOrFail($id);


        if ($stockcount->processed)
        {
            if ($request->ajax()) {

                return response()->json([
    //                'action' => $action, 
    //                'current_task' => $current_task, 
    //                'current_task_status' => $current_task_status, 
                    'item_count' => 0, 
                    'item_count_ok' => 0, 
                    'item_total' => 0, 
                    'roundCycle' => 0,
                    'progress' => 100,
                    'iamdone'  => 1, 
                    'nextItem' => 0,
                    'messages' => [],

                    'url_to' => route('stockcounts.index'),
                    'errors'   => 0,
                    'warnings' => 0,
                ]);
            }

            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts'));
        }

        $name = '['.$stockcount->id.'] '.$stockcount->name;
        $wsname = '['.$stockcount->warehouse->id.'] '.$stockcount->warehouse->name;

        // Start Logger
        $logger = ActivityLogger::setup( 'Process Inventory Count', __METHOD__ )
                    ->backTo( route('stockcounts.stockcountlines.index', [$stockcount->id]) );

        if ($roundCycle==1)
            $logger->empty();
        $logger->start();


        $abiTimer = new eggTimer( Configuration::getInt('ABI_IMPERSONATE_TIMEOUT'), Configuration::getInt('ABI_TIMEOUT_OFFSET') );
        $logger->log("TIMER", 'Control de tiempo Iniciado. Ronda: '.$roundCycle.' :: [Tiempo concedido: '.$abiTimer->getAllowedTime().' segundos].');

        if ($roundCycle==1)
        {
            // "Header" info
            $logger->log("INFO", 'Se actualizará el Stock de los Productos en el Almacén <span class="log-showoff-format">:name</span> utilizando el Inventario (:id) <span class="log-showoff-format">:cname</span> [:date].', ['name' => $wsname, 'id' => $stockcount->id, 'cname' => $stockcount->name, 'date' => abi_date_short($stockcount->document_date)]);
        
            if ($stockcount->initial_inventory) 
            {
                //
                $movement_type_id = StockMovement::INITIAL_STOCK;
    
                $logger->log("INFO", 'Se creará el Stock Inicial de los Productos.');
    
            } else {
                //
                $movement_type_id = StockMovement::ADJUSTMENT;
    
                $logger->log("INFO", 'Se hará un Ajuste del Stock de los Productos.');
    
            }

        } else {
            // "Header" info
            if ($stockcount->initial_inventory) 
            {
                //
                $movement_type_id = StockMovement::INITIAL_STOCK;
    
            } else {
                //
                $movement_type_id = StockMovement::ADJUSTMENT;
    
            }
        }


        // Let's get dirty!!!
        $lines = $this->stockcountline
                        ->with('product')
                        ->where('stock_count_id', $id)
                        ->where('id', '>=', $nextItem)
                        ->orderBy('id', 'asc')
                        ->get();

        $count = $lines->count();
        if ($roundCycle==1)
        {
                $logger->log('INFO', 'Se han encontrado <span class="log-showoff-format">{i} Líneas</span> de Inventario.', ['i' => $count]);
                $item_total = $count;
                $item_count = 0;
                $item_count_ok = 0;
        } else {
                $logger->log('INFO', 'Se han encontrado <span class="log-showoff-format">{i} Líneas</span> de Inventario pedientes.', ['i' => $count]);
                $item_total = $request->input('item_total', 0);
                $item_count = $request->input('item_count', 0);
                $item_count = $request->input('item_count_ok', 0);
        }

        $i = 0;
        $i_ok = 0;
        $timeoutReached = false;
        $cyclesReached = false;
        $messages = [];

        // abi_r($lines);die();

//        if ( $count > 0 )
//        if ( ! ($lines->count() > 0 ) ) $logger->logError('No hay líneas, amiguete!');
//        $logger->logWarning('No hay líneas, amiguete!');

        foreach ($lines as $line)
        {
            // Check timeout
            if ($timeoutReached=$abiTimer->checkTimeout())  {
                // Do Stuff...
                $nextItem = $line->id;
                $logger->log('INFO', ' - <span style="color: red; font-weight: bold">'.$i.'</span> líneas procesados de <span style="color: green; font-weight: bold">'.$count . '</span>');
                $logger->log('INFO', 'Siguiente línea: ' . '('.$line->id.') [' . $line->referencee. '] ' . $line->name);
                // $messages['informations'][] = ' - <span style="color: red; font-weight: bold">'.$art_count.'</span> Artículos procesados de <span style="color: green; font-weight: bold">'.$art_total . '</span>';
                break;
            }

            $product = $line->product;

            // Alternative:
            if ( !$product )
            {
                $product = Product::where('reference', $line->reference)->first();

                if ( !$product )
                {
                    // Product not found
                    $logger->log("ERROR", 'NO se ha creado un Movimiento para la línea núm. <span class="log-ERROR-format">:id</span> del Recuento. El Producto NO existe', ['id' => $line->id]);
                    $i++;
                    continue;
                }
            }

            // Update Cost Price
            // if ( $line->cost_price > 0.0 )
            // {
                $product->cost_price = $line->cost_price;
                $product->cost_average = $line->cost_average;
                $product->last_purchase_price = $line->last_purchase_price;

                $product->save();
            // }

            // Let's move on:
            $data = [

                    'movement_type_id' => $movement_type_id,
                    'date' => $stockcount->document_date,

 //                   'stockmovementable_id' => ,
 //                   'stockmovementable_type' => ,

                    'document_reference' => 'Stock Count #'.$stockcount->id,
 //                   'quantity_before_movement' => ,
                    'quantity' => $line->quantity,
 //                   'quantity_after_movement' => ,
                    'measure_unit_id' => $product->measure_unit_id,

                    'price_currency' => $line->getPriceForStockValuation(),
                    'currency_id' => Context::getContext()->company->currency->id,
                    'conversion_rate' => Context::getContext()->company->currency->conversion_rate,

                    'notes' => '',

                    'product_id' => $product->id,
                    'combination_id' => '', // $line->combination_id,
                    'reference' => $product->reference,
                    'name' => $product->name,

                    'warehouse_id' => $stockcount->warehouse_id,
 //                   'warehouse_counterpart_id' => ,
                    
            ];



            $stockmovement = StockMovement::createAndProcess( $data );  // null; sleep(2);    // 

            // Stock movement fulfillment (perform stock movements)
            if( $stockmovement )
            {
                $line->stockmovements()->save( $stockmovement );

                $i_ok++;
            } else {    
                // Error
                // When?
                $logger->log("ERROR", 'NO se ha creado un Movimiento para la línea núm. <span class="log-ERROR-format">:id</span> del Recuento porque el Stock no ha variado.', ['id' => $line->id]);
            }

            $i++;
        }

        $logger->log("TIMER", 'Control de tiempo Detenido. Ronda: '.$roundCycle);

        $item_count += $i;
        $item_count_ok += $i_ok;
        
        if ($timeoutReached)
        {
            $messages['informations'][] = "Ha terminado la Ronda: <b>$roundCycle</b>";
            $messages['informations'][] = ' - <span style="color: red; font-weight: bold">'.$i.'</span> Productos actualizados de <span style="color: green; font-weight: bold">'.$item_count . '</span>. Quedan pendientes: <span style="color: blue; font-weight: bold">'.($item_total - $item_count) . '</span>.';

            $messages['informations'][] = 'Se han creado '.$i_ok.' Movimientos de Inventario.';

            $logger->log('INFO', 'Ha terminado la Ronda: <b>:roundCycle</b>.', ['roundCycle' => $roundCycle]);
            $logger->log('INFO', ' - <span style="color: red; font-weight: bold">'.$item_count.'</span> Productos actualizados de <span style="color: green; font-weight: bold">'.$item_total . '</span>. Quedan pendientes: <span style="color: blue; font-weight: bold">'.($item_total - $item_count) . '</span>.', ['i' => $i]);

            $logger->log('INFO', 'Se han creado {i} Movimientos de Inventario.', ['i' => $i_ok]);
        } else {
            $stockcount->processed = 1;
            $stockcount->save();

//            $logger->log('INFO', 'Se han actualizado {i} Productos.', ['i' => $i_ok]);
            $logger->log('INFO', 'Se han procesado {i} Líneas de Inventario en total.', ['i' => $item_total]);

            $logger->log('INFO', 'Se han creado {i} Movimientos de Inventario en total.', ['i' => $item_count_ok]);
        }

        if ($roundCycle>1)
        {
            $messages['informations'][] = "Ha terminado la Ronda: <b>$roundCycle</b>";
            $messages['informations'][] = ' - <span style="color: red; font-weight: bold">'.$i.'</span> Productos actualizados de <span style="color: green; font-weight: bold">'.$item_count . '</span>. Quedan pendientes: <span style="color: blue; font-weight: bold">'.($item_total - $item_count) . '</span>.';

            $logger->log('INFO', ' - Ronda: '.$roundCycle.' TERMINADA');
        }

        if ( $roundCycle >= Configuration::getInt('ABI_MAX_ROUNDCYCLES') ) {
            // Abort
            $cyclesReached = true;
            $messages['warnings'][] = 'Se ha alcanzado el número máximo de ciclos permitido: '.Configuration::getInt('ABI_MAX_ROUNDCYCLES');
            $messages['errors'][] = 'Se ha alcanzado el número máximo de ciclos permitido: '.Configuration::getInt('ABI_MAX_ROUNDCYCLES');

            $severity = ($item_total - $item_count) > 0 ? 'ERROR' : 'WARNING' ;
            $logger->log($severity, 'Se ha alcanzado el número máximo de ciclos permitido.');
        }

        $logger->stop();


        if ($request->ajax()) {

            $iamdone = $cyclesReached ? -1 : ($timeoutReached ? 0 : 1);
            $progress = $item_total>0 ? ceil(($item_count/$item_total)*100.0) : 100;

            return response()->json([
//                'action' => $action, 
//                'current_task' => $current_task, 
//                'current_task_status' => $current_task_status, 
                'item_count' => $item_count, 
                'item_count_ok' => $item_count_ok, 
                'item_total' => $item_total, 
                'roundCycle' => $roundCycle,
                'progress' => $progress,
                'iamdone'  => $iamdone, 
                'nextItem' => $nextItem,
                'messages' => $messages,

                'url_to' => route('activityloggers.show', [$logger->id]),
                'errors'   => $logger->hasErrors(),
                'warnings' => $logger->hasWarnings(),
            ]);
        }

        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
