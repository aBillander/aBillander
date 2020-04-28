<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CustomerOrderTemplate;
use App\CustomerOrderTemplateLine;

use App\Customer;
use App\Template;

class CustomerOrderTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerordertemplates = CustomerOrderTemplate::with('customer')->orderBy('id', 'asc')->get();

        return view('customer_order_templates.index', compact('customerordertemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_order_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, CustomerOrderTemplate::$rules);

        $customerordertemplate = CustomerOrderTemplate::create($request->all());

        return redirect('customerordertemplates')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerordertemplate->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerOrderTemplate $customerordertemplate)
    {
        return $this->edit($customerordertemplate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerOrderTemplate $customerordertemplate)
    {
        return view('customer_order_templates.edit', compact('customerordertemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerOrderTemplate $customerordertemplate)
    {
        $this->validate($request, CustomerOrderTemplate::$rules);

        $customerordertemplate->update($request->all());

        return redirect('customerordertemplates')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customerordertemplate->id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrderTemplate  $customerordertemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerOrderTemplate $customerordertemplate)
    {
        $id = $customerordertemplate->id;

        $customerordertemplate->delete();

        return redirect('customerordertemplates')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                CustomerOrderTemplateLine::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }
}
