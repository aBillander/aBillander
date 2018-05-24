<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\CustomerInvoiceLine as CustomerInvoiceLine;
use View;

class CustomerInvoiceLinesController extends Controller {


   protected $customerInvoiceLine;

   public function __construct(CustomerInvoiceLine $customerInvoiceLine)
   {
        $this->customerInvoiceLine = $customerInvoiceLine;
   }

	/**
	 * Display a listing of customerinvoicelines
	 *
	 * @return Response
	 */
	public function index()
	{
		$customerinvoicelines = Customerinvoiceline::all();

		return View::make('customerinvoicelines.index', compact('customerinvoicelines'));
	}

	/**
	 * Show the form for creating a new customerinvoiceline
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('customerinvoicelines.create');
	}

	/**
	 * Store a newly created customerinvoiceline in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Customerinvoiceline::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Customerinvoiceline::create($data);

		return Redirect::route('customerinvoicelines.index');
	}

	/**
	 * Display the specified customerinvoiceline.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$customerinvoiceline = Customerinvoiceline::findOrFail($id);

		return View::make('customerinvoicelines.show', compact('customerinvoiceline'));
	}

	/**
	 * Show the form for editing the specified customerinvoiceline.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$customerinvoiceline = Customerinvoiceline::find($id);

		return View::make('customerinvoicelines.edit', compact('customerinvoiceline'));
	}

	/**
	 * Update the specified customerinvoiceline in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$customerinvoiceline = Customerinvoiceline::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Customerinvoiceline::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$customerinvoiceline->update($data);

		return Redirect::route('customerinvoicelines.index');
	}

	/**
	 * Remove the specified customerinvoiceline from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Customerinvoiceline::destroy($id);

		return Redirect::route('customerinvoicelines.index');
	}

}
