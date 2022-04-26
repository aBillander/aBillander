<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\OptionGroup;
use App\Models\Option;
use View;

class OptionsController extends Controller {


   protected $optiongroup;
   protected $option;

   public function __construct(OptionGroup $optiongroup, Option $option)
   {
        $this->optiongroup = $optiongroup;
        $this->option = $option;
   }

	/**
	 * Display a listing of the resource.
	 * GET /options
	 *
	 * @return Response
	 */
	public function index($optionGroupId)
	{
        $optiongroup = $this->optiongroup->find($optionGroupId);
        $options = $this->option->where('option_group_id', '=', $optionGroupId)->orderBy('position', 'asc')->orderBy('name', 'asc')->get();

        return view('options.index', compact('optiongroup', 'options'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /options/create
	 *
	 * @return Response
	 */
	public function create($optionGroupId)
	{
        $optiongroup = $this->optiongroup->findOrFail($optionGroupId);
        return view('options.create', compact('optiongroup'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /options
	 *
	 * @return Response
	 */
	public function store($optionGroupId, Request $request)
	{
		$optiongroup = $this->optiongroup->findOrFail($optionGroupId);

		$this->validate($request, Option::$rules);

		$option = $this->option->create($request->all());

		$optiongroup->options()->save($option);

		return redirect('optiongroups/'.$optionGroupId.'/options')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $option->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /options/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($optionGroupId, $id)
	{
		return $this->edit($optionGroupId, $id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /options/{id}/editcompactcompactcompact
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($optionGroupId, $id)
	{
        $optiongroup = $this->optiongroup->findOrFail($optionGroupId);
        $option = $this->option->findOrFail($id);

        return view('options.edit', compact('optiongroup', 'option'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /options/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($optionGroupId, $id, Request $request)
	{
		$option = Option::findOrFail($id);

		$this->validate($request, Option::$rules);

		$option->update($request->all());

		return redirect('optiongroups/'.$optionGroupId.'/options')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /options/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($optionGroupId, $id)
	{
        $this->option->findOrFail($id)->delete();

        return redirect('optiongroups/'.$optionGroupId.'/options')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}