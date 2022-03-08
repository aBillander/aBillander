<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;
use App\ProductionSheet;
use App\ProductionRequirement;
use App\Product;

class ProductionSheetProductionRequirementsController extends Controller
{
   protected $productionSheet;
   protected $requirement;
   protected $product;

   public function __construct(ProductionSheet $productionSheet, ProductionRequirement $requirement, Product $product)
   {
        $this->productionSheet = $productionSheet;
        $this->requirement     = $requirement;
        $this->product         = $product;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($sheet)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sheet)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($sheet, Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductionRequirement  $productionrequirement
     * @return \Illuminate\Http\Response
     */
    public function show(ProductionRequirement $productionrequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductionRequirement  $productionrequirement
     * @return \Illuminate\Http\Response
     */
    public function edit($sheet, ProductionRequirement $productionrequirement)
    {
        //
        abi_r($sheet);
        abi_r($productionrequirement);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductionRequirement  $productionrequirement
     * @return \Illuminate\Http\Response
     */
    public function update($sheet, Request $request, ProductionRequirement $productionrequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductionRequirement  $productionrequirement
     * @return \Illuminate\Http\Response
     */
    public function destroy($sheet, ProductionRequirement $productionrequirement)
    {
        $id = $productionrequirement->id;

        $productionrequirement->delete();

        return redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
