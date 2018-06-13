<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PriceList as PriceList;
use View;

class PriceListsController extends Controller {


   protected $pricelist;

   public function __construct(PriceList $pricelist)
   {
        $this->pricelist = $pricelist;
   }

	/**
	 * Display a listing of pricelists
	 *
	 * @return Response
	 */
	public function index()
	{
		$pricelists = $this->pricelist->with('currency')->orderBy('id', 'ASC')->get();

		return view('price_lists.index', compact('pricelists'));
	}

	/**
	 * Show the form for creating a new pricelist
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('price_lists.create');
	}

	/**
	 * Store a newly created pricelist in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, PriceList::$rules);

		$pricelist = $this->pricelist->create($request->all());

		if ( \App\Configuration::get('NEW_PRICE_LIST_POPULATE') ) {

			// Calculate prices for this Price List
			// ToDo: make chunck by chunck
			$products = \App\Product::get();
	
	        foreach ($products as $product) {
	
	            $pricelist->addLine( $product );
	
	        }

		}

		return redirect('pricelists')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $pricelist->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified pricelist.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->edit($id);
	}

	/**
	 * Show the form for editing the specified pricelist.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$pricelist = $this->pricelist->findOrFail($id);

		return view('price_lists.edit', compact('pricelist'));
	}

	/**
	 * Update the specified pricelist in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$pricelist = $this->pricelist->findOrFail($id);

		$this->validate($request, PriceList::$rules);

		$pricelist->update($request->all());

		return redirect('pricelists')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified pricelist from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $pricelist = $this->pricelist->findOrFail($id);

        // See: https://laracasts.com/discuss/channels/eloquent/laravel-delete-model-with-all-relations
        $pricelist->pricelistlines()->each(function($line) {
			        $line->delete();
			    });

        $pricelist->delete();

        return redirect('pricelists')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
