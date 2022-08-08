<?php

namespace Queridiam\POS\Http\Controllers;

use App\Http\Controllers\Controller;
use Queridiam\POS\Models\CashRegister;
use Illuminate\Http\Request;

class POSHomeController extends Controller
{

   protected $cashregister;

   public function __construct(CashRegister $cashregister)
   {
        $this->cashregister = $cashregister;
   }

   public function index()
   {
        return view('pos::home.index');

        return view('home.index', compact('sells_chart_1', 'sells_chart_2', 'widgets', 'all_locations', 'common_settings', 'is_admin'));
   }
}