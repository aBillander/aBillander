<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Party;
use App\Contact;
use App\User;

use Auth;

class ContactsController extends Controller
{

   protected $contact;

   public function __construct(Contact $contact)
   {
        $this->contact = $contact;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = $this->contact->with('party')->orderBy('id', 'asc')->get();

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexByParty($party_id)
    {
        // $contacts = $this->contact->with('assignedto')->orderBy('id', 'asc')->get();
        $party = Party::with('contacts')->findOrFail($party_id);

        $contacts = $party->contacts;

        return view('contacts.index_by_party', compact('contacts', 'party'));
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
        if ($party_id > 0)
        {
            $party = Party::findOrFail($party_id);
            // $partyList = $party->pluck('name_commercial', 'id')->toArray();
            $partyList = [$party->id => $party->name_commercial];
        } else {
            $party = null;
            $partyList = Party::orderBy('name_commercial', 'asc')->pluck('name_commercial', 'id')->toArray();
        }        

        return view('contacts.create', compact('partyList', 'party'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Contact::$rules);

        $request->merge(['user_created_by_id' => Auth::id()]);

        // abi_r($request->all());die();

        $contact = $this->contact->create($request->all());

        $url = $request->input('caller_url', 'contacts') ?: 'contacts';

        return redirect($url)
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $contact->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $contact = $this->contact->findOrFail($id);

        $party_id = $contact->party_id;

        // abi_r($party_id); // die();

        if ($party_id > 0)
        {
            $party = Party::findOrFail($party_id);
            // abi_r($party->pluck('name_commercial', 'id'));
            $partyList = [$party->id => $party->name_commercial];       //  $party->pluck('name_commercial', 'id')->toArray();
        } else {
            $party = null;
            $partyList = Party::orderBy('name_commercial', 'asc')->pluck('name_commercial', 'id')->toArray();
        }

        // abi_r($partyList);die();

        return view('contacts.edit', compact('contact', 'partyList', 'party'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $contact = $this->contact->findOrFail($id);

        $vrules = Contact::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $contact->id.',id';  // Unique

        $this->validate($request, $vrules);

        $contact->update($request->all());

        $url = $request->input('caller_url', 'contacts') ?: 'contacts';

        return redirect($url)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->contact->findOrFail($id)->delete();

        return redirect('contacts')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
