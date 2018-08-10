<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PriceList as PriceList;
use App\PriceListLine as PriceListLine;

class PriceListLinesController extends Controller {


   protected $pricelist;
   protected $pricelistline;

   public function __construct(PriceList $pricelist, PriceListLine $pricelistline)
   {
        $this->pricelist     = $pricelist;
        $this->pricelistline = $pricelistline;
   }

	/**
	 * Display a listing of the resource.
	 * GET /prices
	 *
	 * @return Response
	 */
	public function index($pricelistId, Request $request)
    {
        $list  = $this->pricelist->findOrFail($pricelistId);
        $lines = $this->pricelistline
                    	->select('price_list_lines.*')
        				->with('product')
        				->where('price_list_id', $pricelistId)
        				->join('products', 'products.id', '=', 'price_list_lines.product_id')		// Get field to order by
        				->filter( $request->all() )
                        ->orderBy('products.name', 'asc');

        $lines = $lines->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $lines->setPath('pricelistlines');

        return view('price_list_lines.index', compact('list', 'lines'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /prices/create
	 *
	 * @return Response
	 */
	public function create($pricelistId)
	{
        $list  = $this->pricelist->findOrFail($pricelistId);

        return view('price_list_lines.create', compact('list'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /prices
	 *
	 * @return Response
	 */
	public function store($pricelistId, Request $request)
	{
		$list = $this->pricelist->findOrFail($pricelistId);

		$this->validate($request, PriceListLine::$rules);

		$line = $this->pricelistline->create($request->all());

		$list->pricelistlines()->save($line);

		return redirect(route('pricelists.pricelistlines.index',$pricelistId))
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $line->id], 'layouts'));

	}

	/**
	 * Display the specified resource.
	 * GET /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($pricelistId, $id)
	{
        return $this->edit($pricelistId, $id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /prices/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($pricelistId, $id)
	{
        $list = $this->pricelist->findOrFail($pricelistId);
        $line = $this->pricelistline->with('product')->findOrFail($id);

        return view('price_list_lines.edit', compact('list', 'line'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($pricelistId, $id, Request $request)
	{
		$line = $this->pricelistline->findOrFail($id);

		if ($line->price_list_id != $pricelistId)
			return redirect()->back()
				->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts'));


		$this->validate($request, PriceListLine::$rules);

		$line->update(['price' => $request->input('price')]);

		return redirect(route('pricelists.pricelistlines.index',$pricelistId))
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($pricelistId, $id)
    {
        $this->pricelistline->findOrFail($id)->delete();

        return redirect(route('pricelists.pricelistlines.index',$pricelistId))
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
                                ->isManufactured()
                                ->qualifyForPriceList( $id )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }

}