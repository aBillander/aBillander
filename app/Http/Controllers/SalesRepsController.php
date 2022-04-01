<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Language;
use App\Models\SalesRep;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use View;

class SalesRepsController extends Controller {


   protected $salesrep;
   protected $address;

   public function __construct(SalesRep $salesrep, Address $address)
   {
        $this->salesrep = $salesrep;
        $this->address = $address;
   }

	/**
	 * Display a listing of the resource.
	 * GET /salesreps
	 *
	 * @return Response
	 */
	public function index()
	{
        // $salesreps = $this->salesrep->with('address')->get();
        $salesreps = $this->salesrep->get();

        return view('sales_reps.index', compact('salesreps'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /salesreps/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('sales_reps.create'); 
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /salesreps
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// $request->merge( $request->input('address') );

		$this->validate($request, SalesRep::$rules);

		$salesrep = $this->salesrep->create($request->all());
//			$request->merge( ['model_name' => 'SalesRep', 'notes' => ''] );
//		$address = $this->address->create($request->all());
//		$salesrep->address()->save($address);

		return redirect('salesreps')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $salesrep->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /salesreps/{id}
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
	 * GET /salesreps/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// $salesrep = $this->salesrep->with('address')->findOrFail($id);
		$salesrep = $this->salesrep->findOrFail($id);

		$languageList =  Language::pluck('name', 'id')->toArray();
		$warehouseList =  Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();

		// abi_r($languageList);die();
		
		return view('sales_reps.edit', compact('salesrep', 'languageList', 'warehouseList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /salesreps/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$salesrep = SalesRep::findOrFail($id);
//		$address = $salesrep->address;

		// $this->validate($request, Address::related_rules());
		$this->validate($request, SalesRep::$rules);

		// http://stackoverflow.com/questions/17950118/laravel-eloquent-how-to-update-a-model-and-related-models-in-one-go
		$salesrep->update( $request->all() );
/*		$salesrep->update( array_merge($request->all(), ['alias' => $request->input('address.alias')] ) );
			$request->merge($request->input('address'));
			$request->merge(['notes' => '']);  
		$address->update($request->except(['address']));
*/
		return redirect('salesreps')
				->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /salesreps/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->salesrep->findOrFail($id)->delete();

        return redirect('salesreps')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers($id, Request $request)
    {
        $salesrep_users = $this->salesrep->with('users')->findOrFail($id)->users;
        
        return view('sales_reps._panel_salesrep_users_list', compact('id', 'salesrep_users'));
    }
}