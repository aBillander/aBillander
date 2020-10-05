<?php

namespace App\Http\Controllers\SalesRepCenter;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use App\Traits\ViewFormatterTrait;
use App\Traits\DateFormFormatterTrait;

use App\Configuration;
use Illuminate\Support\Facades\Auth;

use App\CommissionSettlement;
use App\CommissionSettlementLine;
use App\SalesRep;
use App\CustomerInvoice;

class AbsrcCommissionSettlementsController extends Controller
{
    use ViewFormatterTrait;
    use DateFormFormatterTrait;
   
   protected $stockcount;
   protected $stockcountline;

   public function __construct(CommissionSettlement $settlement, CommissionSettlementLine $settlementline)
   {
        $this->settlement = $settlement;
        $this->settlementline = $settlementline;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales_rep_id = Auth::user()->sales_rep_id;
        $salesrep = SalesRep::where('id', $sales_rep_id)->first();

        $settlements = $this->settlement
                        ->when($sales_rep_id > 0, function($query) use ($sales_rep_id) {

                                $query->where('sales_rep_id', $sales_rep_id);
                        })
                        ->orderBy('document_date', 'desc')
//                        ->orderBy('sales_rep_id', 'desc')
                        ->orderBy('date_from', 'desc')
                        ->get();

        return  view('absrc.commission_settlements.index', compact('settlements', 'salesrep'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salesrepList = SalesRep::pluck('alias', 'id')->toArray();

        return view('commission_settlements.create', compact('salesrepList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'date_from', 'date_to'], $request );

        $rules = $this->settlement::$rules;

        $this->validate($request, $rules);

        $extradata = [  'status'               => 'pending',
                        'onhold'               => 0,
                     ];

        $request->merge( $extradata );


        $sales_rep_id = $request->input('sales_rep_id');
        $date_from = $request->input('date_from', '');  // .' 00:00:00'
        $date_to   = $request->input('date_to', '');    // .' 23:59:59'
        $paid_documents_only   = $request->input('paid_documents_only', '0');
        
        // Lets see:
        $documents =  CustomerInvoice::
                      whereHas('salesrep', function ($query) use ($sales_rep_id) {

                            if ( (int) $sales_rep_id > 0 )
                                $query->where('id', $sales_rep_id);
                    })
                    ->when($date_from, function($query) use ($date_from) {

                            $query->where('document_date', '>=', $date_from.' 00:00:00');
                    })
                    ->when($date_to, function($query) use ($date_to) {

                            $query->where('document_date', '<=', $date_to.' 23:59:59');
                    })
                    ->where( function ($query) use ($paid_documents_only) {

                            if ( (int) $paid_documents_only > 0 )
                                $query->where('payment_status', 'paid');
                    })
                    ->whereDoesnthave('commissionsettlementline')
                    ->get();

 // abi_r($documents->count());die();

        if ( $documents->count() == 0 )
        {
            $sales_rep = SalesRep::find($sales_rep_id);

            return redirect()->route('commissionsettlements.index')
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . ' :: ' .  l('No records selected. ', 'layouts') . $request->input('date_from_form', '') . ' -> ' . $request->input('date_to_form', '') . ' :: ' . optional($sales_rep)->name);
        }


        // create settlement
        $settlement = $this->settlement->create($request->all());

//        abi_r( ( new \ReflectionClass($settlement) )->getName() );die();

        // Do add documents, now!
        $settlement->addDocuments( $documents );

        

        return redirect()->route('commissionsettlements.show', $settlement->id)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $settlement->id], 'layouts') . ' :: ' . $documents->count() . ' ' . l('document(s)'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommissionSettlement  $commissionSettlement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $settlement = $this->settlement
                        ->with('salesrep')
                        ->with('commissionsettlementlines')
                        ->with('commissionsettlementlines.commissionable')
                        ->with('commissionsettlementlines.commissionable.customer')
                        ->findOrFail($id);

        return view('absrc.commission_settlements.show', compact('settlement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommissionSettlement  $commissionSettlement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $settlement = $this->settlement->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'date_from', 'date_to'], $settlement );

        return view('commission_settlements.edit', compact('settlement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommissionSettlement  $commissionSettlement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'date_from', 'date_to'], $request );

        $settlement = $this->settlement->findOrFail($id);

        $rules = $this->settlement::$rules;

        $this->validate($request, $rules);

        $settlement->update($request->all());

        return redirect()->route('commissionsettlements.show', $id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $settlement->document_date);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommissionSettlement  $commissionSettlement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $settlement = $this->settlement->with('commissionsettlementlines')->findOrFail($id);

        // ToDo: Move to model boot method
        foreach ($settlement->commissionsettlementlines as $line) {
            # code...
            $line->delete();
        }

        $settlement->delete();

        return redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

    /**
     *
     *  More stuff
     *
     */


    public function addDocument(Request $request)
    {
        
        $settlement_id = $request->input('settlement_id');
        $document_id = $request->input('document_id');

        $settlement = $this->settlement->with('salesrep')->findOrFail($settlement_id);
        $sales_rep_id = $settlement->sales_rep_id;
        $sales_rep = $settlement->salesrep;

        if ( !$document_id )
        {
            
            return redirect()->route('commissionsettlements.show', $settlement->id)
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . ' :: ' .  l('No records selected. ', 'layouts') . ' :: ' . optional($sales_rep)->name);
        }
        
        // Lets see:
        $document =  CustomerInvoice::
                      whereHas('salesrep', function ($query) use ($sales_rep_id) {

                            if ( (int) $sales_rep_id > 0 )
                                $query->where('id', $sales_rep_id);
                    })
                    ->where( function ($query) use ($document_id) {

                            $query->where('id', $document_id)->orWhere('document_reference', $document_id);
                    })
                    ->whereDoesnthave('commissionsettlementline')
                    ->first();

 // abi_r($documents->count());die();

        if ( !$document )
        {

            return redirect()->route('commissionsettlements.show', $settlement->id)
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . ' :: ' .  l('No records selected. ', 'layouts') . ' :: ' . optional($sales_rep)->name . ' :: ' . $document_id);
        }

        // Do add documents, now!
        $settlement->addDocument( $document );

        // Update Settlement
        $settlement->updateTotal();
        

        return redirect()->route('commissionsettlements.show', $settlement->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $settlement->id], 'layouts') . ' :: ' . $document_id);
    }


    public function calculate($id)
    {
        $settlement = $this->settlement->findOrFail($id);

        // Recalculate document commissions
        // $msg = $settlement->calculateCommission();

        // Add new documents
        $sales_rep_id = $settlement->sales_rep_id;
        $date_from = optional($settlement->date_from)->startOfDay();
        $date_to   = optional($settlement->date_to)  ->endOfDay()  ;
        $paid_documents_only   = $settlement->paid_documents_only;
        
        // Lets see:
        $documents =  CustomerInvoice::
                      whereHas('salesrep', function ($query) use ($sales_rep_id) {

                            if ( (int) $sales_rep_id > 0 )
                                $query->where('id', $sales_rep_id);
                    })
                    ->when($date_from, function($query) use ($date_from) {

                            $query->where('document_date', '>=', $date_from.' 00:00:00');
                    })
                    ->when($date_to, function($query) use ($date_to) {

                            $query->where('document_date', '<=', $date_to.' 23:59:59');
                    })
                    ->where( function ($query) use ($paid_documents_only) {

                            if ( (int) $paid_documents_only > 0 )
                                $query->where('payment_status', 'paid');
                    })
                    ->whereDoesnthave('commissionsettlementline')
                    ->get();

 // abi_r($documents->count());die();

        if ( $documents->count() == 0 )
        {
            return redirect()->back()
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . ' :: ' .  l('No records selected. ', 'layouts') . $date_from . ' -> ' . $date_to );
        }

        // Do add documents, now!
        $settlement->addDocuments( $documents );

        

        return redirect()->route('commissionsettlements.show', $settlement->id)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $settlement->id], 'layouts') . ' :: ' . $documents->count() . ' ' . l('document(s)'));
    }
}
