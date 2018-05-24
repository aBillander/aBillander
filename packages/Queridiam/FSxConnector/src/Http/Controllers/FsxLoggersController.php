<?php

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Queridiam\FSxConnector\FsxLogger;

class FsxLoggersController extends Controller {


   protected $fsx_logger;

   public function __construct(FsxLogger $fsx_logger)
   {
        $this->fsx_logger = $fsx_logger;
   }
    /**
     * Display a listing of the resource.
	 * GET /fsx_loggers
     *
     * @return Response
     */

    public function index()
    {
        $fsx_loggers = $this->fsx_logger->orderBy('id', 'desc')->get();

        $fsx_logger_errors = $this->fsx_logger->where('type', 'ERROR')->count();

        $fsx_logger_warnings = $this->fsx_logger->where('type', 'WARNING')->count();

//        $fsx_loggers = collect([]);

        return view('fsx_connector::fsx_loggers.index', compact('fsx_loggers', 'fsx_logger_errors', 'fsx_logger_warnings'));
        
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /fsx_loggers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('fsx_loggers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /fsx_loggers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, FsxLogger::$rules);

		$fsx_logger = $this->fsx_logger->create($request->all());

		return redirect('fsx_loggers')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $fsx_logger->id], 'layouts') . $request->input('name'));
    }

	/**
	 * Display the specified resource.
	 * GET /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->edit($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /fsx_loggers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$fsx_logger = FsxLogger::findOrFail($id);

		return view('fsx_loggers.edit', compact('fsx_logger'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$fsx_logger = FsxLogger::findOrFail($id);

		$this->validate($request, FsxLogger::$rules);

		$fsx_logger->update($request->all());

		return redirect('fsx_loggers')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->fsx_logger->findOrFail($id)->delete();

        return redirect('fsx_loggers')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


	public function empty()
	{
        $this->fsx_logger->empty();

        return redirect('fsx/fsxlog')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }

}