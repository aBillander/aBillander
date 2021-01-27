<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use App\Product;
use App\PackItem;

class PackItemsController extends Controller {


   protected $product;
   protected $packitem;

   public function __construct(Product $product, PackItem $packitem)
   {
        $this->product = $product;
        $this->packitem = $packitem;
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param         $product_id
     * @param Request $request
     * @return Response
     */
	public function store($product_id, Request $request)
	{
		$product = $this->product->findOrFail($product_id);

		// $this->validate($request, Packitem::$rules);

		// Use own validator
		$validator = Validator::make($request->all(), Packitem::$rules);
     
	    if ($validator->fails()) {
	    	// to do
            return redirect('products/' . $product_id . '/edit' . '#' . $request->input('tab_name'))
	    		->withErrors($validator)
	    		->withInput();
	    }

        $extra_data = [
            'reference' => $product->reference,
            'name' => $product->name,
        ];

        $packitem = $this->packitem->create($request->all() + $extra_data );

        // abi_r($request->all());die();
        // abi_r($packitem);die();

        $product->packitems()->save($packitem);

        if($request->ajax()){

            return response()->json( [
                'msg' => 'OK',
                'data' => $packitem->toArray()
            ] );

        }

        return redirect('products/' . $product_id . '/edit' . '#' . $request->input('tab_name'))
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $image->id], 'layouts') . $image->caption);
	}

    /**
     * Display the specified resource.
     *
     * @param     $product_id
     * @param int $id
     * @return Response
     */
	public function show($product_id, $id)
	{
		return $this->edit($product_id, $id);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param     $product_id
     * @param int $id
     * @return Response
     */
	public function edit($product_id, $id)
	{
        $product = $this->product->findOrFail($product_id);
        $image = $this->image->findOrFail($id);

        return view('products.images.edit', compact('product', 'image'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param         $product_id
     * @param int     $id
     * @param Request $request
     * @return Response
     */
	public function update($product_id, $id, Request $request)
	{
		// Combinations may own one or more images -> Many to many relation ahead! <- Pero sólo de las imágenes que pertenecen al Product padre!!!
        $product = $this->product->findOrFail($product_id);
		$image = Packitem::findOrFail($id);

		$this->validate($request, Packitem::$rules_updating);

		$image->update($request->all());

        if ( $request->input('is_featured') || ($product->images()->count() == 1) )
        	$product->setFeaturedPackitem( $image );

        return redirect('products/' . $product_id . '/edit' . '#images')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $image->id], 'layouts') . $image->caption);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param     $product_id
     * @param int $id
     * @return Response
     */
	public function destroy($product_id, $id)
	{

        // Delete now!
        $this->packitem->findOrFail($id)->delete();

        return redirect('products/' . $product_id . '/edit' . '#pack')
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
                PackItem::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }


}
