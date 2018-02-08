<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Currency as Currency;
use View;

class CurrenciesController extends Controller {


   protected $currency;

   public function __construct(Currency $currency)
   {
        $this->currency = $currency;
   }

	/**
	 * Display a listing of the resource.
	 * GET /currencies
	 *
	 * @return Response
	 */
	public function index()
	{
		$currencies = $this->currency->orderBy('id', 'asc')->get();

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
		$this->validate($request, Currency::$rules);

		$currency = $this->currency->create($request->all());

		\App\CurrencyConversionRate::create([
				'date' => \Carbon\Carbon::now(), 
				'currency_id' => $currency->id, 
				'conversion_rate' => $currency->conversion_rate, 
				'user_id' => \Auth::id(),
			]);

		return redirect('currencies')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $currency->id], 'layouts') . $request->input('name'));
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
		$currency = $this->currency->findOrFail($id);
		
		return view('currencies.edit', compact('currency'));
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
		$currency = $this->currency->findOrFail($id);

		$this->validate($request, Currency::$rules);

		$currency->update($request->all());

		if ( $currency->id != \App\Context::getContext()->currency->id )
			\App\CurrencyConversionRate::create([
					'date' => \Carbon\Carbon::now(), 
					'currency_id' => $currency->id, 
					'conversion_rate' => $currency->conversion_rate, 
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
        $this->currency->findOrFail($id)->delete();

        // Delete currency conversion rate history

        return redirect('currencies')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

	/**
	 * Display the specified resource.
	 * GET /currencies/{id}/exchange
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function exchange($id)
	{
		$currency = $this->currency->findOrFail($id);

		$ccrs = \App\CurrencyConversionRate::where('currency_id', '=', $currency->id)->with('user')->orderBy('date', 'desc')->get();

        return view('currencies.exchange', compact('currency', 'ccrs'));
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
        $currency_id     = $request->input('currency_id');
        
        $currency = Currency::find(intval($currency_id));

        if ( !$currency ) {
            // Die silently
            return '';
        }

        return $currency->conversion_rate;
    }

}