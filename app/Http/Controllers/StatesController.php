<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Country as Country;
use App\State as State;

class StatesController extends Controller {


   protected $country;
   protected $state;

   public function __construct(Country $country, State $state)
   {
        $this->country = $country;
        $this->state = $state;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($countryId)
	{
        $country = $this->country->find($countryId);
        $states = $this->state->where('country_id', '=', $countryId)->orderBy('name', 'asc')->get();

        return view('states.index', compact('country', 'states'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($countryId)
	{
        $country = $this->country->findOrFail($countryId);
        return view('states.create', compact('country'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($countryId, Request $request)
	{
		$country = $this->country->findOrFail($countryId);

		$this->validate($request, State::$rules);

		$state = $this->state->create($request->all());

		$country->states()->save($state);
		$country->update(['contains_states' => '1']);

		return redirect('countries/'.$countryId.'/states')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $state->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($countryId, $id)
	{
		return $this->edit($countryId, $id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($countryId, $id)
	{
        $country = $this->country->findOrFail($countryId);
        $state = $this->state->findOrFail($id);

        return view('states.edit', compact('country', 'state'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($countryId, $id, Request $request)
	{
		$state = State::findOrFail($id);

		$this->validate($request, State::$rules);

		$state->update($request->all());

		return redirect('countries/'.$countryId.'/states')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($countryId, $id)
	{
        $this->state->findOrFail($id)->delete();

        $country = $this->country->findOrFail($countryId);

		if ( !$country->states()->count() )
			$country->update(['contains_states' => '0']);

        return redirect('countries/'.$countryId.'/states')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
