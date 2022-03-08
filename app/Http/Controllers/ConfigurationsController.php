<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Configuration;
use View;

class ConfigurationsController extends Controller {


   protected $configuration;

   public function __construct(Configuration $configuration)
   {
        $this->configuration = $configuration;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $sort  = ($request->has('sort')  ? $request->get('sort')  : 'name'); 
		$order = ($request->has('order') ? $request->get('order') : 'asc');
		
        // $configurations = Configuration::orderBy($sort, $order)->paginate(Configuration::get('DEF_PERPAGE'));
        $configurations = Configuration::orderBy($sort, $order)->get();
        return view('configurations.index')->with(array('configurations' => $configurations, 'sort' => $sort, 'order' => $order));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('configurations.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Configuration::$rules);

        // Prevent NULL values
        $value = is_null( $request->input('value') ) ? '' : $request->input('value');

        $request->merge(['value'=>$value]);

		$configuration = $this->configuration->create($request->all());

		return redirect('configurations')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $configuration->id], 'layouts') . $request->input('name'));
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$configuration = Configuration::findOrFail($id);
		
		return view('configurations.edit', compact('configuration'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$configuration = Configuration::findOrFail($id);

		$this->validate($request, Configuration::$rules);

        // Prevent NULL values
        $value = is_null( $request->input('value') ) ? '' : $request->input('value');

        $request->merge(['value'=>$value]);

		$configuration->update($request->all());

		return redirect('configurations')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->configuration->findOrFail($id)->delete();

        return redirect('configurations')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
