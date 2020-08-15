<?php

namespace App\Http\Controllers\SalesRepCenter;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Warehouse as Warehouse;
use App\Address as Address;
use App\Country as Country;

use Excel;

use View;

class AbsrcWarehousesController extends Controller {


   protected $warehouse;
   protected $address;

   public function __construct(Warehouse $warehouse, Address $address)
   {
        $this->warehouse = $warehouse;
        $this->address = $address;
   }

	/**
	 * Display a listing of the resource.
	 * GET /warehouses
	 *
	 * @return Response
	 */
	public function index()
	{
        $warehouses = $this->warehouse->with('address')->get();

        return view('warehouses.index', compact('warehouses'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /warehouses/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('warehouses.create'); 
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /warehouses
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // Prepare address data
        // $address = $request->input('address');

		$request->merge( ['alias' => $request->input('address.alias'), 'name' => $request->input('address.name_commercial')] );

		$this->validate($request, $this->warehouse::$rules);
		$this->validate($request, $this->address::related_rules());

		$warehouse = $this->warehouse->create( $request->all() );

		// First record
		if ( Warehouse::count() == 1 ) \App\Configuration::updateValue('DEF_WAREHOUSE', $warehouse->id);

		$data = $request->input('address');
//		$data['notes'] = '';
		$address = $this->address->create( $data );
		$warehouse->addresses()->save($address);

		return redirect('warehouses')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $warehouse->id], 'layouts') . $request->input('name'));
	}

	/**
	 * Display the specified resource.
	 * GET /warehouses/{id}
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
	 * GET /warehouses/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$warehouse = $this->warehouse->with('address')->findOrFail($id);

        $country = Country::find( $warehouse->address->country_id );

        // abi_r($country);

        $stateList = $country ? $country->states()->pluck('name', 'id')->toArray() : [] ;

        // abi_r($stateList, true);
		
		return view('warehouses.edit', compact('warehouse', 'stateList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /warehouses/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$request->merge( ['alias' => $request->input('address.alias'), 'name' => $request->input('address.name_commercial')] );
		
		$this->validate($request, $this->warehouse::$rules);
		$this->validate($request, $this->address::related_rules());

		$warehouse = $this->warehouse->findOrFail($id);
		$address = $warehouse->address;
		
		$warehouse->update( ['notes' => $request->input('address.notes'), 'alias' => $request->input('address.alias'), 'name' => $request->input('address.name_commercial'), 'active' => $request->input('active')] );

		// abi_r($request->all(), true);

		$data = $request->input('address');
//		$data['notes'] = '';
		$address->update( $data );

		return redirect('warehouses')
				->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /warehouses/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $ws = $this->warehouse->findOrFail($id);

        // check if warehouse is in use (short test)
        $in_use= false;

        // Products
        $hasProducts = $ws->whereHas('products', function ($q) {
				        $q->where('product_warehouse.quantity', '!=', 0);
				    })
				    ->exists();
        
        if ( $hasProducts ) {

        	$in_use = true;
        } else 

        // Default Warehouse
        if ( \App\Configuration::get('DEF_WAREHOUSE') == $id ) {

        	$in_use = true;
        }

        if ( $in_use )
	        return redirect('warehouses')
					->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts'));
        

        // So far, so good
        $ws->address->delete();

        $ws->delete();

        return redirect('warehouses')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexProducts($id, Request $request)
	{
        $warehouse = Warehouse::findOrFail($id);

        $products = $warehouse->products()
        				->where('reference', 'LIKE', '%'.$request->input('reference', '').'%')
        				->where('name', 'LIKE', '%'.$request->input('name', '').'%')
                        ->orderBy('products.name', 'asc');

        $products = $products->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $products->setPath('inventory');

       	// abi_r($wh->products);die();

        return view('absrc.warehouses.indexProducts')->with(compact('warehouse', 'products'));
	}
	
	public function exportProducts($id, Request $request)
	{
        $warehouse = Warehouse::findOrFail($id);

        $products = $warehouse->products()
        				->where('reference', 'LIKE', '%'.$request->input('reference', '').'%')
        				->where('name', 'LIKE', '%'.$request->input('name', '').'%')
                        ->orderBy('products.name', 'asc')
                        ->get();

        // Limit number of records
        if ( ($count=$products->count()) > 1000 )
            return redirect()->back()
                    ->with('error', l('Too many Records for this Query &#58&#58 (:id) ', ['id' => $count], 'layouts'));

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Productos en Almacén -::- '.$warehouse->address->alias, '', '', date('d M Y H:i:s')];
        $data[] = ['Filtro Referencia: ' . $request->input('reference', '')];
        $data[] = ['Filtro Nombre de Producto: ' . $request->input('name', '')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $headers = [ 
                    'id', 'reference', 'name', 'quantity',
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($products as $product) {
            // $data[] = $line->toArray();
            $row = [];
            /*
            foreach ($headers as $header)
            {
                $row[$header] = $product->{$header} ?? '';
            }
            */

            $row['id'] = $product->id;
            $row['reference'] = $product->reference;
            $row['name'] = $product->name;

            $row['quantity'] = (float) $product->pivot->quantity;

            $data[] = $row;
        }


        $sheetName = 'Productos en Almacén' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Productos en Almacén', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('A3:C3');
                $sheet->mergeCells('A4:C4');
                
                $sheet->getStyle('A2:C2')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
                
                $sheet->getStyle('A6:D6')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'F' => 'dd/mm/yyyy',
//                    'G' => 'dd/mm/yyyy',
//                    'E' => '0.00%',
                    'H' => '0.00',
//                    'F' => '@',
                ));
                
                $n = count($data);
                $m = $n - 1;
                $sheet->getStyle("H$n:H$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');

	}


	public function indexStockmoves($id)
	{
        $wh = Warehouse::find($id);

        if (is_null($wh)) 
        {
        	return Redirect::route('warehouses.index');
       	}

        return View::make('warehouses.indexStockmoves')->with(array('warehouse' => $wh, 'stockmoves' => $wh->stockmoves));
	}

}