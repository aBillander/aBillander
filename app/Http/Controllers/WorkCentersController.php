<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\WorkCenter;
use View;

class WorkCentersController extends Controller {


   protected $workcenter;

   public function __construct(WorkCenter $workcenter)
   {
        $this->workcenter = $workcenter;
   }

	/**
	 * Display a listing of the resource.
	 * GET /workcenters
	 *
	 * @return Response
	 */
	public function index()
	{
        $workcenters = $this->workcenter->orderBy('alias')->get();

        return view('work_centers.index', compact('workcenters'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /workcenters/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('work_centers.create'); 
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /workcenters
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, WorkCenter::$rules);

		$workcenter = $this->workcenter->create($request->all());

		return redirect('workcenters')
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $workcenter->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /workcenters/{id}
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
	 * GET /workcenters/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$workcenter = $this->workcenter->findOrFail($id);
		
		return view('work_centers.edit', compact('workcenter'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /workcenters/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$workcenter = $this->workcenter->findOrFail($id);

		$this->validate($request, WorkCenter::$rules);
		
		$workcenter->update($request->all());

		return redirect('workcenters')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /workcenters/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->workcenter->findOrFail($id)->delete();

        return redirect('workcenters')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}