<?php

namespace App\Http\Controllers;

use App\StockCountLine;
use Illuminate\Http\Request;

class StockCountLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('soon');
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
