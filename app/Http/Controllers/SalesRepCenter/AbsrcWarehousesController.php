<?php

namespace App\Http\Controllers\SalesRepCenter;

use App\Helpers\Exports\ArrayExport;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Country;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Traits\DateFormFormatterTrait;
use Auth;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AbsrcWarehousesController extends Controller
{

   use DateFormFormatterTrait;

   protected $warehouse;
   protected $address;
   protected $stockmovement;

   public function __construct(Warehouse $warehouse, Address $address, StockMovement $stockmovement)
   {
        $this->warehouse = $warehouse;
        $this->address = $address;
        $this->stockmovement = $stockmovement;
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
		if ( Warehouse::count() == 1 ) Configuration::updateValue('DEF_WAREHOUSE', $warehouse->id);

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
        if ( Configuration::get('DEF_WAREHOUSE') == $id ) {

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
        if (Auth::user()->warehouse_id > 0)
        if ($id != Auth::user()->warehouse_id)
            return redirect()->back()
                    ->with('error', l('You are not allowed to access to this resource', 'layouts'));
        
        $warehouse = Warehouse::findOrFail($id);

        $products = $warehouse->products()
        				->where('reference', 'LIKE', '%'.$request->input('reference', '').'%')
        				->where('name', 'LIKE', '%'.$request->input('name', '').'%')
                        ->orderBy('products.name', 'asc');

        $products = $products->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

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
        $data[] = [Context::getContext()->company->name_fiscal];
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


        $styles = [
            'A2:C2'    => ['font' => ['bold' => true]],
            'A6:D6'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'B' => NumberFormat::FORMAT_TEXT,
//            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3', 'A4:C4'];

        $sheetTitle = 'Productos en Almacén';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

	}


	public function indexStockMovements($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        if (Auth::user()->warehouse_id > 0)
        if ($id != Auth::user()->warehouse_id)
            return redirect()->back()
                    ->with('error', l('You are not allowed to access to this resource', 'layouts'));

        $warehouse = Warehouse::findOrFail($id);

        $stockmovements = $this->stockmovement
                                ->filter( $request->all() )
                                ->where('warehouse_id', $warehouse->id)
//                                ->with('warehouse')
                                ->with('product')
                                ->with('combination')
//                              ->with('stockmovementable')
                                ->with('stockmovementable.document')
                                ->orderBy('created_at', 'DESC')
                                ->orderBy('id', 'DESC');

//         abi_r($stockmovements->toSql(), true);

        $stockmovements = $stockmovements->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );
        // $stockmovements = $stockmovements->paginate( 1 );

        $stockmovements->setPath('stockmovements');

        $movement_typeList = StockMovement::stockmovementList();

        return view('absrc.warehouses.indexStockMovements')->with(compact('warehouse', 'stockmovements', 'movement_typeList'));
	}

}