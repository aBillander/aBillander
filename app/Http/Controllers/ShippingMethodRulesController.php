<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ShippingMethod;
use App\ShippingMethodServiceLine as ShippingMethodRule;

class ShippingMethodRulesController extends Controller
{
   protected $shippingmethod;
   protected $shippingmethodrule;

   public function __construct(ShippingMethod $shippingmethod, ShippingMethodRule $shippingmethodrule)
   {
        $this->shippingmethod     = $shippingmethod;
        $this->shippingmethodrule = $shippingmethodrule;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($shippingmethodId)
    {
        $shippingmethod = $this->shippingmethod
                                ->with('rules')
                                ->with('rules.country')
                                ->with('rules.state')
                                ->find($shippingmethodId);
        // $lines = $this->shippingmethodtableline->with('country')->with('state')->where('shipping_method_id', '=', $shippingmethodId)->orderBy('amount', 'asc')->get();

        return view('shipping_method_rules.index', compact('shippingmethod'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($shippingmethodId)
    {
        $shippingmethod = $this->shippingmethod->findOrFail($shippingmethodId);

        $countryList =  \App\Country::orderby('name', 'asc')->pluck('name', 'id')->toArray();
        $stateList = [];

        return view('shipping_method_rules.create', compact('shippingmethod', 'countryList', 'stateList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($shippingmethodId, Request $request)
    {
        $shippingmethod = $this->shippingmethod->findOrFail($shippingmethodId);

        $this->validate($request, ShippingMethodRule::$rules);

        $rule = $this->shippingmethodrule->create($request->all());

        $shippingmethod->rules()->save($rule);

        return redirect()->route('shippingmethods.shippingmethodrules.index', $shippingmethod->id)
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $rule->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($shippingmethodId, $id)
    {
        return $this->edit($shippingmethodId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($shippingmethodId, $id)
    {
        $shippingmethod = $this->shippingmethod->findOrFail($shippingmethodId);
        $rule = $this->shippingmethodrule->findOrFail($id);

        return view('shipping_method_rules.edit', compact('shippingmethod', 'rule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($shippingmethodId, Request $request, $id)
    {
        $rule = $this->shippingmethodrule->findOrFail($id);

        $this->validate($request, ShippingMethodRule::$rules);

        $rule->update($request->all());

        return redirect()->route('shippingmethods.shippingmethodrules.index', $shippingmethodId)
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $rule->id], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($shippingmethodId, $id)
    {
        $rule = $this->shippingmethodrule->findOrFail($id)->delete();

        return redirect()->route('shippingmethods.shippingmethodrules.index', $shippingmethodId)
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
