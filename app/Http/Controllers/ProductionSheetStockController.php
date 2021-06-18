<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;
use App\ProductionSheet;
use App\CustomerOrderLine;
use App\Product;
use App\LotItem;

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
        $lines = CustomerOrderLine::
                      whereHas('customerorder', function ($query) use ($id) {
                        $query->where('production_sheet_id', $id);
                    })
                    ->whereHas('product', function ($query) {
                        $query->where('mrp_type', '<>', 'onorder');     // 'manual', 'reorder'
                        $query->where('lot_tracking', '>', 0);
                    })
                    ->with('customerorder')
                    ->with('lotitems')
                    ->get();
        
        // Group collection by product
        $lines = $lines->groupBy('product_id');


        // Main loop throu Products
        $products = Product::whereIn( 'id', $lines->keys() )->with('availableLots')->get();

        // Add lines to products
        foreach ($products as $product) {
            // code...
            $product->lines = $lines
                                    ->get($product->id)
                                    ->sortBy(function ($line, $key) {
                                        return $line->document->document_reference;
                                    });
        }


        if (0)
        foreach ($lines as $key => $product_lines) {
            // code...
            $product = Product::with('availableLots')->find($key);
            if (!$product)
                continue;
        }

        return view('production_sheet_stock.index', compact('sheet', 'lines', 'products'));
    }
}
