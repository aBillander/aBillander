<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DeliveryRoute;
use App\DeliveryRouteLine;

use App\Carrier;

class DeliveryRoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryroutes = DeliveryRoute::orderBy('id', 'asc')->get();

        return view('delivery_routes.index', compact('deliveryroutes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $carrierList = Carrier::pluck('name', 'id')->toArray();

        return view('delivery_routes.create', compact('carrierList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, DeliveryRoute::$rules);

        $deliveryroute = DeliveryRoute::create($request->all());

        return redirect('deliveryroutes')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $deliveryroute->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DeliveryRoute  $deliveryroute
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryRoute $deliveryroute)
    {
        return $this->edit($deliveryroute);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveryRoute  $deliveryroute
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryRoute $deliveryroute)
    {
        $carrierList = Carrier::pluck('name', 'id')->toArray();

        return view('delivery_routes.edit', compact('deliveryroute', 'carrierList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveryRoute  $deliveryroute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryRoute $deliveryroute)
    {
        $this->validate($request, DeliveryRoute::$rules);

        $deliveryroute->update($request->all());

        return redirect('deliveryroutes')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $deliveryroute->id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveryRoute  $deliveryroute
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryRoute $deliveryroute)
    {
        $id = $deliveryroute->id;

        $deliveryroute->delete();

        return redirect('deliveryroutes')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
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
                DeliveryRouteLine::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }




    public function showPdf(DeliveryRoute $deliveryroute, Request $request)
    {
        $deliveryroute->load(['deliveryroutelines', 'carrier']);
        $deliveryroutelines = $deliveryroute->deliveryroutelines;

        // return view('delivery_route_lines.show_pdf', compact('deliveryroute', 'deliveryroutelines'));



        $pdf = \PDF::loadView('delivery_routes.reports.delivery_route.delivery_route', compact('deliveryroute', 'deliveryroutelines'))->setPaper('a4', 'vertical');

        if ($request->has('screen')) return view('delivery_routes.reports.delivery_route.delivery_route', compact('deliveryroute', 'deliveryroutelines'));

        return $pdf->stream('delivery_route_'.$deliveryroute->alias.'.pdf'); // $pdf->download('invoice.pdf');





        die($id);

        $customer      = Auth::user()->customer;

        $document = $this->customerShippingSlip->where('id', $id)->where('customer_id', $customer->id)->first();

        if (!$document) 
            return redirect()->route('abcc.shippingslips.index')
                    ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts').$customer->id);
        

        $company = \App\Context::getContext()->company;

        // Get Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE') );

        if ( !$t )
            return redirect()->route('abcc.shippingslips.index', $id)
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

        $template = $t->getPath( 'CustomerShippingSlip' );


//        $template = 'customer_invoices.templates.' . $cinvoice->template->file_name;  // . '_dist';
        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.

        // PDF stuff
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

                return redirect()->route('abcc.invoices.index')
                    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').$e->getMessage());
        }

        // PDF stuff ENDS

        $pdfName    = 'Shippingslip_' . $document->secure_key . '_' . $document->document_date;

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');
    }
}
