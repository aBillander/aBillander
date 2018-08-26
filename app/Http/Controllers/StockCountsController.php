<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\StockCount;
use App\StockMovement;

class StockCountsController extends Controller
{


   protected $stockcount;

   public function __construct(StockCount $stockcount)
   {
        $this->stockcount = $stockcount;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockcounts = $this->stockcount->with('warehouse')->orderBy('document_date', 'desc')->get();

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
        $date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $date_raw )->toDateString();

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
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function show(StockCount $stockcount)
    {
        return $this->edit($stockcount);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockCount  $stockCount
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
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCount $stockcount)
    {
        $date_raw = $request->input('document_date');
        $date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $date_raw )->toDateString();

        $request->merge( ['document_date' => $date] );

        // abi_r($request->all());die();

        $this->validate($request, StockCount::$rules);

        $stockcount->update($request->all());

        return redirect('stockcounts')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $stockcount->id], 'layouts') . $stockcount->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCount  $stockCount
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


    public function warehouseUpdate($id)
    {

        $stockcount = $this->stockcount
                    ->with('stockcountlines')
 //                   ->with('stockcountlines.product')
 //                   ->with('stockcountlines.product.tax')
                    ->findOrFail($id);



        $name = '['.$stockcount->id.'] '.$stockcount->name;
        $wsname = '['.$stockcount->warehouse->id.'] '.$stockcount->warehouse->name;

        // Start Logger
        $logger = \App\ActivityLogger::setup( 'Process Inventory Count', __METHOD__ );

        $logger->empty();
        $logger->start();

        $logger->log("INFO", 'Se actualizará el Stock de los Productos en el Almacén <span class="log-showoff-format">:name</span> .', ['name' => $wsname]);

        $i = 0;
        $i_ok = 0;

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


        // Let's get dirty!!!
        if ( $stockcount->stockcountlines()->count() )
        foreach ($stockcount->stockcountlines as $line)
        {
/*

        if ( $request->input('movement_type_id') == 10 ) {
            $product = \App\Product::find( $request->input('product_id') );
            if ( $product->quantity_onhand > 0.0 )
                return redirect('stockmovements/create')
                        ->with( 'error', l('Can not set Initial Stock &#58&#58 (:id) :name has already non zero stock', ['id' => $product->id, 'name' => $product->name]) );
        }

*/
            //
            $data = [

                    'movement_type_id' => $movement_type_id,
                    'date' => $stockcount->document_date,

 //                   'stockmovementable_id' => ,
 //                   'stockmovementable_type' => ,

                    'document_reference' => $stockcount->id,
 //                   'quantity_before_movement' => ,
                    'quantity' => $line->quantity,
 //                   'quantity_after_movement' => ,

                    'price' => $line->cost_price,
                    'currency_id' => \App\Context::getContext()->company->currency->id,
                    'conversion_rate' => \App\Context::getContext()->company->currency->conversion_rate,

                    'notes' => '',

                    'product_id' => $line->product_id,
                    'combination_id' => $line->combination_id,
                    'warehouse_id' => $stockcount->warehouse_id,
 //                   'warehouse_counterpart_id' => ,
                    
            ];

            $stockmovement = StockMovement::create( $data );
            $line->stockmovements()->save( $stockmovement );

            // Stock movement fulfillment (perform stock movements)
            $stockmovement->process();


            $i_ok++;
            $i++;
        }


        $logger->log('INFO', 'Se han actualizado {i} Productos.', ['i' => $i_ok]);

        $logger->log('INFO', 'Se han procesado {i} Líneas de Inventario.', ['i' => $i]);

        $logger->stop();


        $stockcount->processed = 1;
        $stockcount->save();

        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
