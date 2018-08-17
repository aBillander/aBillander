<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\StockCount;
use App\StockCountLine;

class StockCountLinesController extends Controller
{


   protected $stockcount;
   protected $stockcountline;

   public function __construct(StockCount $stockcount, StockCountLine $stockcountline)
   {
        $this->stockcount     = $stockcount;
        $this->stockcountline = $stockcountline;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($stockcountId, Request $request)
    {
        $list  = $this->stockcount->findOrFail($stockcountId);
        $lines = $this->stockcountline
                        ->select('stock_count_lines.*')
                        ->with('product')
                        ->where('stock_count_id', $stockcountId)
                        ->join('products', 'products.id', '=', 'stock_count_lines.product_id')       // Get field to order by
                        ->filter( $request->all() )
                        ->orderBy('products.name', 'asc');

        $lines = $lines->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $lines->setPath('stockcountlines');

        return view('stock_count_lines.index', compact('list', 'lines'));
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
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function show(StockCountLine $stockCountLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function edit(StockCountLine $stockCountLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCountLine $stockCountLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCountLine $stockCountLine)
    {
        //
    }
}
