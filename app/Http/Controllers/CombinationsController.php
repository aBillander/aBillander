<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Combination;
use App\Models\Product;

class CombinationsController extends Controller {


   protected $combination;
   protected $product;

   public function __construct(Combination $combination, Product $product)
   {
        $this->combination = $combination;
        $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $combination = $this->combination
                        ->with('options')
                        ->with('options.optiongroup')
                        ->findOrFail($id);

        $product = $this->product
                        ->with('tax')
                        ->findOrFail($combination->product_id);

        return view('combinations.edit', compact('combination', 'product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$combination = $this->combination->findOrFail($id);

		$this->validate($request, Combination::$rules);

		$combination->update($request->all());

		return redirect('products/'.$combination->product_id.'/edit#combinations')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $combination->name());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
