<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration;
use App\Models\Address;

use View;

class AddressesController extends Controller {


   protected $address;

   public function __construct(Address $address)
   {
        $this->address = $address;
   }

	/**
	 * Display a listing of the resource.
	 * GET /addresses
	 *
	 * @return Response
	 */
	public function index()
	{
		echo 'Direcciones Postales';
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /addresses/create
	 *
	 * @return Response
	 */
	public function create($owner_id, Request $request)
	{
		/*  http://localhost/aBillander5/public/customers/5/addresses/create
		$segment1 = $request->segment(1);
		$segment2 = $request->segment(2);
		$segment3 = $request->segment(3);
		$segment4 = $request->segment(4);

		echo ucfirst(\Str::singular($segment1)).'<br>'.$segment2.'<br>'.$segment3.'<br>'.$segment4.'<br>';

		dd($owner_id);

		Customer
		5
		addresses
		create
		"5"

		*/

		$model_name = ucfirst(\Str::singular($request->segment(1)));
		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

		// Check that the class exists before trying to use it
		if( !class_exists('\\App\\Models\\'.$model_name) ) {
		    // Do stuff for when class does not exist
		    echo $model_name.' NO existe'; die();
		}

	    $model_var = strtolower($model_name);
	    $model_name_full = '\\App\\Models\\'.$model_name;
	    $$model_var = $model_name_full::findOrFail($owner_id);

		// $customer = Customer::find($owner_id);

		return view('addresses.create', compact('customer', 'model_name', 'owner_id', 'back_route'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /addresses
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$model_name = $request->input('model_name');
		$owner_id = $request->input('owner_id');
		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

		// Check that the class exists before trying to use it
		if( !class_exists('\\App\\Models\\'.$model_name) ) {
		    // Do stuff for when class does not exist
		    echo $model_name.' NO existe'; die();
		}

	    $model_var = strtolower($model_name);
	    $model_name_full = '\\App\\Models\\'.$model_name;
	    $$model_var = $model_name_full::with('addresses', 'address')->findOrFail($owner_id);

        $customer = $$model_var;

        if ( !$request->input('country') ) $request->merge( ['country' => Configuration::get('DEF_COUNTRY_NAME')] );
        // $this->validate($request, Address::$rules);
            $request->merge( ['model_name' => 'Customer'] );
        $address = $this->address->create($request->all());
        $customer->addresses()->save($address);

        return redirect($back_route)
            ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));

	}

	/**
	 * Display the specified resource.
	 * GET /addresses/{id}
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
	 * GET /addresses/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$address = Address::find($id);
		$model_name = $address->addressable_type;
		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

	//	if ( !Route::has($back_route) AND !Route::hasNamedRoute($back_route) )


		// Check that the class exists before trying to use it
		if( !class_exists($model_name) ) {
		    // Do stuff for when class does not exist
		    echo $model_name.' NO existe '.$model_name; die();
		}

	    $model_var = strtolower($model_name);
	    $model_name_full = $model_name;
	    $customer = $model_name_full::find($address->addressable_id);

//	    abi_r($customer, true);

		// $customer = Customer::find($owner_id);

		return view('addresses.edit', compact('customer', 'model_name', 'address', 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /addresses/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$address = Address::findOrFail($id);
		$model_name = $address->model_name;
		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Address::$rules);
		$address->update($request->all());

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /addresses/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
    {
        $back_route = $request->input('back_route', '');
		
        return redirect( $back_route )
            ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') );
	}

}