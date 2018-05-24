<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\StockCount as StockCount;

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

        $sequenceList = \App\Sequence::listFor('StockCount');

        if ( !$sequenceList )
            return redirect('stockcounts')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));

        return view('stock_counts.create', compact('date', 'sequenceList'));
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


        $seq = \App\Sequence::find( $request->input('sequence_id') );
        $doc_id = $seq->getNextDocumentId();
        $extradata = [  'document_prefix'      => $seq->prefix,
                        'document_id'          => $doc_id,
                        'document_reference'   => $seq->getDocumentReference($doc_id),
                        'document_date' => $date,
                     ];
        $request->merge( $extradata );

        $this->validate($request, StockCount::$rules);

        $stockcount = $this->stockcount->create($request->all());

        return redirect('stockcounts')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockcount->id], 'layouts') . $stockcount->document_reference);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function show(StockCount $stockCount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function edit(StockCount $stockCount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCount $stockCount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCount $stockCount)
    {
        //
    }
}
