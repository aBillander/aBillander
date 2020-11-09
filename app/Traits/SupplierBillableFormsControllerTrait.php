<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\Configuration;
use App\Product;
use App\ShippingMethod;

trait SupplierBillableFormsControllerTrait
{

    public function SupplierFormForProduct( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view($this->view_path.'._form_for_product_edit');
                break;
            
            case 'create':
                # code...
                return view($this->view_path.'._form_for_product_create');
                break;
            
            default:
                # code...
                // Form for action not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $action
                    ] );
                break;
        }
        
    }

    public function SupplierFormForService( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view($this->view_path.'._form_for_service_edit');
                break;
            
            case 'create':
                # code...
                return view($this->view_path.'._form_for_service_create');
                break;
            
            default:
                # code...
                // Form for action not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $action
                    ] );
                break;
        }
        
    }

    public function SupplierFormForComment( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view($this->view_path.'._form_for_comment_edit');
                break;
            
            case 'create':
                # code...
                return view($this->view_path.'._form_for_comment_create');
                break;
            
            default:
                # code...
                // Form for action not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $action
                    ] );
                break;
        }
        
    }

    public function storeSupplierDocumentLine(Request $request, $document_id)
    {
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->storeSupplierDocumentLineProduct($request, $document_id);
                break;
            
            case 'service':
                # code...
                return $this->storeSupplierDocumentLineService($request, $document_id);
                break;
            
            case 'comment':
                # code...
                return $this->storeSupplierDocumentLineComment($request, $document_id);
                break;
            
            default:
                # code...
                // Document Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function storeSupplierDocumentLineProduct(Request $request, $document_id)
    {
        // return response()->json(['order_id' => $document_id] + $request->all());

        $document = $this->document
                        ->with('supplier')
                        ->with('taxingaddress')
                        ->with('currency')
                        ->find($document_id);

        if ( !$document )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );


        $product_id     = $request->input('product_id');
        $combination_id = $request->input('combination_id', null);

        if ( !$document || !Product::where('id', $product_id)->exists() )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );
        
        $quantity       = $request->input('quantity', 1.0);

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->supplier->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'prices_entered_with_tax' => $pricetaxPolicy,
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_supplier_final_price' => $request->input('unit_supplier_final_price'),

            'line_sort_order' => $request->input('line_sort_order'),
            'notes' => $request->input('notes', ''),

            'store_mode' => $request->input('store_mode', ''),
        ];

        // More stuff
        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');


        // Let's Rock!

        $store_mode = $request->input('store_mode', '');

        if ( $store_mode == 'asis' )
            // Force product price from imput
            $document_line = $document->addSupplierProductAsIsLine( $product_id, $combination_id, $quantity, $params );
        else
            // Calculate product price according to Customer Price List and Price Rules
            $document_line = $document->addSupplierProductLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function storeSupplierDocumentLineService(Request $request, $document_id)
    {
        $document = $this->document
                        ->with('supplier')
                        ->with('taxingaddress')
                        ->with('salesrep')
                        ->with('currency')
                        ->find($document_id);

        if ( !$document )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );


        $product_id     = $request->input('product_id', null);
        $combination_id = $request->input('combination_id', null);
        $quantity       = $request->input('quantity', 1.0);

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->supplier->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'line_type' => $request->input('is_shipping', 0) ? 'shipping' : $request->input('line_type', 'service'),
            'prices_entered_with_tax' => $pricetaxPolicy,
            'cost_price' => $request->input('cost_price', 0.0),
            'unit_price' => $request->input('unit_price', 0.0),
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_supplier_final_price' => $request->input('unit_supplier_final_price'),
            'tax_id' => $request->input('tax_id', \App\Configuration::get('DEF_TAX')),

            'line_sort_order' => $request->input('line_sort_order'),
            'notes' => $request->input('notes', ''),
        ];

        // More stuff
        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        if ($request->has('sales_rep_id')) 
            $params['sales_rep_id'] = $request->input('sales_rep_id');

        if ($request->has('commission_percent')) 
            $params['commission_percent'] = $request->input('commission_percent');


        // Let's Rock!

        $document_line = $document->addSupplierServiceLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function storeSupplierDocumentLineComment(Request $request, $document_id)
    {
        $document = $this->document
                        ->with('supplier')
                        ->with('taxingaddress')
                        ->with('salesrep')
                        ->with('currency')
                        ->find($document_id);

        if ( !$document )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );


        $product_id     = $request->input('product_id', null);
        $combination_id = $request->input('combination_id', null);
        $quantity       = $request->input('quantity', 1.0);

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->supplier->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'line_type' => 'comment',
            'prices_entered_with_tax' => $pricetaxPolicy,
            'cost_price' => $request->input('cost_price', 0.0),
            'unit_price' => $request->input('unit_price', 0.0),
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_supplier_final_price' => $request->input('unit_supplier_final_price'),
            'tax_id' => $request->input('tax_id', \App\Configuration::get('DEF_TAX')),

            'line_sort_order' => $request->input('line_sort_order'),
            'notes' => $request->input('notes', ''),
        ];

        // More stuff
        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        if ($request->has('sales_rep_id')) 
            $params['sales_rep_id'] = $request->input('sales_rep_id');

        if ($request->has('commission_percent')) 
            $params['commission_percent'] = $request->input('commission_percent');


        // Let's Rock!

        $document_line = $document->addSupplierCommentLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateSupplierDocumentLine(Request $request, $line_id)
    {
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->updateSupplierDocumentLineProduct($request, $line_id);
                break;
            
            case 'service':
            case 'shipping':
                # code...
                return $this->updateSupplierDocumentLineService($request, $line_id);
                break;
            
            case 'comment':
                # code...
                return $this->updateSupplierDocumentLineComment($request, $line_id);
                break;
            
            default:
                # code...
                // SupplierDocument Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function updateSupplierDocumentLineProduct(Request $request, $line_id)
    {

        $params = [
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_supplier_final_price' => $request->input('unit_supplier_final_price'),

//            'line_sort_order' => $request->input('line_sort_order'),
//            'notes' => $request->input('notes'),
        ];

        // More stuff
        if ($request->has('quantity')) 
            $params['quantity'] = $request->input('quantity');

        if ($request->has('prices_entered_with_tax')) 
            $params['prices_entered_with_tax'] = $request->input('prices_entered_with_tax');

        if ($request->has('discount_percent')) 
            $params['discount_percent'] = $request->input('discount_percent');

        if ($request->has('unit_supplier_final_price')) 
            $params['unit_supplier_final_price'] = $request->input('unit_supplier_final_price');

        if ($request->has('line_sort_order')) 
            $params['line_sort_order'] = $request->input('line_sort_order');

        if ($request->has('notes')) 
            $params['notes'] = $request->input('notes');


        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');


        // Let's Rock!
        $document_line = $this->document_line
                        ->with( 'document' )
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $line_id,
            ] );

        
        $document = $document_line->document;
//        $document = $this->document->where('id', $this->model_snake_case.'_id')->first();

        $document_line = $document->updateSupplierProductLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateSupplierDocumentLineService(Request $request, $line_id)
    {

        $params = [
//            'line_type' => $request->input('is_shipping', 0) ? 'shipping' : $request->input('line_type', 'service'),
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_supplier_final_price' => $request->input('unit_supplier_final_price'),

//            'line_sort_order' => $request->input('line_sort_order'),
//            'notes' => $request->input('notes'),
        ];

        // More stuff
        if ($request->has('is_shipping')) 
            $params['line_type'] = $request->input('is_shipping') ? 'shipping' : 'service';
        
        if ($request->has('quantity')) 
            $params['quantity'] = $request->input('quantity');

        if ($request->has('tax_id')) 
            $params['tax_id'] = $request->input('tax_id');

        if ($request->has('prices_entered_with_tax')) 
            $params['prices_entered_with_tax'] = $request->input('prices_entered_with_tax');

        if ($request->has('discount_percent')) 
            $params['discount_percent'] = $request->input('discount_percent');

        if ($request->has('cost_price')) 
            $params['cost_price'] = $request->input('cost_price');

        if ($request->has('unit_price')) 
            $params['unit_price'] = $request->input('unit_price');

        if ($request->has('unit_supplier_final_price')) 
            $params['unit_supplier_final_price'] = $request->input('unit_supplier_final_price');

        if ($request->has('line_sort_order')) 
            $params['line_sort_order'] = $request->input('line_sort_order');

        if ($request->has('notes')) 
            $params['notes'] = $request->input('notes');


        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        if ($request->has('sales_rep_id')) 
            $params['sales_rep_id'] = $request->input('sales_rep_id');

        if ($request->has('commission_percent')) 
            $params['commission_percent'] = $request->input('commission_percent');


        // Let's Rock!
        $document_line = $this->document_line
                        ->with( 'document' )
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $line_id,
            ] );

        
        $document = $document_line->document;
//        $document = $this->document->where('id', $this->model_snake_case.'_id')->first();

        $document_line = $document->updateSupplierServiceLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateSupplierDocumentLineComment(Request $request, $line_id)
    {

        $params = [
//            'line_type' => $request->input('is_shipping', 0) ? 'shipping' : $request->input('line_type', 'service'),
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_supplier_final_price' => $request->input('unit_supplier_final_price'),

//            'line_sort_order' => $request->input('line_sort_order'),
//            'notes' => $request->input('notes'),
        ];

        // More stuff
//        if ($request->has('is_shipping')) 
//            $params['line_type'] = $request->input('is_shipping') ? 'shipping' : 'service';
        
        if ($request->has('quantity')) 
            $params['quantity'] = $request->input('quantity');

        if ($request->has('tax_id')) 
            $params['tax_id'] = $request->input('tax_id');

        if ($request->has('prices_entered_with_tax')) 
            $params['prices_entered_with_tax'] = $request->input('prices_entered_with_tax');

        if ($request->has('discount_percent')) 
            $params['discount_percent'] = $request->input('discount_percent');

        if ($request->has('cost_price')) 
            $params['cost_price'] = $request->input('cost_price');

        if ($request->has('unit_price')) 
            $params['unit_price'] = $request->input('unit_price');

        if ($request->has('unit_supplier_final_price')) 
            $params['unit_supplier_final_price'] = $request->input('unit_supplier_final_price');

        if ($request->has('line_sort_order')) 
            $params['line_sort_order'] = $request->input('line_sort_order');

        if ($request->has('notes')) 
            $params['notes'] = $request->input('notes');


        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        if ($request->has('sales_rep_id')) 
            $params['sales_rep_id'] = $request->input('sales_rep_id');

        if ($request->has('commission_percent')) 
            $params['commission_percent'] = $request->input('commission_percent');


        // Let's Rock!
        $document_line = $this->document_line
                        ->with( 'document' )
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $line_id,
            ] );

        
        $document = $document_line->document;
//        $document = $this->document->where('id', $this->model_snake_case.'_id')->first();

        $document_line = $document->updateSupplierCommentLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }

}