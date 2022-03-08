<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PaymentMethod as PaymentMethod;
use View;

class PaymentMethodsController extends Controller {


   protected $paymentmethod;

   public function __construct(PaymentMethod $paymentmethod)
   {
        $this->paymentmethod = $paymentmethod;
   }

	/**
	 * Display a listing of the resource.
	 * GET /paymentmethods
	 *
	 * @return Response
	 */
	public function index()
	{
		$paymentmethods = $this->paymentmethod->orderBy('name', 'asc')->get();

        return view('payment_methods.index', compact('paymentmethods'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /paymentmethods/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$paymentmethod = $this->paymentmethod;
//		$paymentmethod->deadlines_by = 'days';
		$paymentmethod->payment_is_cash = 0;
		$paymentmethod->auto_direct_debit = 0;
		$paymentmethod->deadlines = array('0' => ['slot' => 0, 'percentage' => 100.0]);


		return view('payment_methods.create', compact('paymentmethod'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /paymentmethods
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, PaymentMethod::$rules);

		// 
		// Lines stuff
		// 
		$deadlines = array();
		$j = 0;

		// Loop...
		$n = intval($request->input('nbrlines'));

        for($i = 0; $i < $n; $i++)
        {
			if ( !$request->has('lineid_'.$i) ) continue;	// Line was deleted on View
        	
        	$deadlines[$j] = array('slot' => $request->input('slot_'.$i), 'percentage' => $request->input('percentage_'.$i));
        	$j++;
        }

        $request->merge( ['deadlines' => $deadlines] );

		$paymentmethod = $this->paymentmethod->create($request->all());

		if ( PaymentMethod::count() == 1 )
			\App\Configuration::updateValue('DEF_CUSTOMER_PAYMENT_METHOD', $paymentmethod->id);

		return redirect('paymentmethods')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $paymentmethod->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /paymentmethods/{id}
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
	 * GET /paymentmethods/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$paymentmethod = $this->paymentmethod->findOrFail($id);
		
		return view('payment_methods.edit', compact('paymentmethod'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /paymentmethods/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$paymentmethod = $this->paymentmethod->findOrFail($id);

		$this->validate($request, PaymentMethod::$rules);

		// 
		// Lines stuff
		// 
		$deadlines = array();
		$j = 0;

		// Loop...
		$n = intval($request->input('nbrlines'));

        for($i = 0; $i < $n; $i++)
        {
			if ( !$request->has('lineid_'.$i) ) continue;	// Line was deleted on View
        	
        	$deadlines[$j] = array('slot' => $request->input('slot_'.$i), 'percentage' => $request->input('percentage_'.$i));
        	$j++;
        }

        $request->merge( ['deadlines' => $deadlines] );

		$paymentmethod->update($request->all());

		return redirect('paymentmethods')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /paymentmethods/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $method = $this->paymentmethod->findOrFail($id);

        try {

            $method->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }


        return redirect('paymentmethods')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}