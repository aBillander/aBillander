<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cheque;
use App\ChequeDetail;
use App\CustomerInvoice;

class ChequeDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($chequeId)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($chequeId)
    {
        $cheque = Cheque::
                      with('chequedetails')
                    ->with('customer')
                    ->with('currency')
                    ->findOrFail($chequeId);
        
        return view('cheque_details.create', compact('cheque'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($chequeId, Request $request)
    {
        $cheque = Cheque::with('chequedetails')->findOrFail($chequeId);

        $customer_id = $cheque->customer_id;
/*
        $extra_rules = [
            'customer_invoice_id' => [                                                                  
                'nullable',                                                            
                \Illuminate\Validation\Rule::exists('customer_invoices', 'id')                     
                    ->where(function ($query) use ($customer_id) {                      
                        $query->where('customer_id', $customer_id);                  
                }),                                                                    
            ],
        ];
*/
        $this->validate($request, ChequeDetail::$rules);        //  + $extra_rules);

        // Handy conversions
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $cheque->chequedetails->max('line_sort_order') + 10  ] );


        $chequedetail = ChequeDetail::create($request->all());

        $cheque->chequedetails()->save($chequedetail);

        // $cheque->update([
        //         'total_tax_incl' => 0.0,
        //         'total_tax_excl' => 0.0,
        // ]);

        return redirect('cheques/'.$chequeId.'/edit')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $chequedetail->id], 'layouts') . $chequedetail->line_sort_order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function show($chequeId, ChequeDetail $chequeDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($chequeId, ChequeDetail $chequedetail)
    {
        $cheque = Cheque::with('chequedetails')->findOrFail($chequeId);

        
        return view('cheque_details.edit', compact('cheque', 'chequedetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function update($chequeId, Request $request, ChequeDetail $chequedetail)
    {
        $cheque = Cheque::with('chequedetails')->findOrFail($chequeId);

        $customer_id = $cheque->customer_id;

        $this->validate($request, ChequeDetail::$rules);

        // Handy conversions
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $cheque->chequedetails->max('line_sort_order') + 10  ] );


        $chequedetail->update($request->all());

        // $cheque->update([
        //         'total_tax_incl' => 0.0,
        //         'total_tax_excl' => 0.0,
        // ]);

        return redirect('cheques/'.$chequeId.'/edit')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $chequedetail->id], 'layouts') . $chequedetail->line_sort_order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($chequeId, ChequeDetail $chequedetail)
    {
        $id = $chequedetail->id;

        $chequedetail->delete();

        return redirect('cheques/'.$chequeId.'/edit')
                ->with('info', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') . $chequedetail->line_sort_order);
    }



    /*
    |--------------------------------------------------------------------------
    | Ajax Stuff
    |--------------------------------------------------------------------------
    */

    public function searchInvoice($id, Request $request)
    {
        $search = $request->term;

        $invoices = CustomerInvoice::select('id', 'document_reference', 'total_tax_incl')
                                ->where(function($query) use ($search)
                                {
                                    $query->where  ( 'id',                 'LIKE', '%'.$search.'%' );
                                    $query->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' );
                                })
 //                               ->where('customer_id', $request->input('customer_id'))
                                ->where('currency_id', $request->input('currency_id'))
                                ->where('status', 'closed')
                                ->where('total_tax_incl', '>', 0.0)
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $invoices );
    }
}
