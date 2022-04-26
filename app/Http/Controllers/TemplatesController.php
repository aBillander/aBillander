<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Template;
use View;

class TemplatesController extends Controller {


   protected $template;

   public function __construct(Template $template)
   {
        $this->template = $template;
   }

	/**
	 * Display a listing of the resource.
	 * GET /templates
	 *
	 * @return Response
	 */
	public function index()
	{
		$templates = $this->template->orderBy('model_name', 'asc')->get();

        return view('templates.index', compact('templates'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /templates/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('templates.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /templates
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Template::$rules);

		$template = $this->template->create($request->all());

		return redirect('templates')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $template->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /templates/{id}
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
	 * GET /templates/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$template = $this->template->findOrFail($id);
		
		return view('templates.edit', compact('template'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /templates/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$template = $this->template->findOrFail($id);

		$this->validate($request, Template::$rules);

		$template->update($request->all());

		return redirect('templates')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /templates/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->template->findOrFail($id)->delete();

        return redirect('templates')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}