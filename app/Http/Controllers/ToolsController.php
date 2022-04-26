<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tool;

class ToolsController extends Controller
{

   protected $tool;

   public function __construct(Tool $tool)
   {
        $this->tool = $tool;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tools = $this->tool->orderBy('name')->get();

        return view('tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tools.create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Tool::$rules);

        $tool = $this->tool->create($request->all());

        return redirect('tools')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $tool->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function show(Tool $tool)
    {
        return $this->edit($tool);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Tool $tool)
    {
        return view('tools.edit', compact('tool'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tool $tool)
    {
        $this->validate($request, Tool::$rules);
        
        $tool->update($request->all());

        return redirect('tools')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $tool->id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tool $tool)
    {
        $id = $tool->id;

        $tool->delete();

        return redirect('tools')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
