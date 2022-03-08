<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\ShippingMethod;
use App\Models\Tax;
use Illuminate\Http\Request;
use View;

class ShippingMethodsController extends Controller {


   protected $shippingmethod;

   public function __construct(ShippingMethod $shippingmethod)
   {
        $this->shippingmethod = $shippingmethod;
   }

	/**
	 * Display a listing of the resource.
	 * GET /shippingmethods
	 *
	 * @return Response
	 */
	public function index()
	{
		$shippingmethods = $this->shippingmethod
						->with('tax')
						->orderBy('carrier_id', 'asc')
						->orderBy('name', 'asc')
						->get();

		$system_default = ShippingMethod::getSystemDefault();

        return view('shipping_methods.index', compact('shippingmethods', 'system_default'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /shippingmethods/create
	 *
	 * @return Response->with('info', )
	 */
	public function create()
	{
		$shippingmethod = $this->shippingmethod;
		$shippingmethod->carrier_id = null;
		$shippingmethod->free_shipping_from = 0.0;
		$shippingmethod->class_name = 'App\ShippingMethods\\';

		$billing_typeList = ShippingMethod::getBillingTypeList();

		$taxList = Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

		return view('shipping_methods.create', compact('shippingmethod', 'billing_typeList', 'taxList'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /shippingmethods
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, ShippingMethod::$rules);

		$shippingmethod = $this->shippingmethod->create($request->all());

		if ( ShippingMethod::count() == 1 )
			Configuration::updateValue('DEF_CUSTOMER_PAYMENT_METHOD', $shippingmethod->id);

		return redirect('shippingmethods')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $shippingmethod->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /shippingmethods/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// Temporarily:
		return $this->edit($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /shippingmethods/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$shippingmethod = $this->shippingmethod->findOrFail($id);

		$billing_typeList = ShippingMethod::getBillingTypeList();

		$taxList = Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();
		
		return view('shipping_methods.edit', compact('shippingmethod', 'billing_typeList', 'taxList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /shippingmethods/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$shippingmethod = $this->shippingmethod->findOrFail($id);

		$this->validate($request, ShippingMethod::$rules);

		$shippingmethod->update($request->all());

		return redirect('shippingmethods')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /shippingmethods/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $method = $this->shippingmethod->findOrFail($id);

        try {

            $method->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }

        return redirect('shippingmethods')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}