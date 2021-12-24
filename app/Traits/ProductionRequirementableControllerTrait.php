<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\Product;
use App\ProductionRequirement;

trait ProductionRequirementableControllerTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\ProductionSheet  $productionSheet
     * @return \Illuminate\Http\Response
     */
    public function getProductionRequirements($id)
    {
        $sheet = $this->productionSheet
                        ->with('productionrequirements')
                        ->with('productionrequirements.product')
                        ->findOrFail($id);

        // $sheet->due_date = abi_date_form_short($sheet->due_date);
        
        return view('production_sheets._panel_production_requirements_content', compact('sheet'));
    }




    public function quickAddProductionRequirements(Request $request, $document_id)
    {
        parse_str($request->input('product_id_values'), $output);
        $product_id_values = $output['product_id_values'];

        parse_str($request->input('combination_id_values'), $output);
        $combination_id_values = $output['combination_id_values'];

        parse_str($request->input('quantity_values'), $output);
        $quantity_values = $output['quantity_values'];

        // Create id's array and id => quantity array
        $ids = [];
        $qty = [];
        foreach ($product_id_values as $key => $value) {
            // code...
            $ids[] = $value;
            $qty[$value] = $quantity_values[$key];
        }


        // abi_r($output);die();


        // Let's Rock!
        $sheet = $this->productionSheet
                        ->with('productionrequirements')
                        ->with('productionrequirements.product')
                        ->findOrFail($document_id);

        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            # code...

            // Existing Production Requirement will be superseded
            if ( $req = $sheet->productionrequirements->where('product_id', $product->id)->first() )
                $req->delete();

            $data = [
                    'product_id' => $product->id,
//                    'combination_id' => ,
                    'reference' => $product->reference,
                    'name' => $product->name,

                    'product_bom_id' => $product->bom->id,
                    'measure_unit_id' => $product->measure_unit_id,

                    // Quantity expressed in number of Manufacturing Batches
                    'required_quantity' => $qty[$product->id] * $product->manufacturing_batch_size,
                    'manufacturing_batch_size' => $product->manufacturing_batch_size,

                    'notes' => '',

//                    'warehouse_id' => ,
//                    'work_center_id' => ,

                    'production_sheet_id' => $sheet->id,
            ];

            $requirement = ProductionRequirement::create( $data );

            $line[] = $requirement->id;
        }

        return response()->json( [
                'msg' => 'OK',
                'document' => $document_id,
                'data' => $line,
        ] );

    }


    public function deleteProductionRequirement($line_id)
    {

        $line = ProductionRequirement::findOrFail($line_id);


        $line->delete();

        return response()->json( [
                'msg' => 'OK',
                'data' => $line_id,
        ] );
    }

}