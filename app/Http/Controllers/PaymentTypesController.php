<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\PaymentType;
use View;

class PaymentTypesController extends Controller {
   
//   use Traits\ActivableTrait;


   protected $paymenttype;

   public function __construct(PaymentType $paymenttype)
   {
        $this->paymenttype = $paymenttype;
   }

	/**
	 * Display a listing of the resource.
	 * GET /paymenttypes
	 *
	 * @return Response
	 */
	public function index()
	{
		// echo $this->toggleStatus();

		$paymenttypes = $this->paymenttype->orderBy('id', 'asc')->get();

        return view('payment_types.index', compact('paymenttypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /paymenttypes/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('payment_types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /paymenttypes
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, PaymentType::$rules);

		$paymenttype = $this->paymenttype->create($request->all());

		return redirect('paymenttypes')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $paymenttype->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /paymenttypes/{id}
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
	 * GET /paymenttypes/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$paymenttype = $this->paymenttype->findOrFail($id);
		
		return view('payment_types.edit', compact('paymenttype'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /paymenttypes/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$paymenttype = $this->paymenttype->findOrFail($id);

		if($request->has('toggleStatus'))
		{
			return $this->toggleStatus( $paymenttype, $request );
		}

		$this->validate($request, PaymentType::$rules);

		$paymenttype->update($request->all());

		return redirect('paymenttypes')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /paymenttypes/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $type = $this->paymenttype->findOrFail($id);

        try {

            $type->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }

        return redirect('paymenttypes')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}