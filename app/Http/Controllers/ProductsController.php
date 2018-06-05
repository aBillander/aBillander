<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Product as Product;
use Form, DB;

class ProductsController extends Controller {


   protected $product;

   public function __construct(Product $product)
   {
        $this->product = $product;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $products = $this->product->isManufactured()->filter( $request->all() )
                                  ->with('measureunit');


        $products = $products->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($products, true);

        $products->setPath('products');     // Customize the URI used by the paginator

        return view('products.index', compact('products'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
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

        $rules = Product::$rules['create'];

        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
            unset($rules['price']);
        else 
            unset($rules['price_tax_inc']);

        $this->validate($request, $rules);

        $tax = \App\Tax::find( $request->input('tax_id') );
        if ( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ){
            $price = $request->input('price_tax_inc')/(1.0+($tax->percent/100.0));
            $request->merge( ['price' => $price] );
        } else {
            $price_tax_inc = $request->input('price')*(1.0+($tax->percent/100.0));
            $request->merge( ['price_tax_inc' => $price_tax_inc] );
        }


        // Create Product
        $product = $this->product->create($request->all());


        // Stock Movement
        if ($request->input('quantity_onhand')>0) 
        {
            // Create stock movement (Initial Stock)
            $data = [   'date' =>  \Carbon\Carbon::now(), 
                        'document_reference' => '', 
                        'price' => $request->input('price'), 
    //                    'price_tax_inc' => $request->input('price_tax_inc'), 
                        'quantity' => $request->input('quantity_onhand'),  
                        'notes' => '',
                        'product_id' => $product->id, 
                        'currency_id' => \App\Context::getContext()->currency->id, 
                        'conversion_rate' => \App\Context::getContext()->currency->conversion_rate, 
                        'warehouse_id' => $request->input('warehouse_id'), 
                        'movement_type_id' => 10,
                        'model_name' => '', 'document_id' => 0, 'document_line_id' => 0, 'combination_id' => 0, 'user_id' => \Auth::id()
            ];
    
            // Initial Stock
            $stockmovement = \App\StockMovement::create( $data );
    
            // Stock movement fulfillment (perform stock movements)
            $stockmovement->process();
        }


        // Prices according to Ptice Lists
        $plists = \App\PriceList::get();

        foreach ($plists as $list) {

            $price = $list->calculatePrice( $product );
            // $product->pricelists()->attach($list->id, array('price' => $price));
            $line = \App\PriceListLine::create( [ 'product_id' => $product->id, 'price' => $price ] );

            $list->pricelistlines()->save($line);
        }


        if ($action == 'completeProductData')
        return redirect('products/'.$product->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $product->id], 'layouts') . $request->input('name'));
        else
        return redirect('products')
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
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->product
                        ->with('tax')
                        ->with('warehouses')
                        ->with('combinations')
                        ->with('combinations.options')
                        ->with('combinations.options.optiongroup')
                        ->isManufactured()
                        ->with('measureunit')
                        ->with('pricelists')
                        ->findOrFail($id);

        
        // BOMs
        $bom = $product->bom();

        
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

        
        // Price Lists
        // See: https://stackoverflow.com/questions/44029961/laravel-search-relation-including-null-in-wherehas
        $pricelists = $product->pricelists; //  \App\PriceList::with('currency')->orderBy('id', 'ASC')->get();

        return view('products.edit', compact('product', 'bom', 'groups', 'pricelists'));
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

            \App\BOMItem::create($request->all() + ['product_id' => $id]);

            return redirect('products/'.$id.'/edit'.'#'.'manufacturing')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
        }

        if (  $rules_tab == 'bom_create' ) {
            //
//            abi_r($request->all(), true);
//            $this->validate($request, \App\BOMItem::$rules);

            $bom = \App\ProductBOM::create($request->all());

            \App\BOMItem::create($request->all() + ['product_bom_id' => $bom->id]);

            return redirect('productboms/'.$bom->id.'/edit')
                    ->with('success', l('Complete la Lista de Materiales para el Producto &#58&#58 (:id) ', ['id' => $product->id], 'layouts') . $product->name);
        }

        if ( $rules_tab == 'main_data' ) $rules_tab = 'create';

        $vrules = Product::$rules[ $rules_tab ];

        if ( $product->reference == $request->input('reference')) unset($vrules['reference']);

        $this->validate($request, $vrules);

        $product->update($request->all());

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
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );
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




/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxProductSearch(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;

//        if ( $query )

        if ($request->has('query'))
        {
            $onhand_only = ( $request->has('onhand_only') ? 1 : 0 );

//            return Product::searchByNameAutocomplete($query, $onhand_only);
            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
        } else {
            // die silently
            return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        }
    }

}
