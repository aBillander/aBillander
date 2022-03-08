<?php 

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Manufacturer;

class ManufacturersController extends Controller {


   protected $manufacturer;

   public function __construct(Manufacturer $manufacturer)
   {
        $this->manufacturer = $manufacturer;
   }


	/**
	 * Display a listing of the resource.
	 * GET /manufacturers
	 *
	 * @return Response
	 */
	public function index()
	{
        $manufacturers = $this->manufacturer->orderBy('name', 'asc')->get();

        return view('manufacturers.index', compact('manufacturers'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /manufacturers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('manufacturers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /manufacturers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Manufacturer::$rules);

		$manufacturer = $this->manufacturer->create($request->all());

		return redirect('manufacturers')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $manufacturer->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /manufacturers/{id}
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
	 * GET /manufacturers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$manufacturer = $this->manufacturer->findOrFail($id);
		
		return view('manufacturers.edit', compact('manufacturer'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /manufacturers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$manufacturer = $this->manufacturer->findOrFail($id);

		$this->validate($request, Manufacturer::$rules);

		$manufacturer->update($request->all());

		return redirect('manufacturers')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /manufacturers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        // Destroy States
        $manufacturer = $this->manufacturer->findOrFail($id);

        // Destroy Manufacturer
        $manufacturer->delete();

        return redirect('manufacturers')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}