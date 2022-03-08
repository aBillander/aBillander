<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Carrier;
use App\Models\Configuration;
use Illuminate\Http\Request;
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



/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */

    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxCarrierSearch(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;

//        if ( $query )

        if ($request->has('carrier_id'))
        {
            $search = $request->carrier_id;

            $carriers = $this->carrier->find( $search );

            return response()->json( $carriers );
        }

        if ($request->has('term'))
        {
            $search = $request->term;

            $carriers = $this->carrier
            						->where(   'name',  'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'alias', 'LIKE', '%'.$search.'%' )
                                    ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                    ->get();

            return response()->json( $carriers );
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
    }

}