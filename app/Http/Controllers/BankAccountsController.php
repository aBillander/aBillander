<?php

namespace App\Http\Controllers;

use App\BankAccount;
use Illuminate\Http\Request;

class BankAccountsController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /bankaccounts
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /bankaccounts/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /bankaccounts
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /bankaccounts/{id}
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
	 * GET /bankaccounts/{id}/edit
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
	 * PUT /bankaccounts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /bankaccounts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/* ************************************************************************************* */


	public function ibanCalculate(Request $request)
	{
		//
	    $ccc_entidad = $request->input('ccc_entidad');
	    $ccc_oficina = $request->input('ccc_oficina');
	    $ccc_control = $request->input('ccc_control');
	    $ccc_cuenta  = $request->input('ccc_cuenta');

	    $value = BankAccount::esIbanCalculator($ccc_entidad, $ccc_oficina, $ccc_control, $ccc_cuenta);

        return response()->json( [
                'msg' => 'OK',
                'data' => ['iban' => $value]
        ] );
	}

}