<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Product;
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
        $supplier = $this->supplier->with('currency')->findOrFail($supplierId);

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

        $q_rule = [

        'from_quantity' => [
                                new \App\Rules\SupplierPriceListLineQuantity(
                                        $request->input('supplier_id'), 
                                        $request->input('product_id'), 
                                        $request->input('currency_id', null)
                                ),

                                new \App\Rules\SupplierPriceListLineDuplicated(
                                        $request->input('supplier_id'), 
                                        $request->input('product_id'), 
                                        $request->input('currency_id', null)
                                ),
                            ]
        ];

        $this->validate($request, SupplierPriceListLine::$rules + $q_rule);

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


    public function editReference($supplierId, $productId)
    {
        // $supplier = $this->supplier->findOrFail($supplierId);
        // $product  = Product::findOrFail($id);

        $line = SupplierPriceListLine::
                              where('supplier_id', $supplierId)
                            ->where('product_id', $productId)
                            ->with('supplier')
                            ->orderBy('from_quantity', 'asc')
                            ->first();

        if ( !$line )
        return redirect()->back()
                ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => $reference], 'layouts'));


        $supplier = $line->supplier;

        $currencyList = Currency::pluck('name', 'id')->toArray();
        $currencyDefault = $line->currency->id;
        
        return view('supplier_price_list_lines.edit_reference', compact('supplier', 'line', 'currencyList', 'currencyDefault'));
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

    public function updateReference($supplierId, $productId, Request $request)
    {
        // $this->validate($request, SupplierPriceListLine::$rules);

        $reference = $request->input('supplier_reference', '');

        $line = SupplierPriceListLine::
                              where('supplier_id', $supplierId)
                            ->where('product_id', $productId)
                            ->orderBy('from_quantity', 'asc')
                            ->first();

        if ( !$line )
        return redirect( route('suppliers.supplierpricelistlines.index', $supplierId) )
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $reference], 'layouts'));

        $line->update(['supplier_reference' => $reference ]);

        return redirect( route('suppliers.supplierpricelistlines.index', $supplierId) )
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $reference], 'layouts'));
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

        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->IsPurchaseable()
//                                ->qualifyForPriceList( $id )
//                                ->with('measureunit')
//                                ->toSql();
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $products );
    }


    public function getSupplierProductReference($id, $pid, Request $request)
    {
        $supplier = $this->supplier->findOrFail($id);
        $product  = Product::findOrFail($pid);

        $reference = $product->getReferenceBySupplier( $supplier );

        return response( ['reference' => $reference] );
    }

}