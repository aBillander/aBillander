<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ecotax;

class EcotaxesController extends Controller {


   protected $ecotax;

   public function __construct(Ecotax $ecotax)
   {
        $this->ecotax = $ecotax;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $ecotaxes = $this->ecotax->orderBy('id', 'asc')->get();

        return view('ecotaxes.index', compact('ecotaxes'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('ecotaxes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Ecotax::$rules);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );


        $ecotax = $this->ecotax->create($request->all());

        return redirect('ecotaxes')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $ecotax->id], 'layouts') . $request->input('name'));
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
        $ecotax = $this->ecotax->findOrFail($id);

        return view('ecotaxes.edit', compact('ecotax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $ecotax = Ecotax::findOrFail($id);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );


        $this->validate($request, Ecotax::$rules);

        $ecotax->update($request->all());

        return redirect('ecotaxes')
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
        $ecotax = $this->ecotax->findOrFail($id);

        try {

            $ecotax->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }

        return redirect('ecotaxes')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}