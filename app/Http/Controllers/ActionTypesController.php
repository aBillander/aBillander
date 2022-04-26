<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ActionType;

class ActionTypesController extends Controller {
   
//   use Traits\ActivableTrait;


   protected $actiontype;

   public function __construct(ActionType $actiontype)
   {
        $this->actiontype = $actiontype;
   }

	/**
	 * Display a listing of the resource.
	 * GET /actiontypes
	 *
	 * @return Response
	 */
	public function index()
	{
		// echo $this->toggleStatus();

		$actiontypes = $this->actiontype->orderBy('id', 'asc')->get();

        return view('action_types.index', compact('actiontypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /actiontypes/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('action_types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /actiontypes
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, ActionType::$rules);

		$actiontype = $this->actiontype->create($request->all());

		return redirect('actiontypes')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $actiontype->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /actiontypes/{id}
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
	 * GET /actiontypes/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$actiontype = $this->actiontype->findOrFail($id);
		
		return view('action_types.edit', compact('actiontype'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /actiontypes/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$actiontype = $this->actiontype->findOrFail($id);

		if($request->has('toggleStatus'))
		{
			return $this->toggleStatus( $actiontype, $request );
		}

		$this->validate($request, ActionType::$rules);

		$actiontype->update($request->all());

		return redirect('actiontypes')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /actiontypes/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $type = $this->actiontype->findOrFail($id);

        try {

            $type->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }

        return redirect('actiontypes')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}