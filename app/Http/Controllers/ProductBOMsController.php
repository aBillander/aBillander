<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\ProductBOM;
use App\ProductBOMLine;
use View;

class ProductBOMsController extends Controller
{

   protected $bom;
   protected $bom_line;

   public function __construct(ProductBOM $bom, ProductBOMLine $bom_line)
   {
        $this->bom      = $bom;
        $this->bom_line = $bom_line;
   }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $productboms = $this->bom
                        ->filter( $request->all() )
                        ->orderBy('alias', 'asc');     // ->get();

        $productboms = $productboms->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $productboms->setPath('productboms');

        return view('product_boms.index', compact('productboms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product_boms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ProductBOM::$rules);

        $bom = $this->bom->create($request->all());

        return redirect('productboms/'.$bom->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $bom->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductBOM  $productBOM
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBOM $productBOM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductBOM  $productBOM
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bom = $this->bom
                        ->with('measureunit')
                        ->with('BOMlines')
                        ->with('BOMlines.product')
                        ->with('BOMlines.measureunit')
                        ->findOrFail($id);

        return view('product_boms.edit', compact('bom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductBOM  $productBOM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bom = $this->bom->findOrFail($id);

        $vrules = ProductBOM::$rules;

        if ( isset($vrules['alias']) ) $vrules['alias'] = $vrules['alias'] . ','. $bom->id.',id'; // ,deleted_at,NULL';  // Unique

        $this->validate($request, $vrules);

        $bom->update($request->all());

        return redirect( route('productboms.edit', $id) )
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductBOM  $productBOM
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bom = $this->bom->findOrFail($id);

        // Destroy BOM Lines
        if ( $bom->bomlines()->count() )
            foreach ($bom->bomlines as $line) {
                $line->delete();
            }

        // Destroy BOM Items
        if ( $bom->bomitems()->count() )
            foreach ($bom->bomitems as $line) {
                $line->delete();
            }

        // Destroy BOM
        $bom->delete();

        return redirect('productboms')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

    public function duplicateBOM($id)
    {
        $bom = $this->bom->findOrFail($id);

        // Duplicate BOM
        $clone = $bom->replicate();
        $clone->alias .= '-COPIA';
        $clone->name = '[COPIA] '.$clone->name;
        $clone->save();

        // Duplicate BOM Lines
        if ( $bom->bomlines()->count() )
            foreach ($bom->bomlines as $line) {
                $clone->bomlines()->save($line->replicate());
            }

        // Save BOM
        $clone->push();

        return redirect( route('productboms.edit', $clone->id) )
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $clone->id], 'layouts') . $clone->name);
    }


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function getBOMlines($id)
    {
        $bom = $this->bom
//                        ->with('measureunit')
                        ->with('BOMlines')
                        ->with('BOMlines.product')
                        ->with('BOMlines.measureunit')
                        ->findOrFail($id);

        return view('product_boms._panel_bom_lines', compact('bom'));
    }

    public function getBOMline($bom_id, $line_id)
    {
        $bom_line = $this->bom_line
                        ->with('product')
                        ->with('measureunit')
                        ->findOrFail($line_id);

        return response()->json( $bom_line->toArray() );
    }

    public function updateBOMline(Request $request, $line_id)
    {
        $bom_line = $this->bom_line
                        ->findOrFail($line_id);

        $bom_line->update( $request->all() );

        return response()->json( [
                'msg' => 'OK',
                'data' => $bom_line->toArray()
        ] );
    }

    public function searchProduct(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where( function ($query) use ($search) {
                                        $query->where(  'name',      'LIKE', '%'.$search.'%' )
                                              ->OrWhere('reference', 'LIKE', '%'.$search.'%' );
                                } )
                                ->where( function ($query) {
                                        $query->where(  'procurement_type', '=', 'purchase')
                                              ->OrWhere('procurement_type', '=', 'assembly');
                                } )
//                                ->isPurchased()
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
        return response( $products );
    }

    public function storeBOMline(Request $request, $bom_id)
    {
        $bom = $this->bom->with('products')->findOrFail($bom_id);

        // Check to avoid infinite loops in BOM
        // Products that owns this BOM:
        $bomable = \App\Product::findOrFail( $request->input('product_id') );
        $bomable_bom = $bomable->bom;
        $products = $bom->products;   // Products that own this BOM

        foreach ($products as $product) {
          # code...
          if ( !( ($product->procurement_type == 'manufacture') || ($product->procurement_type == 'assembly') ) ) continue;

//            $bom1 = $product->bom;

//            if ( $bom1 && $bom1->hasProduct( $request->input('product_id') ) )
            if ( ($product->id==$bomable->id) || optional($bomable_bom)->hasProduct( $product->id ) )
            {
                return response()->json( [
                        'msg' => 'ERROR',
                        'data' => $request->all(),
                        'products' => $products,
                        'found' => $bom
                ] );

                return redirect('products/'.$id.'/edit'.'#'.'manufacturing')
                        ->with('error', l('No se puede asociar esta Lista de materiales porque contiene al Producto &#58&#58 (:id) ', ['id' => $id], 'layouts') . $product->name);
            }


        }
/*
        if ( $products->where( 'id', $request->input('product_id') )->count() )
        {
          return response()->json( [
                  'msg' => 'ERROR',
                  'data' => $request->all(),
                  'products' => $products,
                  'found' => $products->where( 'id', $request->input('product_id') )->count()
          ] );
        }
*/

        $bom_line = $this->bom_line->create( $request->all() );

        $bom->bomlines()->save($bom_line);

        return response()->json( [
                'msg' => 'OK',
                'data' => $bom_line->toArray()
        ] );
    }

    public function deleteBOMline($line_id)
    {
        $bom_line = $this->bom_line
                        ->findOrFail($line_id);

        $bom_line->delete();

        return response()->json( [
                'msg' => 'OK',
                'data' => ''
        ] );
    }

    public function getBOMproducts($id)
    {
        $bom = $this->bom
//                        ->with('measureunit')
                        ->with('products')
                        ->findOrFail($id);

        return view('product_boms._panel_products', compact('bom'));
    }

    public function getproductBOMs($id)
    {
        $product = \App\Product
                        ::with('productBOMlines')
                        ->with('productBOMlines.productBOM')
                        ->findOrFail($id);

        return view('product_boms._panel_product_boms', compact('product'));
    }

    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                ProductBOMLine::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }
}
