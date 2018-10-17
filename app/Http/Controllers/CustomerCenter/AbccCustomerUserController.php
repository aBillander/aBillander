<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\CustomerUser;

class AbccCustomerUserController extends Controller {


   protected $customer_user;

   public function __construct(CustomerUser $customer_user)
   {
        $this->middleware('auth:customer');

        $this->customer_user = $customer_user;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$payments = [];

        return view('abcc.vouchers.index', compact('payments'));
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
	public function edit(Request $request)
	{
		// Get logged in user

		return 'OK';

		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;
		
		$payment = null;
		
		return view('customer_vouchers.edit', compact('payment', 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//
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
