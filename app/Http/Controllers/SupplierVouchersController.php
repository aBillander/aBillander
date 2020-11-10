<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Supplier;
use App\SupplierShippingSlip as Document;
use App\SupplierShippingSlipLine as DocumentLine;

use App\SupplierInvoice;
use App\SupplierInvoiceLine;
use App\SupplierInvoiceLineTax;
use App\DocumentAscription;

use App\Configuration;
use App\Sequence;
use App\Template;

use App\Events\SupplierShippingSlipConfirmed;

class SupplierVouchersController extends BillableController
{

   public function __construct(Supplier $supplier, Document $document, DocumentLine $document_line)
   {
        parent::__construct();

        $this->model_class = Document::class;

        $this->supplier = $supplier;
        $this->document = $document;
        $this->document_line = $document_line;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view($this->view_path.'.index', $this->modelVars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SupplierVoucher  $supplierVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierVoucher $supplierVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SupplierVoucher  $supplierVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierVoucher $supplierVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SupplierVoucher  $supplierVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierVoucher $supplierVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SupplierVoucher  $supplierVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierVoucher $supplierVoucher)
    {
        //
    }
}
