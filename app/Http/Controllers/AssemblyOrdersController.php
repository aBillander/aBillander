<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AssemblyOrder;

class AssemblyOrdersController extends Controller
{

   protected $assemblyorder;

   public function __construct(AssemblyOrder $assemblyorder)
   {
        $this->assemblyorder = $assemblyorder;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->assemblyorder
                      ->filter( $request->all() )
                      ->with('workcenter')
                      ->orderBy('due_date', 'DESC')
                      ->orderBy('id', 'DESC');

// See: https://laracasts.com/discuss/channels/eloquent/eager-loading-cant-orderby
// See: https://stackoverflow.com/questions/18861186/eloquent-eager-load-order-by
                      

        $orders = $orders->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $orders->setPath('assemblyorders');     // Customize the URI used by the paginator

        return view('assembly_orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\AssemblyOrder  $assemblyorder
     * @return \Illuminate\Http\Response
     */
    public function show(AssemblyOrder $assemblyorder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssemblyOrder  $assemblyorder
     * @return \Illuminate\Http\Response
     */
    public function edit(AssemblyOrder $assemblyorder)
    {
        return redirect()->route('assemblyorders.index', ['id' => $assemblyorder->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssemblyOrder  $assemblyorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssemblyOrder $assemblyorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssemblyOrder  $assemblyorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssemblyOrder $assemblyorder)
    {
        //
    }
}
