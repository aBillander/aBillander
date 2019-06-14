<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Customer;
use App\CustomerOrder as Document;
use App\CustomerOrderLine as DocumentLine;
use App\CustomerOrderLineTax as DocumentLineTax;

use App\ProductionSheet;
use App\DocumentAscription;

use App\Configuration;
use App\Sequence;
use App\Template;

trait BillableProductionSheetableControllerTrait
{

/* ********************************************************************************************* */    


//      Manufacturing


/* ********************************************************************************************* */    


    public function move(Request $request, $id)
    {
        $order = Document::findOrFail($id);

        $order->update(['production_sheet_id' => $request->input('production_sheet_id')]);

        // Update Sheets!
        $sheet = ProductionSheet::findOrFail( $request->input('current_production_sheet_id') );
        $sheet->calculateProductionOrders();

        $sheet = ProductionSheet::findOrFail( $request->input('production_sheet_id') );
        $sheet->calculateProductionOrders();

        if ( $request->input('stay_current_sheet', 0) )
            $sheet_id = $request->input('current_production_sheet_id');
        else
            $sheet_id = $request->input('production_sheet_id');

        return redirect('productionsheets/'.$sheet_id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet_id], 'layouts') . $request->input('name', ''));
    }

    public function unlink(Request $request, $id)
    {
        $order = Document::findOrFail($id);

        $sheet_id = $order->production_sheet_id;

        $order->update(['production_sheet_id' => null]);

        // $sheet_id = $request->input('current_production_sheet_id');

        return redirect()->route('productionsheet.calculate', [$sheet_id]);
    }



/* ********************************************************************************************* */    


// https://stackoverflow.com/questions/39812203/cloning-model-with-hasmany-related-models-in-laravel-5-3


/* ********************************************************************************************* */  


}