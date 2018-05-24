<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\OptionGroup as OptionGroup;
use View;

class OptionGroupsController extends Controller {


   protected $optiongroup;

   public function __construct(OptionGroup $optiongroup)
   {
        $this->optiongroup = $optiongroup;
   }

	/**
	 * Display a listing of the resource.
	 * GET /optiongroups
	 *
	 * @return Response
	 */
	public function index()
	{
        $optiongroups = $this->optiongroup->with('options')->orderBy('position', 'asc')->orderBy('name', 'asc')->get();

        return view('option_groups.index', compact('optiongroups'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /optiongroups/create
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('option_groups.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /optiongroups
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, OptionGroup::$rules);

		$optiongroup = $this->optiongroup->create($request->all());

		return redirect('optiongroups')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $optiongroup->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /optiongroups/{id}
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
	 * GET /optiongroups/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $optiongroup = $this->optiongroup->findOrFail($id);

        return view('option_groups.edit', compact('optiongroup'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /optiongroups/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$optiongroup = OptionGroup::findOrFail($id);

		if ( isset(OptionGroup::$rules['name']) ) OptionGroup::$rules['name'] .= $optiongroup->id;

		$this->validate($request, OptionGroup::$rules);

		$optiongroup->update($request->all());

		return redirect('optiongroups')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /optiongroups/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->optiongroup->findOrFail($id)->delete();

        return redirect('optiongroups')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}