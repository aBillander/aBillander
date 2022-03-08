<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\CustomerOrderTemplate;
use App\Models\CustomerOrderTemplateLine;

class CustomerOrderTemplateLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customerordertemplateId)
    {
        $customerordertemplate = CustomerOrderTemplate::with('customerordertemplatelines')->findOrFail($customerordertemplateId);
        $customerordertemplatelines = $customerordertemplate->customerordertemplatelines;

        return view('customer_order_template_lines.index', compact('customerordertemplate', 'customerordertemplatelines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customerordertemplateId)
    {
        $customerordertemplate = CustomerOrderTemplate::
                                      with('customerordertemplatelines')
                                    ->with('customer')
                                    ->findOrFail($customerordertemplateId);
        
        return view('customer_order_template_lines.create', compact('customerordertemplate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($customerordertemplateId, Request $request)
    {
        $customerordertemplate = CustomerOrderTemplate::with('customerordertemplatelines')->findOrFail($customerordertemplateId);

        $this->validate($request, CustomerOrderTemplateLine::$rules);

        // Handy conversions
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $customerordertemplate->customerordertemplatelines->max('line_sort_order') + 10  ] );


        $customerordertemplateline = CustomerOrderTemplateLine::create($request->all());

        $customerordertemplate->customerordertemplatelines()->save($customerordertemplateline);

        $customerordertemplate->update([
                'total_tax_incl' => 0.0,
                'total_tax_excl' => 0.0,
        ]);

        return redirect('customerordertemplates/'.$customerordertemplateId.'/customerordertemplatelines')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customerordertemplateline->id], 'layouts') . $request->input('line_sort_order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerOrderTemplateLine  $customerOrderTemplateLine
     * @return \Illuminate\Http\Response
     */
    public function show($customerordertemplateId, CustomerOrderTemplateLine $customerordertemplateline)
    {
        return $this->edit($customerordertemplateId, $customerordertemplateline);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerOrderTemplateLine  $customerOrderTemplateLine
     * @return \Illuminate\Http\Response
     */
    public function edit($customerordertemplateId, CustomerOrderTemplateLine $customerordertemplateline)
    {
        $customerordertemplate = CustomerOrderTemplate::with('customerordertemplatelines')->findOrFail($customerordertemplateId);

        $customerordertemplateline->load(['product']);

        return view('customer_order_template_lines.edit', compact('customerordertemplate', 'customerordertemplateline'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerOrderTemplateLine  $customerOrderTemplateLine
     * @return \Illuminate\Http\Response
     */
    public function update($customerordertemplateId, Request $request, CustomerOrderTemplateLine $customerordertemplateline)
    {
        $customerordertemplate = CustomerOrderTemplate::with('customerordertemplatelines')->findOrFail($customerordertemplateId);

        // Handy conversions
        $request->merge( ['customer_id'  => $customerordertemplateline->customer_id] );
        $request->merge( ['address_id'   => $customerordertemplateline->address_id ] );
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $customerordertemplate->customerordertemplatelines->max('line_sort_order') + 10  ] );
        

        $this->validate($request, CustomerOrderTemplateLine::$rules);

        $customerordertemplateline->update($request->all());

        $customerordertemplate->update([
                'total_tax_incl' => 0.0,
                'total_tax_excl' => 0.0,
        ]);

        return redirect('customerordertemplates/'.$customerordertemplateId.'/customerordertemplatelines')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customerordertemplateline->id], 'layouts') . $request->input('line_sort_order'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerOrderTemplateLine  $customerOrderTemplateLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerordertemplateId, CustomerOrderTemplateLine $customerordertemplateline)
    {
        $customerordertemplate = CustomerOrderTemplate::findOrFail($customerordertemplateId);

        $id = $customerordertemplateline->id;

        $customerordertemplateline->delete();

        $customerordertemplate->update([
                'total_tax_incl' => 0.0,
                'total_tax_excl' => 0.0,
        ]);

        return redirect('customerordertemplates/'.$customerordertemplateId.'/customerordertemplatelines')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
