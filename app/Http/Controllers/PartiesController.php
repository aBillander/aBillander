<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Party;
use App\Models\User;

use Auth;

class PartiesController extends Controller
{
   protected $party;

   public function __construct(Party $party)
   {
        $this->party = $party;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parties = $this->party->with('assignedto')->orderBy('name_commercial', 'asc')->get();

        return view('parties.index', compact('parties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $party_typeList = $this->party->getTypeList();

        $userList = User::getUserList();

        // abi_r(User::select('id', DB::raw("concat( firstname, ' ', lastname) as full_name"))->pluck('full_name', 'id'), true);

        // abi_r($party_typeList);die();

        return view('parties.create', compact('party_typeList', 'userList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Party::$rules);

        $request->merge(['user_created_by_id' => Auth::id()]);

        // abi_r($request->all());die();

        $party = $this->party->create($request->all());

        return redirect('parties')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $party->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $party_typeList = $this->party->getTypeList();

        $userList = User::getUserList();
        
        $party = $this->party->with('leads')->with('contacts')->findOrFail($id);

        $leads = $party->leads;
        $contacts = $party->contacts;

        return view('parties.edit', compact('party', 'party_typeList', 'userList', 'leads', 'contacts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $party = $this->party->findOrFail($id);

        $vrules = Party::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $party->id.',id';  // Unique

        $this->validate($request, $vrules);

        $party->update($request->all());

        return redirect('parties')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->party->findOrFail($id)->delete();

        return redirect('parties')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
