<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \aBillander\WooConnect\WooOrder;

class WooImportedOrdersController extends Controller {


   protected $worder;

   public function __construct(WooOrder $worder)
   {
        $this->worder = $worder;
   }

	/**
	 * Display a listing of the resource.
	 * GET /currencies
	 *
	 * @return Response
	 */
	public function index()
	{
		$currencies = $this->worder->orderBy('id', 'asc')->get();

        return view('currencies.index', compact('currencies'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /currencies/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('currencies.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /currencies
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, WooOrder::$rules);

		$worder = $this->worder->create($request->all());

		\App\CurrencyConversionRate::create([
				'date' => \Carbon\Carbon::now(), 
				'worder_id' => $worder->id, 
				'conversion_rate' => $worder->conversion_rate, 
				'user_id' => \Auth::id(),
			]);

		return redirect('currencies')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $worder->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->edit($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /currencies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$worder = $this->worder->findOrFail($id);
		
		return view('currencies.edit', compact('worder'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$worder = $this->worder->findOrFail($id);

		$this->validate($request, Currency::$rules);

		$worder->update($request->all());

		if ( $worder->id != \App\Context::getContext()->worder->id )
			\App\CurrencyConversionRate::create([
					'date' => \Carbon\Carbon::now(), 
					'worder_id' => $worder->id, 
					'conversion_rate' => $worder->conversion_rate, 
					'user_id' => \Auth::id(),
				]);

		return redirect('currencies')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->worder->findOrFail($id)->delete();

        // Delete worder conversion rate history

        return redirect('currencies')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}


/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxCurrencyRateSearch(Request $request)
    {
        // Request data
    }

}