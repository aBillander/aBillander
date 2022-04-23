<?php

namespace App\Http\Controllers\SalesRepCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\BankAccount;

class AbsrcBankAccountsController extends Controller
{

    /* ************************************************************************************* */


    public function ibanCalculate(Request $request)
    {
        //
        $ccc_entidad = $request->input('ccc_entidad');
        $ccc_oficina = $request->input('ccc_oficina');
        $ccc_control = $request->input('ccc_control');
        $ccc_cuenta  = $request->input('ccc_cuenta');

        $value = BankAccount::esIbanCalculator($ccc_entidad, $ccc_oficina, $ccc_control, $ccc_cuenta);

        return response()->json( [
                'msg' => 'OK',
                'data' => ['iban' => $value]
        ] );
    }

}
