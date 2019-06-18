<?php

namespace aBillander\SepaSpain\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \App\Configuration;
use aBillander\SepaSpain\SepaDirectDebit;

use AbcAeffchen\SepaUtilities\SepaUtilities;
use AbcAeffchen\Sephpa\SephpaDirectDebit;

class SepaDirectDebitsController extends Controller
{

   protected $directdebit;

   public function __construct(SepaDirectDebit $directdebit)
   {
        $this->directdebit = $directdebit;
   }

    /**
     * Display a listing of the resource.
	 * GET /fsx_loggers
     *
     * @return Response
     */

    public function index()
    {
        $sdds = $this->directdebit->orderBy('document_date', 'desc')->orderBy('id', 'desc');

        $sdds = $sdds->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $sdds->setPath('sepasp/directdebits');

        return view('sepa_es::direct_debits.index', compact('sdds'));
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /fsx_loggers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return 'Nothing here, so far. Stay tunned!';

		return view('sepa_es::direct_debits.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /fsx_loggers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // return $request->input('desc').' - OK - '.$request->input('cod');

        $fsolpaymethods = FSxTools::getFormasDePagoList();

        $fsolpaymethods[$request->input('cod')] = $request->input('desc');

        // Save Payment Methods Cache
        Configuration::updateValue('FSX_FORMAS_DE_PAGO_CACHE', json_encode($fsolpaymethods));

		return redirect()->route('fsx.configuration.paymentmethods')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $request->input('cod')], 'layouts') . $request->input('desc'));
    }

	/**
	 * Display the specified resource.
	 * GET /fsx_loggers/{id}
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
	 * GET /fsx_loggers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($cod, Request $request)
	{
        // return $request->input('desc').' - OK - '.$cod;

        $fsolpaymethods = FSxTools::getFormasDePagoList();

        $fsolpaymethods[$request->input('cod')] = $request->input('desc');

        // Save Payment Methods Cache
        Configuration::updateValue('FSX_FORMAS_DE_PAGO_CACHE', json_encode($fsolpaymethods));

		return redirect()->route('fsx.configuration.paymentmethods')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $cod], 'layouts') . $request->input('desc'));
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($cod)
	{
        // return 'OK - '.$cod;

        $fsolpaymethods = FSxTools::getFormasDePagoList();

        unset($fsolpaymethods[$cod]);

        // Save Payment Methods Cache
        Configuration::updateValue('FSX_FORMAS_DE_PAGO_CACHE', json_encode($fsolpaymethods));

		return redirect()->route('fsx.configuration.paymentmethods')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $cod], 'layouts'));
    }

}