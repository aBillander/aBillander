<?php 

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Language;
use View;

class LanguagesController extends Controller {


   protected $language;

   public function __construct(Language $language)
   {
        $this->language = $language;
   }
    /**
     * Display a listing of the resource.
	 * GET /languages
     *
     * @return Response
     */

    public function index()
    {
        $languages = $this->language->orderBy('id', 'asc')->get();

        return view('languages.index', compact('languages'));
        
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /languages/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('languages.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /languages
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Language::$rules);

		$language = $this->language->create($request->all());

		return redirect('languages')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $language->id], 'layouts') . $request->input('name'));
    }

	/**
	 * Display the specified resource.
	 * GET /languages/{id}
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
	 * GET /languages/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$language = Language::findOrFail($id);

		return view('languages.edit', compact('language'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /languages/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$language = Language::findOrFail($id);

		$this->validate($request, Language::$rules);

		$language->update($request->all());

		return redirect('languages')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /languages/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->language->findOrFail($id)->delete();

        return redirect('languages')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}