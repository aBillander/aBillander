<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sequence;
use View;

class SequencesController extends Controller {


   protected $sequence;

   public function __construct(Sequence $sequence)
   {
        $this->sequence = $sequence;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $sequences = $this->sequence->orderBy('model_name', 'ASC')->get();

        return view('sequences.index', compact('sequences'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('sequences.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Sequence::get_rules());

		$sequence = $this->sequence->create($request->all());

		return redirect('sequences')
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $sequence->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$sequence = Sequence::findOrFail($id);
		
		return view('sequences.edit', compact('sequence'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$sequence = Sequence::findOrFail($id);

		$this->validate($request, Sequence::get_rules());

		$sequence->update($request->all());

		return redirect('sequences')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->sequence->findOrFail($id)->delete();

        return redirect('sequences')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
