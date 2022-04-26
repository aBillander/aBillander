<?php 

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Models\CurrencyConversionRate;
use Illuminate\Http\Request;
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

		CurrencyConversionRate::create([
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

		if ( $currency->id != Context::getContext()->currency->id )
			CurrencyConversionRate::create([
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

		$ccrs = CurrencyConversionRate::where('currency_id', '=', $currency->id)->with('user')->orderBy('date', 'desc')->get();

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


/* ********************************************************************************************* */    


    /**
     * Currency Converter
     *
     * @return 
     */
	public function converter()
	{
		return view('currencies.currency_converter');
	}

    public function converterResult(Request $request)
    {
        // return $request->input('amount');

        // Request dataif(isset($_POST['convert'])) {
		if($request->has('convert')) {
			$from_currency = $request->input('from_currency');
			$to_currency = $request->input('to_currency');
			$amount = $request->input('amount');	
			if($from_currency == $to_currency) {
			 	$data = array('error' => '1');
				echo json_encode( $data );	
				exit;
			}
			$converted_currency=$this->currencyConverter($from_currency, $to_currency, $amount);

			echo $converted_currency;
		}

	}

    // To Do: move this to a Class
    public function currencyConverter($fromCurrency,$toCurrency,$amount) 
    {	
		// https://free.currencyconverterapi.com/

		  $apikey =  Configuration::get('CURRENCY_CONVERTER_API_KEY');

		if ( !$apikey )
		{
			$data = ['error' => '3'];
			return json_encode( $data );
		}

		  $from_Currency = urlencode($fromCurrency);
		  $to_Currency = urlencode($toCurrency);
		  $query =  "{$from_Currency}_{$to_Currency}";

		  
        // Catch for errors
        try{
		  $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        }
        catch(\Exception $e){

                // abi_r($e->getMessage(), true);
			$data = ['error' => '4'];
			return json_encode( $data );
        }

		  $obj = json_decode($json, true);

		  // $val = floatval($obj["$query"]);


		  // $total = $val * $amount;
		  // return number_format($total, 2, '.', '');

		// return $json;

		// Decode JSON response:
		$conversionResult = json_decode($json, true);

		// access the conversion result
		// echo $conversionResult['result'];

		$exhangeRate = floatval($obj["$query"]);
		$convertedAmount = ((float) $amount)*$exhangeRate;
		$data = array( 'exhangeRate' => $exhangeRate, 'convertedAmount' =>$convertedAmount, 'fromCurrency' => strtoupper($fromCurrency), 'toCurrency' => strtoupper($toCurrency));
		return json_encode( $data );	
    }


    // https://www.phpzag.com/convert-currency-using-google-api/
    // https://artisansweb.net/foreign-exchange-rates-api-with-currency-conversion-in-php/
    public function currencyConverter_google($fromCurrency,$toCurrency,$amount) 
    {	
		$fromCurrency = urlencode($fromCurrency);
		$toCurrency = urlencode($toCurrency);	
		$encode_amount = 1;
		$url  = "https://www.google.com/search?q=".$fromCurrency."+to+".$toCurrency;
		$get = file_get_contents($url);
		$data = preg_split('/\D\s(.*?)\s=\s/',$get);
//	abi_r($data);
//	abi_r((float) substr($data[1],0,7));
		if ( !isset($data[1]))
		{
			$data = ['error' => '2'];
			return json_encode( $data );
		}

		$exhangeRate = (float) substr($data[1],0,7);
		$convertedAmount = ((float) $amount)*$exhangeRate;
		$data = array( 'exhangeRate' => $exhangeRate, 'convertedAmount' =>$convertedAmount, 'fromCurrency' => strtoupper($fromCurrency), 'toCurrency' => strtoupper($toCurrency));
		return json_encode( $data );	
    }



    // https://gist.github.com/dcblogdev/8067095
    // https://currency-api.appspot.com/
    // https://medium.com/@MicroPyramid/free-foreign-currency-exchange-rates-api-2a93195649fb
    public function currencyConverter_nowork($fromCurrency,$toCurrency,$amount) 
    {	
	    $url = "https://www.google.com/search?q=".$fromCurrency.$toCurrency;
	    $request = curl_init();
	    $timeOut = 0;
	    curl_setopt ($request, CURLOPT_URL, $url);
	    curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($request, CURLOPT_USERAGENT,"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36");
	    curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut);
	    $response = curl_exec($request);
	    curl_close($request);

	    return json_encode( $response );

	    preg_match('~<span [^>]* id="knowledge-currency__tgt-amount"[^>]*>(.*?)</span>~si', $response, $finalData);

	    // return floatval((floatval(preg_replace("/[^-0-9\.]/","", $finalData[1]))/100) * $amount);

	    return json_encode( $finalData );


		$exhangeRate = floatval((floatval(preg_replace("/[^-0-9\.]/","", $finalData[1]))/100));
		$convertedAmount = $amount*$exhangeRate;
		$data = array( 'exhangeRate' => $exhangeRate, 'convertedAmount' =>$convertedAmount, 'fromCurrency' => strtoupper($fromCurrency), 'toCurrency' => strtoupper($toCurrency));

		return json_encode( $data );	
    }
}