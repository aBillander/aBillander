<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\Configuration;
use App\ShippingMethod;

trait BillableFormsControllerTrait
{
   use SupplierBillableFormsControllerTrait;

    public function FormForProduct( $action )
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

    public function FormForService( $action )
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

    public function FormForComment( $action )
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

    public function storeDocumentLine(Request $request, $document_id)
    {
        if ( $request->has('supplier_id') )
            return $this->storeSupplierDocumentLine($request, $document_id);
        
        
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->storeDocumentLineProduct($request, $document_id);
                break;
            
            case 'service':
                # code...
                return $this->storeDocumentLineService($request, $document_id);
                break;
            
            case 'comment':
                # code...
                return $this->storeDocumentLineComment($request, $document_id);
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

    public function storeDocumentLineProduct(Request $request, $document_id)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $document = $this->document
                        ->with('customer')
                        ->with('taxingaddress')
                        ->with('salesrep')
                        ->with('currency')
                        ->find($document_id);

        if ( !$document )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );


        $product_id     = $request->input('product_id');
        $combination_id = $request->input('combination_id', null);
        $quantity       = $request->input('quantity', 1.0);

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->customer->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'prices_entered_with_tax' => $pricetaxPolicy,
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_customer_final_price' => $request->input('unit_customer_final_price'),

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

        $document_line = $document->addProductLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function storeDocumentLineService(Request $request, $document_id)
    {
        $document = $this->document
                        ->with('customer')
                        ->with('taxingaddress')
                        ->with('shippingaddress')
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

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->customer->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'line_type' => $request->input('is_shipping', 0) ? 'shipping' : $request->input('line_type', 'service'),
            'prices_entered_with_tax' => $pricetaxPolicy,
            'cost_price' => $request->input('cost_price', 0.0),
            'unit_price' => $request->input('unit_price', 0.0),
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_customer_price' => $request->input('unit_customer_price'),
            'unit_customer_final_price' => $request->input('unit_customer_final_price'),
            'tax_id' => $request->input('tax_id', Configuration::get('DEF_TAX')),

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


        // Final touches
        if ( $params['line_type'] == 'shipping' )
        if ( $request->input('use_shipping_method', 0) > 0 )
        // Do start Shipping Cost Engine
        {
            // 
            $method = $document->shippingaddress->getShippingMethod();
            $free_shipping = (Configuration::getNumber('ABCC_FREE_SHIPPING_PRICE') >= 0.0) ? Configuration::getNumber('ABCC_FREE_SHIPPING_PRICE') : null;

            list($shipping_label, $cost, $tax) = array_values(ShippingMethod::costPriceCalculator( $method, $document, $free_shipping ));

            $data = [
                'name' => $shipping_label.' :: '.$method->name,
                'cost_price' => $cost,
                'unit_price' => $cost,
                'unit_customer_price' => $cost,
                'unit_customer_final_price' => $cost,
                'tax_id' => $tax->id,
                'sales_equalization' => 0,
            ];

            $params = array_merge($params, $data);
        }

        // Let's Rock!

        $document_line = $document->addServiceLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function storeDocumentLineComment(Request $request, $document_id)
    {
        $document = $this->document
                        ->with('customer')
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

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->customer->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'line_type' => 'comment',
            'prices_entered_with_tax' => $pricetaxPolicy,
            'cost_price' => $request->input('cost_price', 0.0),
            'unit_price' => $request->input('unit_price', 0.0),
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_customer_final_price' => $request->input('unit_customer_final_price'),
            'tax_id' => $request->input('tax_id', Configuration::get('DEF_TAX')),

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

        $document_line = $document->addCommentLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateDocumentLine(Request $request, $line_id)
    {
        if ( $request->has('supplier_id') )
            return $this->updateSupplierDocumentLine($request, $line_id);
        
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->updateDocumentLineProduct($request, $line_id);
                break;
            
            case 'service':
            case 'shipping':
                # code...
                return $this->updateDocumentLineService($request, $line_id);
                break;
            
            case 'comment':
                # code...
                return $this->updateDocumentLineComment($request, $line_id);
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

    public function updateDocumentLineProduct(Request $request, $line_id)
    {

        $params = [
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_customer_final_price' => $request->input('unit_customer_final_price'),

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

        if ($request->has('unit_customer_final_price')) 
            $params['unit_customer_final_price'] = $request->input('unit_customer_final_price');

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

        $document_line = $document->updateProductLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateDocumentLineService(Request $request, $line_id)
    {

        $params = [
//            'line_type' => $request->input('is_shipping', 0) ? 'shipping' : $request->input('line_type', 'service'),
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_customer_final_price' => $request->input('unit_customer_final_price'),

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

        if ($request->has('unit_customer_final_price')) 
            $params['unit_customer_final_price'] = $request->input('unit_customer_final_price');

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

        $document_line = $document->updateServiceLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateDocumentLineComment(Request $request, $line_id)
    {

        $params = [
//            'line_type' => $request->input('is_shipping', 0) ? 'shipping' : $request->input('line_type', 'service'),
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_customer_final_price' => $request->input('unit_customer_final_price'),

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

        if ($request->has('unit_customer_final_price')) 
            $params['unit_customer_final_price'] = $request->input('unit_customer_final_price');

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

        $document_line = $document->updateCommentLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }
}