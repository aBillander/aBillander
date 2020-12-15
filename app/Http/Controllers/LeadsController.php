<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Party;
use App\Lead;
use App\User;

use Auth;

use App\Traits\DateFormFormatterTrait;

class LeadsController extends Controller
{
   use DateFormFormatterTrait;


   protected $lead;

   public function __construct(Lead $lead)
   {
        $this->lead = $lead;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = $this->lead->with('party')->with('assignedto')->orderBy('id', 'asc')->get();

        return view('leads.index', compact('leads'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexByParty($party_id)
    {
        // $leads = $this->lead->with('assignedto')->orderBy('id', 'asc')->get();
        $party = Party::with('leads')->with('leads.assignedto')->findOrFail($party_id);

        $leads = $party->leads;

        return view('leads.index_by_party', compact('leads', 'party'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->createWithParty();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithParty($party_id = 0)
    {
        $statusList = $this->lead->getStatusList();

        $userList = User::getUserList();

        if ($party_id > 0)
        {
            $party = Party::findOrFail($party_id);
            $leadList = $party->pluck('name_commercial', 'id')->toArray();
        } else {
            $party = null;
            $leadList = Party::orderBy('name_commercial', 'asc')->pluck('name_commercial', 'id')->toArray();
        }        

        return view('leads.create', compact('statusList', 'userList', 'leadList', 'party'));
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
        $this->mergeFormDates( ['lead_date', 'lead_end_date'], $request );

        $this->validate($request, Lead::$rules);

        $request->merge(['user_created_by_id' => Auth::id()]);

        // abi_r($request->all());die();

        $lead = $this->lead->create($request->all());

        return redirect('leads')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $lead->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $statusList = $this->lead->getStatusList();

        $userList = User::getUserList();

        $leadList = Party::where('id', $id)->pluck('name_commercial', 'id')->toArray();
        
        $lead = $this->lead->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['lead_date', 'lead_end_date'], $lead );

        return view('leads.edit', compact('lead', 'statusList', 'userList', 'leadList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['lead_date', 'lead_end_date'], $request );

        $lead = $this->lead->findOrFail($id);

        $this->validate($request, Lead::$rules);

        $lead->update($request->all());

        return redirect('leads')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->lead->findOrFail($id)->delete();

        return redirect('leads')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
