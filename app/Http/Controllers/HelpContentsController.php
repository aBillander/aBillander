<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\HelpContent;

class HelpContentsController extends Controller
{

   protected $help_content;

   public function __construct(HelpContent $help_content)
   {
        $this->help_content = $help_content;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = $this->help_content->orderBy('slug', 'asc')->get();

        return view('help_contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get language from context, and move on
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HelpContent  $helpContent
     * @return \Illuminate\Http\Response
     */
    public function show(HelpContent $helpContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HelpContent  $helpContent
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpContent $helpContent)
    {
        // Do not allow to change Language
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HelpContent  $helpContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HelpContent $helpContent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HelpContent  $helpContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(HelpContent $helpContent)
    {
        //
    }



/* ********************************************************************************************* */    



    /**
     * AJAX Stuff.
     *
     * 
     */

    public function getContent($slug, Request $request)
    {
        // En realidad esto esto es show, pero con ajax request y response, pero en lugar de por id, por slug. 

        $product = $this->editQueryRaw()
//                        ->isManufactured()
                        ->findOrFail($id);
        
        return view('products._panel_stock_summary', compact('product'));
    }
}
