<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Ecotax;
use App\Models\EcotaxRule;
use View;

class EcotaxRulesController extends Controller {


   protected $ecotax;
   protected $ecotaxrule;

   public function __construct(Ecotax $ecotax, EcotaxRule $ecotaxrule)
   {
        $this->ecotax = $ecotax;
        $this->ecotaxrule = $ecotaxrule;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($ecotaxId)
    {
        $ecotax = $this->ecotax->find($ecotaxId);
        $ecotaxrules = $this->ecotaxrule->with('country')->with('state')->where('ecotax_id', '=', $ecotaxId)->orderBy('position', 'asc')->orderBy('name', 'asc')->get();

        return view('ecotax_rules.index', compact('ecotax', 'ecotaxrules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($ecotaxId)
    {
        $ecotax = $this->ecotax->findOrFail($ecotaxId);
        return view('ecotax_rules.create', compact('ecotax'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($ecotaxId, Request $request)
    {
        $ecotax = $this->ecotax->findOrFail($ecotaxId);
        $this->validate($request, EcotaxRule::$rules);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );


        $ecotaxrule = $this->ecotaxrule->create($request->all());

        $ecotax->ecotaxrules()->save($ecotaxrule);

        return redirect('ecotaxes/'.$ecotaxId.'/ecotaxrules')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $ecotaxrule->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($ecotaxId, $id)
    {
        return $this->edit($ecotaxId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($ecotaxId, $id)
    {
        $ecotax = $this->ecotax->findOrFail($ecotaxId);
        $ecotaxrule = $this->ecotaxrule->findOrFail($id);

        return view('ecotax_rules.edit', compact('ecotax', 'ecotaxrule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($ecotaxId, $id, Request $request)
    {
        $ecotaxrule = EcotaxRule::findOrFail($id);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );
        

        $this->validate($request, EcotaxRule::$rules);

        $ecotaxrule->update($request->all());

        return redirect('ecotaxes/'.$ecotaxId.'/ecotaxrules')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($ecotaxId, $id)
    {
        $this->ecotaxrule->findOrFail($id)->delete();

        return redirect('ecotaxes/'.$ecotaxId.'/ecotaxrules')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}