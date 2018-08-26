<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\StockCount;
use App\StockCountLine;

// use App\Traits\BillableTrait;

class StockCountLinesController extends Controller
{

   // use BillableTrait;

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
    public function create($stockcountId)
    {
        $list  = $this->stockcount->findOrFail($stockcountId);

        return view('stock_count_lines.create', compact('list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($stockcountId, Request $request)
    {
        // Dates (cuen)
        // $this->mergeFormDates( ['date'], $request );
        
        $list = $this->stockcount->findOrFail($stockcountId);

        $line = $list->stockcountlines()
                ->where('product_id',     $request->input('product_id'))
                ->where('combination_id', $request->input('combination_id', null))
                ->first();

        if ($line)
            return redirect()->back()
                ->with('error', l('This record cannot be created because it already exists &#58&#58 (:id) ', ['id' => $line->id], 'layouts').' ['.$line->product->id.'] '.$line->product->name);


        if ( !$request->input('cost_price') ) $request->merge(['cost_price'=>0.0]);


        $this->validate($request, StockCountLine::$rules);

        $line = $this->stockcountline->create($request->all());

        $list->stockcountlines()->save($line);

        return redirect(route('stockcounts.stockcountlines.index',$stockcountId))
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $line->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function show($stockcountId, $id)
    {
        $this->edit($stockcountId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function edit($stockcountId, $id)
    {
        $list = $this->stockcount->findOrFail($stockcountId);
        $line = $this->stockcountline->with('product')->findOrFail($id);

        return view('stock_count_lines.edit', compact('list', 'line'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function update($stockcountId, $id, Request $request)
    {
        $line = $this->stockcountline->findOrFail($id);

        if ($line->stock_count_id != $stockcountId)
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts'));


        $this->validate($request, stockcountLine::$rules);

        $line->update($request->all());

        return redirect(route('stockcounts.stockcountlines.index',$stockcountId))
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($stockcountId, $id)
    {
        $this->stockcountline->findOrFail($id)->delete();

        return redirect(route('stockcounts.stockcountlines.index',$stockcountId))
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function searchProduct($id, Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
//                                ->isManufactured()
//                                ->qualifyForPriceList( $id )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }
}
