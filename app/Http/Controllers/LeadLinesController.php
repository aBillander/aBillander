<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lead;
use App\LeadLine;
use App\User;

use Auth;

use App\Traits\DateFormFormatterTrait;

class LeadLinesController extends Controller
{
   use DateFormFormatterTrait;

   protected $lead;
   protected $leadline;

   public function __construct(Lead $lead, LeadLine $leadline)
   {
        $this->lead = $lead;
        $this->leadline = $leadline;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($leadId)
    {
        $lead = $this->lead
                        ->with('party')
                        ->with('leadlines')
                        ->with('leadlines.assignedto')
                        ->findOrFail($leadId);
        
        $leadlines = $lead->leadlines;

        return view('lead_lines.index', compact('lead', 'leadlines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadId)
    {
        $lead = $this->lead->findOrFail($leadId);

        $statusList = $this->leadline->getStatusList();

        $userList = User::getUserList();

        return view('lead_lines.create', compact('statusList', 'userList', 'lead'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($leadId, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['start_date', 'due_date', 'finish_date'], $request );

        $lead = $this->lead->findOrFail($leadId);

        $this->validate($request, LeadLine::$rules);

        $request->merge(['user_created_by_id' => Auth::id()]);

        // Handy conversions
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );


        $leadline = $this->leadline->create($request->all());

        $lead->leadlines()->save($leadline);

        return redirect('leads/'.$leadId.'/leadlines')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $leadline->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadLine  $leadLine
     * @return \Illuminate\Http\Response
     */
    public function show($leadId, $id)
    {
        return $this->edit($leadId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadLine  $leadLine
     * @return \Illuminate\Http\Response
     */
    public function edit($leadId, $id)
    {
        // $lead = $this->lead->findOrFail($leadId);
        // $leadline = $this->leadline->findOrFail($id);

        $leadline = $this->leadline
                                ->with('lead')
                                ->whereHas('lead', function($q) use ($leadId) {
                                    $q->where('id', $leadId);
                                })
                                ->findOrFail($id);

        $lead = $leadline->lead;

        $statusList = $this->leadline->getStatusList();

        $userList = User::getUserList();

        // Dates (cuen)
        $this->addFormDates( ['start_date', 'due_date', 'finish_date'], $leadline );

        return view('lead_lines.edit', compact('statusList', 'userList', 'lead', 'leadline'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadLine  $leadLine
     * @return \Illuminate\Http\Response
     */
    public function update($leadId, $id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['start_date', 'due_date', 'finish_date'], $request );

        $leadline = LeadLine::findOrFail($id);

        // Handy conversions
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );
        

        $this->validate($request, LeadLine::$rules);

        $leadline->update($request->all());

        return redirect('leads/'.$leadId.'/leadlines')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadLine  $leadLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadLine $leadLine)
    {
        $this->leadline->findOrFail($id)->delete();

        return redirect('leads/'.$leadId.'/leadlines')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
