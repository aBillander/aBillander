<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ProductBOMLine;
use View;

class ProductBOMLinesController extends Controller
{

   protected $bomline;

   public function __construct(ProductBOMLine $bomline)
   {
        $this->bom = $bomline;
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
     * @param  \App\Models\ProductBOMLine  $productBOMLine
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBOMLine $productBOMLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductBOMLine  $productBOMLine
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBOMLine $productBOMLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductBOMLine  $productBOMLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBOMLine $productBOMLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductBOMLine  $productBOMLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBOMLine $productBOMLine)
    {
        //
    }
}
