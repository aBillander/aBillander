<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Models\Country;
use View;

class CountriesController extends Controller {


   protected $country;

   public function __construct(Country $country)
   {
        $this->country = $country;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $countries = $this->country->orderBy('name', 'asc')->get();

        // return $countries;

        return view('countries.index', compact('countries'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('countries.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Country::$rules);

		$country = $this->country->create($request->all());

		return redirect('countries')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $country->id], 'layouts') . $request->input('name'));
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
		$country = $this->country->findOrFail($id);
		
		return view('countries.edit', compact('country'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$country = $this->country->findOrFail($id);

		$this->validate($request, Country::$rules);

		$country->update($request->all());

		return redirect('countries')
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
        // Destroy States
        $country = $this->country->findOrFail($id);

        if ( $country->states()->count() )
        	foreach ($country->states as $state) {
        		$state->delete();
        	}

        // Destroy Country
        $country->delete();

        return redirect('countries')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

    public function getStates($countryId)
    {
        // if ( request->ajax() )
        $country = $this->country->find($countryId);

        $states = $country ? $country->states()->getQuery()->get(['id', 'name']) : [] ;

        return Response::json($states);
    }

}
