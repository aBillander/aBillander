<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Carrier as Carrier;
use View;

class CarriersController extends Controller {
   
//   use Traits\ActivableTrait;


   protected $carrier;

   public function __construct(Carrier $carrier)
   {
        $this->carrier = $carrier;
   }

	/**
	 * Display a listing of the resource.
	 * GET /carriers
	 *
	 * @return Response
	 */
	public function index()
	{
		// echo $this->toggleStatus();

		$carriers = $this->carrier->orderBy('id', 'asc')->get();

        return view('carriers.index', compact('carriers'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /carriers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('carriers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /carriers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Carrier::$rules);

		$carrier = $this->carrier->create($request->all());

		return redirect('carriers')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $carrier->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /carriers/{id}
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
	 * GET /carriers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$carrier = $this->carrier->findOrFail($id);
		
		return view('carriers.edit', compact('carrier'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /carriers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$carrier = $this->carrier->findOrFail($id);

		if($request->has('toggleStatus'))
		{
			return $this->toggleStatus( $carrier, $request );
		}

		$this->validate($request, Carrier::$rules);

		$carrier->update($request->all());

		return redirect('carriers')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /carriers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->carrier->findOrFail($id)->delete();

        return redirect('carriers')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}