<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Image as Image;
use \iImage as iImage;

class ImagesController extends Controller {


   protected $image;

   public function __construct(Image $image)
   {
        $this->image = $image;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//

		return view('images.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('images.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		// echo_r($request); die();

		$this->validate($request, Image::$rules);
		// return 'Hi There!';

		$image = $this->image->create($request->except(['image']));
		// $image = $this->image->create($request->all());
		// return 'Hi There! - OK';
/*
		    $image = new Image(array(
		      'caption'  => $request->get('caption')
		    ));

		    $image->save();
*/
		// Rock n Roll here:

	   // ToDo: Set folder properly
	   // https://laracasts.com/discuss/channels/general-discussion/where-do-you-set-public-directory-laravel-5
		$destinationFolder = '/imagetest/';


	   $file = $request->file('image');

	   $imageName = $image->id;
	   $extension = $request->file('image')->getClientOriginalExtension();

	   //create instance of image from temp upload

	   $image = iImage::make($file->getRealPath())
	   			->save(public_path() . $destinationFolder . $imageName . '.' . $extension);

	   // save image with thumbnail
	   // https://stackoverflow.com/questions/26890539/intervention-image-aspect-ratio

	   	// This will generate an image with transparent background
		// If you need to have a background you can pass a third parameter (e.g: '#000000')
		$canvas = iImage::canvas(145, 145);

//		$image  = Image::make($main_picture->getRealPath())->resize(245, 245, function($constraint)
//		{
//		    $constraint->aspectRatio();
//		});

		$image->resize(145, 145, function ($constraint) {
	       		$constraint->aspectRatio();
//			    $constraint->upsize();
			});

		$canvas->insert($image, 'center');
		$canvas->save(public_path() . $destinationFolder . $imageName . '-mini_default' . '.' . $extension);

/*
	   $image->save(public_path() . $destinationFolder . $imageName . '.' . $extension)
	       ->fit(145, 145, function ($constraint) {
	       		$constraint->aspectRatio();
			    $constraint->upsize();
			})
	       // ->resize(45, 45)
	       // ->greyscale()
	       ->save(public_path() . $destinationFolder . $imageName . '-mini_default' . '.' . $extension);

*/
		return redirect('images')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $imageName], 'layouts') . $request->input('name'));
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		/*


   $marketingImage = Marketingimage::findOrFail($id);
   $thumbPath = $marketingImage->image_path.'thumbnails/';

   File::delete(public_path($marketingImage->image_path).
                            $marketingImage->image_name . '.' .
                            $marketingImage->image_extension);

    Marketingimage::destroy($id);



		*/
	}

}
