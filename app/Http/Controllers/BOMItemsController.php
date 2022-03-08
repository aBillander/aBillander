<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\BOMItem;
use View;

class BOMItemsController extends Controller
{

   protected $bomitem;

   public function __construct(BOMItem $bomitem)
   {
        $this->bom = $bomitem;
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
     * @param  \App\BOMItem  $bOMItem
     * @return \Illuminate\Http\Response
     */
    public function show(BOMItem $bOMItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BOMItem  $bOMItem
     * @return \Illuminate\Http\Response
     */
    public function edit(BOMItem $bOMItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BOMItem  $bOMItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BOMItem $bOMItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BOMItem  $bOMItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(BOMItem $bOMItem)
    {
        //
    }
}
