<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\CustomerUser;
// use App\Customer;
// use App\Address;

class AbccCustomerUserController extends Controller 
{


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
		//
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
	public function edit()
	{
		// Get logged in user
        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;

        $tab_index = 'account';
		
		return view('abcc.account.edit', compact('customer_user', 'customer', 'tab_index'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		// Get logged in user
        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;

        // abi_r($customer_user->id);die();


        $vrules = CustomerUser::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $customer_user->id.',id';  // Unique

		if ( $request->input('password') != '' ) {
			$this->validate( $request, $vrules );

			$password = \Hash::make($request->input('password'));
			$request->merge( ['password' => $password] );
			$customer_user->update($request->all());
		} else {
			$this->validate($request, \Arr::except( $vrules, array('password')) );
			$customer_user->update($request->except(['password']));
		}
/*
		if ( \Auth::user()->id == $id ) {
			$language = Language::find( $request->input('language_id') );
			\App::setLocale($language->iso_code);
		}
*/		
		return redirect()->route('abcc.account.edit')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customer_user->id], 'layouts') . $customer_user->getFullName());
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
