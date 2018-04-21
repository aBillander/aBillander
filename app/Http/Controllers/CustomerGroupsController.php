<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\CustomerGroup as CustomerGroup;
use View;

class CustomerGroupsController extends Controller {


   protected $customergroup;

   public function __construct(CustomerGroup $customergroup)
   {
        $this->customergroup = $customergroup;
   }
	/**
	 * Display a listing of the resource.
	 * GET /customergroups
	 *
	 * @return Response
	 */
	public function index()
	{
        
        $customergroups = $this->customergroup->orderBy('name')->get();

        return view('customer_groups.index', compact('customergroups'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /customergroups/create
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('customer_groups.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /customergroups
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, CustomerGroup::$rules);

		$customergroup = $this->customergroup->create($request->all());

		return redirect('customergroups')
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customergroup->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /customergroups/{id}
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
	 * GET /customergroups/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $customergroup = $this->customergroup->findOrFail($id);

        return view('customer_groups.edit', compact('customergroup'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /customergroups/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$customergroup = $this->customergroup->findOrFail($id);

		$this->validate($request, Customergroup::$rules);

		$customergroup->update($request->all());

		return redirect('customergroups')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /customergroups/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->customergroup->findOrFail($id)->delete();

        return redirect('customergroups')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}