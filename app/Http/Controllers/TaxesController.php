<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Tax;
use View;

class TaxesController extends Controller {


   protected $tax;

   public function __construct(Tax $tax)
   {
        $this->tax = $tax;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // $taxes = $this->tax->all();
        $taxes = $this->tax->orderBy('id', 'asc')->get();

        return view('taxes.index', compact('taxes'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Tax::$rules);

        $tax = $this->tax->create($request->all());

        return redirect('taxes')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $tax->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tax = $this->tax->findOrFail($id);

        return view('taxes.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $tax = Tax::findOrFail($id);

        $this->validate($request, Tax::$rules);

        $tax->update($request->all());

        return redirect('taxes')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tax = $this->tax->findOrFail($id);

        try {

            $tax->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }

        return redirect('taxes')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}