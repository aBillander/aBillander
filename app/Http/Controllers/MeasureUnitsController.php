<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\MeasureUnit as MeasureUnit;
use View;

class MeasureUnitsController extends Controller {


   protected $measureunit;

   public function __construct(MeasureUnit $measureunit)
   {
        $this->measureunit = $measureunit;
   }

	/**
	 * Display a listing of the resource.
	 * GET /currencies
	 *
	 * @return Response
	 */
	public function index()
	{
		$measureunits = $this->measureunit->orderBy('type', 'asc')->get();

        return view('measure_units.index', compact('measureunits'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /currencies/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('measure_units.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /currencies
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, MeasureUnit::$rules);

		$measureunit = $this->measureunit->create($request->all());

		$url = 'measureunits';
		if ( $caller = $request->input('caller_url', '') )
			$url = $caller;
		
		return redirect($url)
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $measureunit->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /currencies/{id}
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
	 * GET /currencies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$measureunit = $this->measureunit->findOrFail($id);
		
		return view('measure_units.edit', compact('measureunit'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$measureunit = $this->measureunit->findOrFail($id);

		$this->validate($request, MeasureUnit::$rules);

		$measureunit->update($request->all());

		return redirect('measureunits')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /currencies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        // check if measure unit is in use (short test)
        $in_use= false;
        $msg = '';

        // Products
        if ( \App\Product::where('measure_unit_id', $id)->first() ) {

        	$in_use = true;
        	$msg = 'Prod';

        } else 

        // ProductBOMLines
        if ( \App\ProductBOMLine::where('measure_unit_id', $id)->first() ) {

        	$in_use = true;
        	$msg = 'BOM';

        	// To do: Check alternative units

        } else 

        // Default measure unit
        if (   (\App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS') == $id) 
        	|| (\App\Configuration::get('DEF_MEASURE_UNIT_FOR_BOMS'    ) == $id) ) {

        	$in_use = true;
        	$msg = 'def';
        }

        if ( $in_use )
	        return redirect('measureunits')
					->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts') . ' [' . $msg . ']');


        // So far, so good
		$this->measureunit->findOrFail($id)->delete();

        return redirect('measureunits')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}