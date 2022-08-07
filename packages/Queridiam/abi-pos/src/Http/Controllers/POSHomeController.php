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
        return 'Hello motto!';
   }
}