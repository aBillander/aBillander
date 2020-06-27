<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cheque;
use App\Currency;
use App\Bank;
use App\Configuration;

use App\Traits\DateFormFormatterTrait;

class ChequesController extends Controller
{
   
   use DateFormFormatterTrait;

   protected $cheque;

   public function __construct(Cheque $cheque)
   {
        $this->cheque = $cheque;
   }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue_from', 'date_of_issue_to', 'due_date_from', 'due_date_to'], $request );

        $cheques = $this->cheque
                        ->filter( $request->all() )
                        ->with('customer')
                        ->with('currency')
                        ->with('bank')
                        ->orderBy('due_date', 'desc');

        $cheques = $cheques->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $cheques->setPath('cheques');

        // $c = new Cheque();
        // $c->id = 22;

        // $cheques = collect( [$c] );

        // abi_r($c);die();

        $statusList = $this->cheque::getStatusList();

        return view('cheques.index', compact('cheques', 'statusList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statusList = $this->cheque::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();

        return view('cheques.create', compact('statusList', 'currencyList', 'bankList'));
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
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $rules = $this->cheque::$rules;

        $this->validate($request, $rules);

        $cheque = Cheque::create($request->all());

        return redirect()->route('cheques.index')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $cheque->document_number], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function show(Cheque $cheque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function edit(Cheque $cheque)
    {
        $statusList = $this->cheque::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();        

        // abi_r($bankList);die();

        // Dates (cuen)
        $this->addFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $cheque );

        return view('cheques.edit', compact('cheque', 'statusList', 'currencyList', 'bankList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cheque $cheque)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $rules = $this->cheque::$rules;

        $this->validate($request, $rules);

        $cheque->update($request->all());

        return redirect()->route('cheques.index')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $cheque->document_number], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cheque $cheque)
    {
        //
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
