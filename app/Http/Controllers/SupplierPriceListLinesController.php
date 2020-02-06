<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Supplier;
use App\SupplierPriceListLine;
use App\Currency;
use View;

use App\Configuration;

class SupplierPriceListLinesController extends Controller
{


   protected $supplier;
   protected $line;

   public function __construct(Supplier $supplier, SupplierPriceListLine $line)
   {
        $this->supplier = $supplier;
        $this->line = $line;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($supplierId, Request $request)
    {
        $supplier = $this->supplier->find($supplierId);
        
        $lines_total = $this->line->where('supplier_id', $supplier->id)->count();

        $lines = $this->line
                        ->where('supplier_id', $supplier->id)
                        ->with('product')
                        ->with('currency')
                        ->filter( $request->all() )

                            ->join('products', 'supplier_price_list_lines.product_id', '=', 'products.id')
                            ->select('supplier_price_list_lines.*', 'products.reference')
                            ->orderBy('products.reference', 'asc')

                        ->orderBy('from_quantity', 'asc');

        $lines = $lines->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $lines->setPath('supplierpricelistlines');

        return view('supplier_price_list_lines.index', compact('supplier', 'lines', 'lines_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($supplierId)
    {
        $supplier = $this->supplier->findOrFail($supplierId);

        $currencyList = Currency::pluck('name', 'id')->toArray();
        $currencyDefault = Configuration::getInt('DEF_CURRENCY');

        return view('supplier_price_list_lines.create', compact('supplier', 'currencyList', 'currencyDefault'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($supplierId, Request $request)
    {
        $supplier = $this->supplier->findOrFail($supplierId);

        $this->validate($request, SupplierPriceListLine::$rules);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );


        $line = $this->line->create($request->all());

        return redirect( route('suppliers.supplierpricelistlines.index', $supplier->id) )
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $line->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($supplierId, $id)
    {
        return $this->edit($supplierId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($supplierId, $id)
    {
        $supplier = $this->supplier->findOrFail($supplierId);
        $line = $this->line->findOrFail($id);

        $currencyList = Currency::pluck('name', 'id')->toArray();
        $currencyDefault = $line->currency->id;
        
        return view('supplier_price_list_lines.edit', compact('supplier', 'line', 'currencyList', 'currencyDefault'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($supplierId, $id, Request $request)
    {
        $line = SupplierPriceListLine::findOrFail($id);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );
        

        $this->validate($request, SupplierPriceListLine::$rules);

        $line->update($request->all());

        return redirect( route('suppliers.supplierpricelistlines.index', $supplierId) )
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($supplierId, $id)
    {
        // die($supplierId);

        $this->line->findOrFail($id)->delete();

        return redirect( route('suppliers.supplierpricelistlines.index', $supplierId) )
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
                                ->IsPurchaseable()
//                                ->qualifyForPriceList( $id )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }


}