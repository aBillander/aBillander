<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\CustomerInvoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use View;

class PdfController extends Controller {


	// Where used? Unknown :(
	public function show($id)
	{
		try {
			$cinvoice = CustomerInvoice::
							  with('customer')
							->with('invoicingAddress')
							->with('customerInvoiceLines')
							->with('currency')
							->with('template')
							->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('404')
                     ->with('error', 'La Factura de Cliente id='.$id.' no existe.');
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

		$company = Company::find( intval(Configuration::get('DEF_COMPANY')) );

		$template = 'customer_invoices.templates.' . $cinvoice->template->file_name;
		$paper = $cinvoice->template->paper;	// A4, letter
		$orientation = $cinvoice->template->orientation;	// 'portrait' or 'landscape'.
		
		$pdf 		= PDF::loadView( $template, compact('cinvoice', 'company') )
							->setPaper( $paper )
							->setOrientation( $orientation );

		$pdfName	= 'invoice_' . $cinvoice->secure_key . '_' . date('Y-m-d');

		if (Request::has('screen')) return View::make($template, compact('cinvoice', 'company'));
		
		return 	$pdf->download( $pdfName . '.pdf');
	}	

}