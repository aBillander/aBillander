<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;
use App\ProductionSheet;
use App\DeliveryRoute;

use Excel;

class ProductionSheetsDeliveryRoutesController extends Controller
{
   protected $productionSheet;
   protected $deliveryRoute;

   public function __construct(ProductionSheet $productionSheet, DeliveryRoute $deliveryRoute)
   {
        $this->productionSheet = $productionSheet;
        $this->deliveryRoute   = $deliveryRoute;
   }

    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export($id, $route_id, Request $request)
    {
        $documents_relation = 'customershippingslips';
        if ($request->has('customerorders')) $documents_relation = 'customerorders';

        $sheet = $this->productionSheet
                        ->with($documents_relation)
                        ->findOrFail($id);

        $route = $this->deliveryRoute
                        ->with('deliveryroutelines')
                        ->findOrFail($route_id);

        $route_spots = $route->deliveryroutelines;

        $route_documents = $sheet->{$documents_relation}->where('carrier_id', $route->carrier_id);

        foreach ($route_documents as $document) {
            # Let's see position in route
            if ( $spot = $route_spots->where('address_id', $document->shipping_address_id)->first() )
            {
                $document->delivery_sort_order = $spot->line_sort_order;
                $document->spot = $spot;    // Just convenient
            }
            else
            {
                $spot = new \stdClass();
                $spot->line_sort_order = 0;
                $spot->customer = $document->customer;
                $spot->address  = $document->shippingaddress;

                $document->delivery_sort_order = $spot->line_sort_order;     // Not found in route, show first
                $document->spot = $spot;
            }
        }

        // Sort documents        
        $route_documents = $route_documents->sortBy(function ($document, $key) {
            return $document->delivery_sort_order;
        });
        

        $pdf = \PDF::loadView('production_sheet_delivery_routes.reports.delivery_sheet.delivery_sheet', compact('sheet', 'route', 'route_documents'))->setPaper('a4', 'vertical');

        if ($request->has('screen')) return view('production_sheet_delivery_routes.reports.delivery_sheet.delivery_sheet', compact('sheet', 'route', 'route_documents'));

        return $pdf->stream('delivery_sheet_'.$route->due_date.'.pdf');
    }
}
