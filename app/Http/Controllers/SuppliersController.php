<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Supplier;
use View;

class SuppliersController extends Controller {


   protected $supplier;

   public function __construct(Supplier $supplier)
   {
        $this->supplier = $supplier;
   }
    /**
     * Display a listing of the resource.
	 * GET /suppliers
     *
     * @return Response
     */

    public function index()
    {
        $suppliers = $this->supplier->orderBy('id', 'asc')->get();

        return view('suppliers.index', compact('suppliers'));
        
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /suppliers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('suppliers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /suppliers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, Supplier::$rules);

		$supplier = $this->supplier->create($request->all());

		return redirect('suppliers')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $supplier->id], 'layouts') . $request->input('name'));
    }

	/**
	 * Display the specified resource.
	 * GET /suppliers/{id}
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
	 * GET /suppliers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$supplier = Supplier::findOrFail($id);

		return view('suppliers.edit', compact('supplier'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /suppliers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$supplier = Supplier::findOrFail($id);

		$this->validate($request, Supplier::$rules);

		$supplier->update($request->all());

		return redirect('suppliers')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /suppliers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->supplier->findOrFail($id)->delete();

        return redirect('suppliers')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}