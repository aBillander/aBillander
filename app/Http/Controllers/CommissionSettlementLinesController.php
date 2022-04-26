<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\ViewFormatterTrait;
use App\Traits\DateFormFormatterTrait;

use App\Models\Configuration;

use App\Models\CommissionSettlement;
use App\Models\CommissionSettlementLine;
use App\Models\SalesRep;
use App\Models\CustomerInvoice;

class CommissionSettlementLinesController extends Controller
{
    use ViewFormatterTrait;
    use DateFormFormatterTrait;
   
   protected $stockcount;
   protected $stockcountline;

   public function __construct(CommissionSettlement $settlement, CommissionSettlementLine $settlementline)
   {
        $this->settlement = $settlement;
        $this->settlementline = $settlementline;
   }

    /**
     *
     *  More stuff
     *
     */



    public function unlink(Request $request, $id)
    {
        $line = $this->settlementline->with('commissionsettlement')->findOrFail($id);

        $settlement = $line->commissionsettlement;

        $line->delete();

        // Update Settlement
        $settlement->updateTotal();

        return redirect()->route('commissionsettlements.show', $settlement->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $settlement->id], 'layouts') . $settlement->document_date);
    }

}
