<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use App\Product;
use App\Image;

class ProductImagesController extends Controller {


   protected $product;
   protected $image;

   public function __construct(Product $product, Image $image)
   {
        $this->product = $product;
        $this->image = $image;
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
		// En el formulario Create sepueden listar las combinaciones, si las hay, para asifnarles las imágenes a cada una. Puede ser un thumbnail de la imagen con un check al lado
        $product = $this->product->findOrFail($product_id);
        $file = $request->file('image');

		// $this->validate($request, Image::$rules);

		// Use own validator
		$validator = Validator::make($request->all(), Image::$rules);
     
	    if ($validator->fails()) {
	    	return redirect('products/' . $product_id . '/edit' . '#' . $request->input('tab_name'))
	    		->withErrors($validator)
	    		->withInput();
	    }

        $image = $this->image->createForProduct($request);
        $product->images()->save($image);

        if ($request->input('is_featured') || ($product->images()->count() == 1)) {
            $product->setFeaturedImage($image);
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
		$image = Image::findOrFail($id);

		$this->validate($request, Image::$rules_updating);

		$image->update($request->all());

        if ( $request->input('is_featured') || ($product->images()->count() == 1) )
        	$product->setFeaturedImage( $image );

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
        // Delete file images
        $image = $this->image->findOrFail($id);
        $image->deleteImage();

        // Check if image is featured <- So what?

        // Check if image belongs to a Combination

        // Delete now!
        $this->image->findOrFail($id)->delete();

        return redirect('products/' . $product_id . '/edit' . '#images')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
