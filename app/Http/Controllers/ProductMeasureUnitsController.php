<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Product;
use App\ProductMeasureUnit;

class ProductMeasureUnitsController extends Controller {


   protected $product;
   protected $productmeasureunit;

   public function __construct(Product $product, ProductMeasureUnit $productmeasureunit)
   {
        $this->product     = $product;
        $this->productmeasureunit = $productmeasureunit;
   }

	/**
	 * Display a listing of the resource.
	 * GET /prices
	 *
	 * @return Response
	 */
	public function index($productId, Request $request)
    {
        // Check default unit existence
        // Pending

        $product  = $this->product->with('measureunit')->with('productmeasureunits')->findOrFail($productId);

        return view('product_measure_units.index', compact('product'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /prices/create
	 *
	 * @return Response
	 */
	public function create($productId)
	{
        // die($productId);

        $product  = $this->product->findOrFail($productId);

        $measure_unitList = \App\MeasureUnit::pluck('name', 'id')->toArray();

        return view('product_measure_units.create', compact('product', 'measure_unitList'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /prices
	 *
	 * @return Response
	 */
	public function store($productId, Request $request)
	{
		$product = $this->product->findOrFail($productId);

		$munits = $product->measureunits;

		if ( $munits->where('id', $request->input('measure_unit_id'))->first() )
			return redirect(route('products.measureunits.index',$productId))
				->with('error', l('This record cannot be created because it already exists &#58&#58 (:id) ', ['id' => ''], 'layouts'));

		$this->validate($request, ProductMeasureUnit::$rules);

		$data = [
			'product_id' => $product->id,
			'base_measure_unit_id' => $product->measure_unit_id,
		];

		$line = $this->productmeasureunit->create($request->all() + $data);

		$product->productmeasureunits()->save($line);

		return redirect(route('products.measureunits.index',$productId))
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $line->id], 'layouts'));

	}

	/**
	 * Display the specified resource.
	 * GET /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($productId, $id)
	{
        return $this->edit($productId, $id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /prices/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($productId, $id)
	{
        $list = $this->product->findOrFail($productId);
        $line = $this->productmeasureunit->with('product')->findOrFail($id);

        return view('product_measure_units.edit', compact('list', 'line'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($productId, $id, Request $request)
	{
		$line = $this->productmeasureunit->findOrFail($id);

		if ($line->price_list_id != $productId)
			return redirect()->back()
				->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts'));


		$this->validate($request, ProductMeasureUnit::$rules);

		$line->update(['price' => $request->input('price')]);

		return redirect(route('products.measureunits.index',$productId))
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($productId, $id)
    {
		$product = $this->product->findOrFail($productId);

		$punit = $this->productmeasureunit->findOrFail($id);

    	if ( $punit->measure_unit_id == $product->measure_unit_id )
    		return redirect(route('products.measureunits.index',$productId))
    	            ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts'));
        
        $punit->delete();

        return redirect(route('products.measureunits.index',$productId))
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
                                ->IsSaleable()
                                ->qualifyForPriceList( $id )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }

}