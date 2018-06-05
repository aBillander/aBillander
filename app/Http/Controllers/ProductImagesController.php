<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Product as Product;
use App\Image as Image;

class ProductImagesController extends Controller {


   protected $product;
   protected $image;

   public function __construct(Product $product, Image $image)
   {
        $this->product = $product;
        $this->image = $image;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($productId)
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($productId)
	{
        // $optiongroup = $this->optiongroup->findOrFail($optionGroupId);
        // return view('options.create', compact('optiongroup'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($productId, Request $request)
	{
		// En el formulario Create sepueden listar las combinaciones, si las hay, para asifnarles las imágenes a cada una. Puede ser un thumbnail de la imagen con un check al lado
        $product = $this->product->findOrFail($productId);

        $file = $request->file('image');

 //       $mime = $file->getMimeType();

 //       echo_r($mime);die();

//        echo_r($request->all());die();

		$this->validate($request, Image::$rules);

        $image = $this->image->createForProduct($request);
		
        $product->images()->save($image);

        if ( $request->input('is_featured') || ($product->images()->count() == 1) )
        	$product->setFeaturedImage( $image );

        return redirect('products/'.$productId.'/edit'.'#'.$request->input('tab_name'))
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $image->id], 'layouts') . $image->caption);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($productId, $id)
	{
		return $this->edit($productId, $id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($productId, $id)
	{
        $product = $this->product->findOrFail($productId);
        $image = $this->image->findOrFail($id);

        return view('products.images.edit', compact('product', 'image'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($productId, $id, Request $request)
	{
		// Combinations may own one or more images -> Many to many relation ahead! <- Pero sólo de las imágenes que pertenecen al Product padre!!!
        $product = $this->product->findOrFail($productId);

		$image = Image::findOrFail($id);

		$this->validate($request, Image::$rules);

		$image->update($request->all());

        if ( $request->input('is_featured') || ($product->images()->count() == 1) )
        	$product->setFeaturedImage( $image );

        return redirect('products/'.$productId.'/edit'.'#images')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $image->id], 'layouts') . $image->caption);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($productId, $id)
	{
        // Delete file images
        $image = $this->image->findOrFail($id);
        $image->deleteImage();

        // Check if image is featured <- So what?

        // Check if image belongs to a Combination

        // Delete now!
        $this->image->findOrFail($id)->delete();

        return redirect('products/'.$productId.'/edit'.'#images')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
