<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Product;
use App\StockMovement;
use App\PriceRule;

use App\Configuration;

use Form, DB;

// use App\CustomerOrder;
use App\CustomerOrderLine;
// use App\CustomerInvoice;
use App\CustomerInvoiceLine;
// use App\CustomerShippingSlip;
use App\CustomerShippingSlipLine;

use App\Events\ProductCreated;

use App\Traits\DateFormFormatterTrait;

class ProductsController extends Controller
{
   
   use DateFormFormatterTrait;


   protected $product;

   public function __construct(Product $product)
   {
        $this->product = $product;
   }
   
    /**
     * Display a listing of the resource.
     *
     */

    public function indexQueryRaw(Request $request)
    {
        return $this->product
                              ->filter( $request->all() )
                              ->with('measureunit')
                              ->with('combinations')                                  
                              ->with('category')
                              ->with('tax')
                              ->orderBy('reference', 'asc');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        
        $category_id = $request->input('category_id', 0);
        // Not needed: $request->merge( ['category_id' => $category_id] );

        $parentId=0;
        $breadcrumb = [];

        $categories = \App\Category::with('children')
//          ->withCount('products')
            ->where('parent_id', '=', intval($parentId))
            ->orderBy('name', 'asc')->get();

        if ($category_id>0 && !$request->input('search_status', 0)) {
            //
            // abi_r($categories, true);

            $category = $categories->search(function ($item, $key) use ($category_id) {
                
                $cat = $item->children;

                $c = $cat->search(function ($item, $key) use ($category_id) {
                    return $item->id == $category_id;
                });

                // Found?
                return $c !== false;
            });

            $parent = $categories->slice($category, 1)->first();
            $child = $parent->children->where('id', $category_id)->first();

            $breadcrumb = [$parent, $child];

            // abi_r($parent->name.' / '.$child->name, true);
        }

        $products = $this->indexQueryRaw( $request )
//                         ->isManufactured()
                        ;

//        abi_r($products->toSql(), true);

        $products = $products->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($products, true);

        $products->setPath('products');     // Customize the URI used by the paginator

        $bom = $categories;

        return view('products.index', compact('category_id', 'products', 'bom', 'breadcrumb'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = \App\Category::exists();

        if ( !$categories )
        {
            return redirect()->back()       // redirect('products')
                    ->with('error', l('You have to create a Category first'));
        }

        $sub_categories = \App\Category::where('parent_id', '>', 0)->exists();

        if ( Configuration::isTrue('ALLOW_PRODUCT_SUBCATEGORIES') && !$sub_categories )
        {
            return redirect()->back()       // redirect('products')
                    ->with('error', l('You have to create a Sub-Category first'));
        }

        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // if ( !( $request->input('cost_average') > 0 ) ) $request->merge( ['cost_average' => $request->input('cost_price')] );

        $action = $request->input('nextAction', '');


        if ( \Auth::user()->language->iso_code == 'en' )
        {
            if ( !$request->input('name', '') )
            {
                $request->merge( ['name' => $request->input('name_en', '')] );
            }
        }


        $rules = Product::$rules['create'];

        if( Configuration::isTrue('ENABLE_MANUFACTURING') )
            $rules += Product::$rules['manufacturing'];

        if ( Configuration::get('PRICES_ENTERED_WITH_TAX') )
            unset($rules['price']);
        else 
            unset($rules['price_tax_inc']);

        $this->validate($request, $rules);

        $tax = \App\Tax::find( $request->input('tax_id') );
        if ( Configuration::get('PRICES_ENTERED_WITH_TAX') ){
            $price = $request->input('price_tax_inc')/(1.0+($tax->percent/100.0));
            $request->merge( ['price' => $price] );
        } else {
            $price_tax_inc = $request->input('price')*(1.0+($tax->percent/100.0));
            $request->merge( ['price_tax_inc' => $price_tax_inc] );
        }

        // If sequences are used:
        //
        // $product_sequences = \App\Sequence::listFor(\App\Product::class);

        // Create Product
        $product = $this->product->create($request->all());


        // Event
        // event( new ProductCreated(), $data );

        // Stock Movement
        if ($request->input('quantity_onhand')>0) 
        {
            // Create stock movement (Initial Stock)
            $data = [   
                        'movement_type_id' => StockMovement::INITIAL_STOCK,
                        'date' =>  \Carbon\Carbon::now(), 

 //                   'stockmovementable_id' => ,
 //                   'stockmovementable_type' => ,

                        'document_reference' => '', 
                        'quantity' => $request->input('quantity_onhand'),  
                        'price' => null,        // Use default
                        'currency_id' => \App\Context::getContext()->company->currency->id,
                        'conversion_rate' => \App\Context::getContext()->company->currency->conversion_rate,

                        'notes' => '',

                        'product_id' => $product->id, 
                        'combination_id' => 0,
                        'reference' => $product->reference,
                        'name' => $product->name,
                        'warehouse_id' => $request->input('warehouse_id'), 
 //                   'warehouse_counterpart_id' => ,
            ];
    
            // Initial Stock
            $stockmovement = StockMovement::createAndProcess( $data );
        }


/*
        // Prices according to Price Lists
        if (0) {
        $plists = \App\PriceList::get();

        foreach ($plists as $list) {

            $price = $list->calculatePrice( $product );
            // $product->pricelists()->attach($list->id, array('price' => $price));
            $line = \App\PriceListLine::create( [ 'product_id' => $product->id, 'price' => $price ] );

            $list->pricelistlines()->save($line);
        }
        }
*/

        // Not so fast, son. What about Measure Units?
        $data = [
            'product_id' => $product->id,
            'measure_unit_id' => $product->measure_unit_id,
            'stock_measure_unit_id' => $product->measure_unit_id,
            'conversion_rate' => 1.0,
            'active' => 1,
        ];

        $line = \App\ProductMeasureUnit::create( $data );

        $product->productmeasureunits()->save($line);

        // Th-th-th-that's all folks!


        if ($action == 'completeProductData')
        return redirect('products/'.$product->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $product->id], 'layouts') . $request->input('name'));
        else
        return redirect()->back()       // redirect('products')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $product->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }
   
    /**
     * Display a listing of the resource.
     *
     */

    public function editQueryRaw()
    {
        return $this->product
                        ->with('tax')
                        ->with('warehouses')
                        ->with('combinations')
                        ->with('combinations.options')
                        ->with('combinations.options.optiongroup')
                        ->with('measureunit')
                        ->with('pricelists');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->editQueryRaw()
//                        ->isManufactured()
                        ->findOrFail($id);

        
        // Measure Units
        $product_measure_unitList = $product->getMeasureUnitList();


        // BOMs
        $bom = $product->bom;

        
        // Gather Attributte Groups
        $groups = array();

        if ( $product->combinations->count() )
        {
            foreach ($product->combinations as $combination) {
                foreach ($combination->options as $option) {
                    $groups[$option->optiongroup->id]['name'] = $option->optiongroup->name;
                    $groups[$option->optiongroup->id]['values'][$option->optiongroup->id.'_'.$option->id] = $option->name;
                }
            }
        } else {
            $groups = \App\OptionGroup::has('options')->orderby('position', 'asc')->pluck('name', 'id');
        }


        // Dates (cuen)
        $this->addFormDates( ['available_for_sale_date'], $product );
        
        // Price Lists
        // See: https://stackoverflow.com/questions/44029961/laravel-search-relation-including-null-in-wherehas
        $pricelists = $product->pricelists; //  \App\PriceList::with('currency')->orderBy('id', 'ASC')->get();

        return view('products.edit', compact('product', 'product_measure_unitList', 'bom', 'groups', 'pricelists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
 /*   {
        $product = Product::findOrFail($id);

        if ( isset(Product::$rules['main_data']['reference']) ) Product::$rules['main_data']['reference'] .= $product->id;

        // ToDo: reference should be '' for products with combinations (not required, not unique)
        $this->validate($request, Product::$rules[ $request->input('tab_name') ]);

        $product->update($request->all());

        return redirect('products')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    } */
    {
        $product = Product::findOrFail($id);

        $rules_tab = $request->input('tab_name', 'main_data');

        if (  $rules_tab == 'detach_bom' ) {
            //
            $bomItem = $product->bomitem(); 

            $bomItem->delete();

            return redirect('products/'.$id.'/edit'.'#'.'manufacturing')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
        }

        if (  $rules_tab == 'bom_selector' ) {
            //
//            abi_r($request->all(), true);
            $this->validate($request, \App\BOMItem::$rules);

            // Check to avoid infinite loops in BOM
            $bom = \App\ProductBOM::find( $request->input('product_bom_id') );

            if ( $bom->hasProduct( $id ) )
            {
                return redirect('products/'.$id.'/edit'.'#'.'manufacturing')
                        ->with('error', l('No se puede asociar esta Lista de materiales porque contiene al Producto &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
            }

            \App\BOMItem::create($request->all() + ['product_id' => $id]);

            return redirect('products/'.$id.'/edit'.'#'.'manufacturing')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
        }

        if (  $rules_tab == 'bom_create' ) {
            //
//            abi_r($request->all(), true);
//            $this->validate($request, \App\BOMItem::$rules);

            $this->validate($request, \App\ProductBOM::$rules);

            $bom = \App\ProductBOM::create($request->all());

            \App\BOMItem::create($request->all() + ['product_bom_id' => $bom->id]);

            return redirect('productboms/'.$bom->id.'/edit')
                    ->with('success', l('Complete la Lista de Materiales para el Producto &#58&#58 (:id) ', ['id' => $product->id], 'layouts') . $product->name);
        }

//        if ( $rules_tab == 'main_data' ) $rules_tab = 'create';

        $vrules = Product::$rules[ $rules_tab ];

        if (  $rules_tab == 'manufacturing' )
        if( Configuration::isTrue('ENABLE_MANUFACTURING') )
            $vrules += Product::$rules['manufacturing'];


//        if ( $product->reference == $request->input('reference')) unset($vrules['reference']);
//        if ( isset($vrules['reference']) ) $vrules['reference'] .= $product->id;
        if ( isset($vrules['reference']) ) $vrules['reference'] = $vrules['reference'] . ','. $product->id.',id,deleted_at,NULL';  // Unique
        if ( isset($vrules['ean13']) )     $vrules['ean13']     = $vrules['ean13']     . ','. $product->id.',id,deleted_at,NULL';  // Unique

        if ($request->input('tab_name') == 'sales') {
            if ( Configuration::get('PRICES_ENTERED_WITH_TAX') )
                unset($vrules['price']);
            else 
                unset($vrules['price_tax_inc']);
        }

        if ($product->product_type == 'combinable') 
        {
            // Remove reference field
            $request->merge(array('reference' => ''));
            $request->merge(array('ean13' => ''));
            unset( $vrules['reference'] );
            unset( $vrules['ean13'] );
            if ( isset($vrules['reference']) ) 
                unset( $vrules['reference'] );
        }

        if ($request->input('tab_name') == 'sales') {

            // Dates (cuen)
            $this->mergeFormDates( ['available_for_sale_date'], $request );
            
            $tax = \App\Tax::find( $product->tax_id );
            if ( Configuration::get('PRICES_ENTERED_WITH_TAX') ){
                $price = $request->input('price_tax_inc')/(1.0+($tax->percent/100.0));
                $request->merge( ['price' => $price] );

                $price = $request->input('recommended_retail_price')/(1.0+($tax->percent/100.0));
                $request->merge( ['recommended_retail_price' => $price, 'recommended_retail_price_tax_inc' => $request->input('recommended_retail_price')] );
            } else {
                $price_tax_inc = $request->input('price')*(1.0+($tax->percent/100.0));
                $request->merge( ['price_tax_inc' => $price_tax_inc] );
                
                $price_tax_inc = $request->input('recommended_retail_price')*(1.0+($tax->percent/100.0));
                $request->merge( ['recommended_retail_price_tax_inc' => $price_tax_inc] );
            }
        }

        $this->validate($request, $vrules);

        $product->update($request->all());

        // Product Tools
        $tool_id = (int) $request->input('tool_id');

        if ( $product->tool_id != $tool_id )
        {
            $product->producttools->each(function ($item, $key) {
                                            $item->delete();
                                        });

            if ( $tool_id > 0 )
                \App\ProductTool::create( ['product_id' => $id, 'tool_id' => $tool_id] );
        }

        // ToDo: update combination fields, such as measure_unit, quantity_decimal_places, etc.

        return redirect('products/'.$id.'/edit'.'#'.$request->input('tab_name'))
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // Any Documents? If any, cannot delete, only disable

        // Delete Product & Combinations Warehouse lines

        // Delete Combinations

        // Delete Images

        $this->product->findOrFail($id)->delete();

        return redirect('products')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


/* ********************************************************************************************* */  


    /**
     * MISC Stuff.
     *
     * 
     */

    public function duplicate($id)
    {
        $product = $this->product->findOrFail($id);

        // Duplicate
        $clone = $product->replicate();

        $clone->name      = '[COPY] '.$clone->name;
        $clone->reference = '[COPY] '.$clone->reference;
        $clone->ean13 = NULL;

        $clone->supplier_reference =  NULL;

        $clone->quantity_onhand = 0.0;
        $clone->quantity_onorder = 0.0;
        $clone->quantity_allocated = 0.0;
        $clone->quantity_onorder_mfg = 0.0;
        $clone->quantity_allocated_mfg = 0.0;
        
        $clone->last_purchase_price = 0.0;
        $clone->cost_average = 0.0;

        $clone->save();


        // Not so fast, son. What about Measure Units?
        $data = [
            'product_id' => $clone->id,
            'measure_unit_id' => $clone->measure_unit_id,
            'stock_measure_unit_id' => $clone->measure_unit_id,
            'conversion_rate' => 1.0,
            'active' => 1,
        ];

        $line = \App\ProductMeasureUnit::create( $data );

        $clone->productmeasureunits()->save($line);

        // ToDo: Copy alternative Measure Units???

        // Th-th-th-that's all folks!



        // Good boy:


        return redirect()->route('products.edit', [$clone->id])
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $clone->id], 'layouts'));
    }




/* ********************************************************************************************* */    



    /**
     * Make Combinations for specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function combine($id, Request $request)
    {
        $groups = $request->input('groups');

        // Validate: $groups ahold not be empty, and values should match options table
        // http://laravel.io/forum/11-12-2014-how-to-validate-array-input
        // https://www.google.es/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8&client=ubuntu#q=laravel%20validate%20array%20input

        $product = $this->product->findOrFail($id);

        // Start Combinator machime!

        $data = array();

        foreach ( $groups as $group ) 
        {
            $data[] = \App\Option::where('option_group_id', '=', $group)->orderby('position', 'asc')->pluck('id');
        }

        $combos = combos($data);

        $i=0;
        foreach ( $combos as $combo ) 
        {
            $i++;

            $combination = \App\Combination::create(
                array(
//                    'reference'        => $product->reference.'-'.$i,
                    'reference'        => '',
                    'reorder_point'    => $product->reorder_point,
                    'maximum_stock'    => $product->maximum_stock,
                    'supply_lead_time' => $product->supply_lead_time,

                    'measure_unit'            => $product->measure_unit,
                    'quantity_decimal_places' => $product->quantity_decimal_places,

                    'is_default'     => $i == 1 ? 1 : 0,
                    'active'         => $product->active,
                    'blocked'        => $product->blocked,
                    'publish_to_web' => $product->publish_to_web,

                    'product_id'     => $product->id,               // Needed by autoSKU()
                )
            );
            $product->combinations()->save($combination);

            $combination->options()->attach($combo);
        }

        // Combinations are created with stock = 0. 
        // Create combinations only alollowed if product->quantity_onhand = 0 

        $product->reference = '';
        $product->ean13 = '';
        $product->supplier_reference = '';
        $product->product_type = 'combinable';
        $product->save();



        return redirect('products/'.$combination->product_id.'/edit#combinations')
                ->with('success', l('This record has been successfully combined &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
    }


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function searchBOM(Request $request)
    {
        $search = $request->term;

        $boms = \App\ProductBOM::select('id', 'alias', 'name')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'alias', 'LIKE', '%'.$search.'%' )
//                                ->with('measureunit')
                                ->get( intval(Configuration::get('DEF_ITEMS_PERAJAX')) );
/*
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                    'id' => $product->id,
                    'value' => '['.$product->reference.'] '.$product->name,
                    'reference'       => $product->reference,
                    'measure_unit_id' => $product->measure_unit_id,
            ];
        }
*/
        return response( $boms );
    }



/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxProductOptionsSearch(Request $request)
    {
        $product = $this->product
                        ->with('tax')
                        ->with('combinations')
                        ->with('combinations.options')
                        ->with('combinations.options.optiongroup')
                        ->findOrFail($request->input('product_id'));

        // Gather Attributte Groups
        $groups = array();

        if ( $product->combinations->count() )
        {
            foreach ($product->combinations as $combination) {
                foreach ($combination->options as $option) {
                    $groups[$option->optiongroup->id]['name'] = $option->optiongroup->name;
                    // $groups[$option->optiongroup->id]['values'][$option->optiongroup->id.'_'.$option->id] = $option->name;
                    $groups[$option->optiongroup->id]['values'][$option->id] = $option->name;
                }
            }
        } else {
            return '';
        }

        $str = '';

        foreach ($groups as $i => $group) {
        
            $str .= Form::label('group['.$i.']', $group['name']) . 
                    '<div class="option_list">' . 
                    Form::select('group['.$i.']', array('0' => l('-- Please, select --', [], 'layouts')) + $group['values'], null, array('class' => 'form-control option_select')) . 
                    '</div>';
        
        }
        return '<div id="options">' . $str . '</div>';

        // sleep(5);
        // return '<select class="form-control" id="warehouse_id" name="warehouse_id"><option value="0">-- Seleccione --</option><option value="1">Main Address</option><option value="8">CALIMA 25</option></select><select class="form-control" id="warehouse_id" name="warehouse_id"><option value="0">-- Seleccione --</option><option value="1">Main Address</option><option value="8">CALIMA 25</option></select><select class="form-control" id="warehouse_id" name="warehouse_id"><option value="0">-- Seleccione --</option><option value="1">Main Address</option><option value="8">CALIMA 25</option></select>';
        // return 'Hello World! '.$request->input('product_id');

        /*

SELECT combination_id, COUNT(combination_id) tot FROM `combinations` as c
left join combination_option as co
on co.combination_id = c.id
WHERE c.product_id = 15
AND (co.option_id = 4) or (co.option_id = 10) or 1
GROUP BY combination_id ORDER BY tot DESC
LIMIT 1

SELECT page, COUNT(page ) totpages
FROM visitas
GROUP BY page ORDER BY totpages DESC
LIMIT 1

        */
    }

    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxProductCombinationSearch(Request $request)
    {
        if ($request->has('group')) {
            $combination_id = \App\Combination::getCombinationByOptions( $request->input('product_id'), $request->input('group') );

            // ToDo: what happens if $combination_id=0 -> Failed to load resource: the server responded with a status of 500 (Internal Server Error)  http://localhost/aBillander5/public/products/ajax/combination_lookup
            $combination = \App\Combination::select('id', 'product_id', 'reference')
                            ->where('id', '=', $combination_id)
                            ->Where('product_id', '=', $request->input('product_id'))
                            ->take(1)->get();
            return json_encode( $combination[0] );

        } else {
            $combination_id = 0;

        }
    }


/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxProductSearch(Request $request)
    {

        if ($request->has('product_id'))
        {
            $search = $request->product_id;

            $product = Product::

            // select('id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id')
                                      with('measureunit')
                                    ->find( $search );

            return response()->json( [ 'product' => $product ] );
        }

        $term  = $request->has('term')  ? $request->input('term')  : null ;
        $query = $request->has('query') ? $request->input('query') : $term;

        if ( $query )

//        if ($request->has('query'))
        {
            $onhand_only = ( $request->has('onhand_only') ? 1 : 0 );

//            return Product::searchByNameAutocomplete($query, $onhand_only);
            return Product::searchByNameAutocomplete($query, $onhand_only);
        } else {
            // die silently
            return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        }
    }


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxProductPriceSearch(Request $request)
    {
        // Request data
        $product_id      = $request->input('product_id');
        $customer_id     = $request->input('customer_id');
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);
//        $conversion_rate = $request->input('conversion_rate');
//        $product_string  = $request->input('product_string');   // <- Esto es la salida de ajaxProductSearch

    //    $product = \App\Product::find();

        $product = $this->product
                        ->with('tax')
                        ->with('combinations')
                        ->with('combinations.options')
                        ->with('combinations.options.optiongroup')
                        ->find(intval($product_id));

        $customer = \App\Customer::find(intval($customer_id));
        
        $currency = ($currency_id == \App\Context::getContext()->currency->id) ?
                    \App\Context::getContext()->currency :
                    \App\Currency::find(intval($currency_id));

        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        if ( !$product || !$customer || !$currency ) {
            // Die silently
            return '';
        }

        $quantity = $request->input('product_id', 1.0);

        $tax = $product->tax;

        // Calculate price per $customer_id now!
        $product->customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );
        $tax_percent = $tax->percent;
        $product->customer_price->applyTaxPercent( $tax_percent );
//        if ($customer->sales_equalization) $tax_percent += $tax->extra_percent;
//        $product->price_customer_with_tax = $product->price_customer*(1.0+$tax_percent/100.0);

        // Add customer_price
/*
        $p = json_decode( $product_string, true);
        $p = array_merge($p, ['price_customer' => $product->price_customer]);
        $product_string = json_encode($p);
*/
//        $product_string = json_encode($product);

//        return view('products.ajax.show_price', compact('product', 'tax', 'customer', 'currency', 'product_string'));
        return view('products.ajax.show_price', compact( 'product', 'customer', 'currency' ) );
    }


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function getStockMovements($id, Request $request)
    {
        $items_per_page_stockmovements = intval($request->input('items_per_page_stockmovements', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page_stockmovements >= 0) ) 
            $items_per_page_stockmovements = Configuration::get('DEF_ITEMS_PERPAGE');

        $mvts = StockMovement::where('product_id', $id)
                                ->with('product')
                                ->with('combination')
                                ->with('warehouse')
                                ->orderBy('created_at', 'DESC')
                                ->orderBy('id', 'DESC');

        $mvts = $mvts->paginate( $items_per_page_stockmovements );     // Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(Configuration::get('DEF_ITEMS_PERAJAX'))

        $mvts->setPath('stockmovements');

        // return $items_per_page_stockmovements ;
        
        return view('products._panel_stock_movements', compact('mvts', 'items_per_page_stockmovements'));
    }


    public function getPendingMovements($id, Request $request)
    {
        $items_per_page_pendingmovements = intval($request->input('items_per_page_pendingmovements', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page_pendingmovements >= 0) ) 
            $items_per_page_pendingmovements = Configuration::get('DEF_ITEMS_PERPAGE');

        
        $lines = CustomerOrderLine::where('product_id', $id)
                            ->with('document')
                            ->with('document.customer')
//                            ->whereHas('document', function($q) use ($id) {
//                                    $q->where('customer_id', $id);
//                                })
                            ->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
                            ->select('customer_order_lines.*', 'customer_orders.document_date', \DB::raw('"customerorders" as route'))
                            ->orderBy('customer_orders.document_date', 'desc');

        $lines = $lines->paginate( $items_per_page_pendingmovements );     // Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(Configuration::get('DEF_ITEMS_PERAJAX'))

        $lines->setPath('pendingmovements');

        // return $items_per_page_stockmovements ;
        
        return view('products._panel_pending_movements', compact('lines', 'items_per_page_pendingmovements'));
    }


    public function getStockSummary($id, Request $request)
    {
        
        $product = $this->editQueryRaw()
//                        ->isManufactured()
                        ->findOrFail($id);
        
        return view('products._panel_stock_summary', compact('product'));
    }


    public function getRecentSales($id, Request $request)
    {
        $items_per_page = intval($request->input('items_per_page', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::get('DEF_ITEMS_PERPAGE');

        // See: https://stackoverflow.com/questions/28913014/laravel-eloquent-search-on-fields-of-related-model
        $o_lines = CustomerOrderLine::where('product_id', $id)
                            ->with('document')
                            ->with('document.customer')
//                            ->whereHas('document', function($q) use ($id) {
//                                    $q->where('customer_id', $id);
//                                })
                            ->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
                            ->select('customer_order_lines.*', 'customer_orders.document_date', \DB::raw('"customerorders" as route'))
                            ->orderBy('customer_orders.document_date', 'desc')
                            ->take( $items_per_page )
                            ->get();


        $s_lines = CustomerShippingSlipLine::where('product_id', $id)
                            ->with('document')
                            ->with('document.customer')
                            ->whereHas('document', function($q) use ($id) {
//                                    $q->where('customer_id', $id);
                                    $q->where('created_via', 'manual');
//                                   $q->where('status', '!=', 'draft');
                                })
                            ->join('customer_shipping_slips', 'customer_shipping_slip_lines.customer_shipping_slip_id', '=', 'customer_shipping_slips.id')
                            ->select('customer_shipping_slip_lines.*', 'customer_shipping_slips.document_date', \DB::raw('"customershippingslips" as route'))
                            ->orderBy('customer_shipping_slips.document_date', 'desc')
                            ->take( $items_per_page )
                            ->get();


        $i_lines = CustomerInvoiceLine::where('product_id', $id)
                            ->with('document')
                            ->with('document.customer')
                            ->whereHas('document', function($q) use ($id) {
//                                    $q->where('customer_id', $id);
                                    $q->where('created_via', 'manual');
//                                   $q->where('status', '!=', 'draft');
                                })
                            ->join('customer_invoices', 'customer_invoice_lines.customer_invoice_id', '=', 'customer_invoices.id')
                            ->select('customer_invoice_lines.*', 'customer_invoices.document_date', \DB::raw('"customerinvoices" as route'))
                            ->orderBy('customer_invoices.document_date', 'desc')
                            ->take( $items_per_page )
                            ->get();

/*
    Merged collections cannot be easyly paginated :( . 'items_per_page' would be the number of records to show

        $mvts = $mvts->paginate( $items_per_page );
*/
        $lines1 = collect($o_lines);
        $lines2 = collect($s_lines);
        $lines3 = collect($i_lines);

        $lines = $lines1->merge($lines2)->merge($lines3)->sortByDesc('document_date');

        $lines = $lines->take( $items_per_page );
        
        return view('products._panel_recent_sales', compact('lines', 'items_per_page'));
    }

    
    public function getPriceRules($id, Request $request)
    {
        $items_per_page_pricerules = intval($request->input('items_per_page_pricerules', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page_pricerules >= 0) ) 
            $items_per_page_pricerules = Configuration::get('DEF_ITEMS_PERPAGE');

        $product_rules = PriceRule::where('product_id', $id)
                                ->with('customergroup')
                                ->with('customer')
 //                               ->with('combination')
                                ->with('currency')
 //                               ->orderBy('product_id', 'ASC')
                                ->orderBy('customer_id', 'ASC')
                                ->orderBy('from_quantity', 'ASC');

        $product_rules = $product_rules->paginate( $items_per_page_pricerules );     // Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(Configuration::get('DEF_ITEMS_PERAJAX'))

        $product_rules->setPath('customerpricerules');

        //return $items_per_page ;
        
        return view('products._panel_pricerules_list', compact('id', 'product_rules', 'items_per_page_pricerules'));
    }



/* ********************************************************************************************* */    

    /**
     * PDF Stuff.
     *
     * 
     */

/* ********************************************************************************************* */    


    public function getPdfBom(Request $request, $id)
    {
        $product = $this->product
                        ->with('measureunit')
                        ->findOrFail($id);


        // BOMs
        $bom = $product->bom;

        //
        //  return view('product_boms.reports.bom.bom', compact('product', 'bom'));

        // PDF::setOptions(['dpi' => 150]);     // 'defaultFont' => 'sans-serif']);

        $pdf = \PDF::loadView('product_boms.reports.bom.bom', compact('product', 'bom'))->setPaper('a4', 'vertical');

        return $pdf->stream($product->reference.'-bom.pdf'); // $pdf->download('invoice.pdf');
    }
   
}
