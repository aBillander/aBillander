<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Tax as Tax;
use App\TaxRule as TaxRule;
use App\State;
use View;

class TaxRulesController extends Controller {


   protected $tax;
   protected $taxrule;

   public function __construct(Tax $tax, TaxRule $taxrule)
   {
        $this->tax = $tax;
        $this->taxrule = $taxrule;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($taxId)
    {
        $tax = $this->tax->find($taxId);
        $taxrules = $this->taxrule->with('country')->with('state')->where('tax_id', '=', $taxId)->orderBy('position', 'asc')->orderBy('name', 'asc')->get();

        return view('tax_rules.index', compact('tax', 'taxrules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($taxId)
    {
        $tax = $this->tax->findOrFail($taxId);
        return view('tax_rules.create', compact('tax'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($taxId, Request $request)
    {
        $tax = $this->tax->findOrFail($taxId);
        $this->validate($request, TaxRule::$rules);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );


        $taxrule = $this->taxrule->create($request->all());

        $tax->taxrules()->save($taxrule);

        return redirect('taxes/'.$taxId.'/taxrules')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $taxrule->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($taxId, $id)
    {
        return $this->edit($taxId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($taxId, $id)
    {
        $tax = $this->tax->findOrFail($taxId);
        $taxrule = $this->taxrule->findOrFail($id);

        $stateList = State::where('country_id', $taxrule->country_id)->orderby('name', 'asc')->pluck('name', 'id')->toArray();

        return view('tax_rules.edit', compact('tax', 'taxrule', 'stateList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($taxId, $id, Request $request)
    {
        $taxrule = TaxRule::findOrFail($id);

        // Handy conversions
        if ( !$request->input('percent') )  $request->merge( ['percent'  => 0.0] );
        if ( !$request->input('amount') )   $request->merge( ['amount'   => 0.0] );
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );
        

        $this->validate($request, TaxRule::$rules);

        $taxrule->update($request->all());

        return redirect('taxes/'.$taxId.'/taxrules')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($taxId, $id)
    {
        $this->taxrule->findOrFail($id)->delete();

        return redirect('taxes/'.$taxId.'/taxrules')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}