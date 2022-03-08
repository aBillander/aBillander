<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuration; 
use \iImage;

class AbccBillboardController extends Controller
{
/*
   protected $todo;

   public function __construct(Todo $todo)
   {
        $this->todo = $todo;
   }
*/   
    /**
     * Display a listing of the resource.
	 * GET /todos
     *
     * @return Response
     */

    public function index()
    {

        // return view('todos.index', compact('todos'));
        
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /todos/create
	 *
	 * @return Response
	 */
	public function create()
	{
		// return view('todos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /todos
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // return redirect('todos')
		//		->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $todo->id], 'layouts') . $request->input('name'));
    }

	/**
	 * Display the specified resource.
	 * GET /todos/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// return $this->edit($todo);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /todos/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		$billboard = new \stdClass();

		$billboard->image           = Configuration::get('ABCC_BB_IMAGE');
		$billboard->image_default   = Configuration::get('ABCC_BB_IMAGE_DEFAULT');	// No interface to store / update. Used here only.
		$billboard->caption         = Configuration::get('ABCC_BB_CAPTION');
		$billboard->caption_default = Configuration::get('ABCC_BB_CAPTION_DEFAULT');	// No interface to store / update. Used here only.
		$billboard->active          = Configuration::get('ABCC_BB_ACTIVE');
		// $billboard-> = Configuration::get('ABCC_BB_');

		$activeList = [];

		return view('abcc_billboard.edit', compact('billboard', 'activeList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /todos/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
        //  abi_r($request->all());die();

        $bbfolder = public_path() . abi_tenant_local_path( 'images_bb/' );

        // File stuff
        $extra_data = [
            'extension' => ( $request->file('data_file') ? 
                             $request->file('data_file')->getClientOriginalExtension() : 
                             null 
                            ),
        ];

        $rules = [
                'data_file' => 'image|nullable|max:1999',	// 'required | max:8000',
 //               'extension' => 'in:csv,xlsx,xls,ods', // all working except for ods
        ];

        $this->validate($request->merge( $extra_data ), $rules);

        // Handle File Upload
        if ( $request->hasFile('data_file') )
        {
        	// Get filename with the extension
        	$filenameWithExt = $request->file('data_file')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('data_file')->getClientOriginalExtension();
            // Filename to store
            $stub = '_'.time(); // Make file unique
            $fileNameToStore= $filename.$stub.'.'.$extension;

            // Upload Image
            // $path = $request->file('data_file')->store($bbfolder, $fileNameToStore);            
			// iImage::make($file)->resize(300,300)->save( public_path( \App\Models\Company::imagesPath() . $filename ) );
			iImage::make( $request->file('data_file') )->save( $bbfolder . $fileNameToStore );

            // Delete previous File
            if ( Configuration::get('ABCC_BB_IMAGE') )
            {
				$old_file = $bbfolder . Configuration::get('ABCC_BB_IMAGE');
		        if ( file_exists( $old_file ) ) {
		            unlink( $old_file );
	      		}
      		}

			// Safe Update 
			Configuration::updateValue('ABCC_BB_IMAGE', $fileNameToStore);

        } else {
            // Just keep current
            // $fileNameToStore = 'Dashboard.jpg';
        }


        // Other simple stuff
        Configuration::updateValue('ABCC_BB_CAPTION', $request->input('caption', ''));

        Configuration::updateValue('ABCC_BB_ACTIVE', $request->input('active', 'none'));


		return redirect()->route('abccbillboard.edit')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /todos/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        // return redirect('todos')
		//		->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}