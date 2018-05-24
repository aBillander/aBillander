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
	public function index()
	{
		// 
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /prices/create
	 *
	 * @return Response
	 */
	public function create($pricelistId)
	{
		// 
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /prices
	 *
	 * @return Response
	 */
	public function store($pricelistId, Request $request)
	{
		//

	}

	/**
	 * Display the specified resource.
	 * GET /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        //
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /prices/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$price = $this->pricelistline
                      ->with('pricelist')
                      ->with('product')
                      ->with('product.tax')
                      ->find($id);

		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

		if ( ($price->pricelist->type == 'price') AND $price->pricelist->price_is_tax_inc )
			$price->price = $price->price / (1.0+($price->product->tax->percent/100.0));

		return view('price_list_lines.edit', compact('price', 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$price = $this->pricelistline
                      ->with('pricelist')
                      ->findOrFail($id);

		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '404' ;

        // $this->validate($request, Price::related_rules());
		$price->price = ( ($price->pricelist->type == 'price') AND $price->pricelist->price_is_tax_inc ) 
						? $request->input('price_tax_inc')
						: $request->input('price') ;

		$price->save();

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $price->id], 'layouts') );

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /prices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
    {
        // 
	}

}