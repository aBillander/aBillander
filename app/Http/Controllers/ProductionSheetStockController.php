<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration;
use App\Models\ProductionSheet;
use App\Models\CustomerOrderLine;
use App\Models\CustomerShippingSlipLine;
use App\Models\Product;
use App\Models\LotItem;

class ProductionSheetStockController extends Controller
{
   protected $productionSheet;

   public function __construct(ProductionSheet $productionSheet)
   {
        $this->productionSheet = $productionSheet;
   }

    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function stockIndex($id, Request $request)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        // Let's get Products!
        // Customer Orders
        $lines = CustomerOrderLine::
                      whereHas('customerorder', function ($query) use ($id) {
                        $query->where('production_sheet_id', $id);
                        $query->where('status', 'confirmed');
                    })
                    ->whereHas('product', function ($query) {
                        $query->where('mrp_type', '<>', 'onorder');     // 'manual', 'reorder'
                        $query->where('lot_tracking', '>', 0);
                    })
                    ->with('customerorder')
                    ->with('lotitems')
                    ->get();
        
        // Customer Shipping Slips
        $slip_lines = CustomerShippingSlipLine::
                      whereHas('customershippingslip', function ($query) use ($id) {
                        $query->where('production_sheet_id', $id);
                        $query->where('status', 'confirmed');
                    })
                    ->whereHas('product', function ($query) {
                        $query->where('mrp_type', '<>', 'onorder');     // 'manual', 'reorder'
                        $query->where('lot_tracking', '>', 0);
                    })
                    ->with('customershippingslip')
                    ->with('lotitems')
                    ->get();

        
        // Group collection by product
        $lines      = $lines->groupBy('product_id');
        $slip_lines = $slip_lines->groupBy('product_id');


        // Main loop throu Products
        $keys = array_unique( array_merge( $lines->keys()->toArray(), $slip_lines->keys()->toArray() ) );
        $products = Product::whereIn( 'id', $keys )->with('availableLots')->get();

        // Add lines to products
        foreach ($products as $product) {
            // code...
            if ( $lines->get($product->id) && ($lines->get($product->id)->count() > 0) )
                $product->lines = $lines
                                    ->get($product->id)
                                    ->sortBy(function ($line, $key) {
                                        return $line->document->document_reference;
                                    });
            else
                $product->lines = collect([]);
            
            
            if ( $slip_lines->get($product->id) && ($slip_lines->get($product->id)->count() > 0) )
                $product->slip_lines = $slip_lines
                                    ->get($product->id)
                                    ->sortBy(function ($line, $key) {
                                        return $line->document->document_reference;
                                    });
            else
                $product->slip_lines = collect([]);
        }


        if (0)
        foreach ($lines as $key => $product_lines) {
            // code...
            $product = Product::with('availableLots')->find($key);
            if (!$product)
                continue;
        }

        return view('production_sheet_stock.index', compact('sheet', 'lines', 'slip_lines', 'products'));
    }
}
