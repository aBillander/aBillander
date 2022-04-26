<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BanksController extends Controller
{

   protected $bank;

   public function __construct(Bank $bank)
   {
        $this->bank = $bank;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $banks = $this->bank->orderBy('id', 'asc')->get();

        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Bank::$rules);

        $bank = $this->bank->create($request->all());

        return redirect('banks')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $bank->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {

        $this->validate($request, Bank::$rules);

        $bank->update($request->all());

        return redirect('banks')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $bank->id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $name = $bank->name;

        // To Do: check if this bank can be deleted
        $bank->delete();

        return redirect('banks')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $name], 'layouts'));
    }
}
