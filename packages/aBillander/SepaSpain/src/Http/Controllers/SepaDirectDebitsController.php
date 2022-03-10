<?php

namespace aBillander\SepaSpain\Http\Controllers;

use AbcAeffchen\SepaUtilities\SepaUtilities;
use AbcAeffchen\Sephpa\SephpaDirectDebit;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Sequence;
use App\Traits\DateFormFormatterTrait;
use App\Traits\ViewFormatterTrait;
use Illuminate\Http\Request;
use aBillander\SepaSpain\SepaDirectDebit;

class SepaDirectDebitsController extends Controller
{
    
    use ViewFormatterTrait;
    use DateFormFormatterTrait;


   protected $directdebit;
   protected $payment;

   public function __construct(SepaDirectDebit $directdebit, Payment $payment)
   {
        $this->directdebit = $directdebit;
        $this->payment = $payment;
   }

    /**
     * Display a listing of the resource.
	 * GET /fsx_loggers
     *
     * @return Response
     */

    public function index()
    {
        $sdds = $this->directdebit->with('bankaccount')->orderBy('document_date', 'desc')->orderBy('id', 'desc');

        $sdds = $sdds->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $sdds->setPath('directdebits');

        return view('sepa_es::direct_debits.index', compact('sdds'));
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /fsx_loggers/create
	 *
	 * @return Response
	 */
	public function create()
	{

        $sequenceList = $this->directdebit->sequenceList();

        if ( !(count($sequenceList)>0) )
            return redirect()->route('sepasp.directdebits.index')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));

		$sepa_sp_schemeList = SepaDirectDebit::getSchemeList();

		$bank_accountList = Context::getContext()->company->bankaccounts->pluck('bank_name', 'id')->toArray();

        // $document_date = abi_date_short( \Carbon\Carbon::now() );

		return view('sepa_es::direct_debits.create', compact('sepa_sp_schemeList', 'bank_accountList', 'sequenceList'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /fsx_loggers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'date_from', 'date_to', 'document_due_date'], $request );

        $rules = $this->directdebit::$rules;

        $this->validate($request, $rules);

        $extradata = [  'user_id'              => Context::getContext()->user->id,

 //                       'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),

                        'created_via'          => 'manual',
                        'status'               => 'pending',
                        'total'               => 0.0,
                     ];

        $request->merge( $extradata );


        $date_from = $request->input('date_from', '');
        $date_to   = $request->input('date_to', '');

        if ( $request->input('autocustomer_name', '') )
            $customer_id = $request->input('customer_id', '');
        else
            $customer_id = '';

        $document_due_date = $request->input('document_due_date', '');
        
        // Lets see:
        $vouchers =  $this->payment
                    ->where('payment_type', 'receivable')
                    ->where('status', 'pending')
                    ->where('amount', '<>', 0.0)
                    ->whereHas('customer', function ($query) use ($customer_id) {

                            if ( (int) $customer_id > 0 )
                                $query->where('id', $customer_id);
                    })
                    ->when($date_from, function($query) use ($date_from) {

                            $query->where('due_date', '>=', $date_from);
                    })
                    ->when($date_to, function($query) use ($date_to) {

                            $query->where('due_date', '<=', $date_to);
                    })
                    ->where('auto_direct_debit', '>', 0)
                    ->doesntHave('bankorder')
                    ->get();

// abi_r($vouchers);die();

        if ( $vouchers->count() == 0 )
        {
            return redirect()->route('sepasp.directdebits.index')
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . ' :: ' .  l('No records selected. ', 'layouts') . $request->input('date_from_form', '') . ' -> ' . $request->input('date_to_form', '') . ' :: ' . $request->input('autocustomer_name'));
        }



        // create Direct Debit
        $sdds = $this->directdebit->create($request->all());

        // Sequence
        $seq_id = $sdds->sequence_id;
        $seq = Sequence::find( $seq_id );
        $doc_id = $seq->getNextDocumentId();

//        $sdds->sequence_id = $seq_id;
        // Not fillable
        $sdds->document_prefix    = $seq->prefix;
        // Not fillable
        $sdds->document_id        = $doc_id;
        // Not fillable. May come from external system ???
        $sdds->document_reference = $seq->getDocumentReference($doc_id);

//        $sdds->validation_date = \Carbon\Carbon::now();

        // Bank Account
        $bankaccount = $sdds->bankaccount;
        $sdds->iban  = $bankaccount->iban;
        $sdds->swift = $bankaccount->swift;

        // Currency
        $currency = $bankaccount->currency ?: Currency::find( intval(Configuration::get('DEF_CURRENCY')) );
        $sdds->currency_iso_code = $currency->iso_code;
        $sdds->currency_conversion_rate = $currency->conversion_rate;

        $sdds->save();


/*
        foreach ($vouchers as $voucher) {
            # code...
            // $voucher->update(['bank_order_id' => $sdds->id]);
            $sdds->vouchers()->save($voucher);
        }
*/
        // Need to set voucher due date?
        if ( $document_due_date )
        {
                $vouchers->each(function ($item, $key) use ($document_due_date) {
                    $item->due_date = $document_due_date;
                });
        }

        // Do add vouchers, now!
        $sdds->vouchers()->saveMany($vouchers);

        // Update bankorder
        $sdds->updateTotal();


        return redirect()->route('sepasp.directdebits.show', $sdds->id)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $sdds->id], 'layouts') . ' :: ' . $vouchers->count() . ' ' . l('voucher(s)'));


        return redirect()->route('sepasp.directdebits.edit', $sdds->id)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $sdds->id], 'layouts') . ' :: ' . $vouchers->count() . ' ' . l('voucher(s)'));
    }

	/**
	 * Display the specified resource.
	 * GET /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $directdebit = $this->directdebit->findOrFail($id);

        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('sepa_es::direct_debits.show', compact('directdebit', 'payment_typeList'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /fsx_loggers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $directdebit = $this->directdebit->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['document_date'], $directdebit );

        $sepa_sp_schemeList = SepaDirectDebit::getSchemeList();

        $bank_accountList = Context::getContext()->company->bankaccounts->pluck('bank_name', 'id')->toArray();

        // $document_date = abi_date_short( \Carbon\Carbon::now() );

        // return view('sepa_es::direct_debits.edit', compact('directdebit', 'sepa_sp_schemeList', 'bank_accountList', 'sequenceList'));
        return view('sepa_es::direct_debits.edit', compact('directdebit', 'sepa_sp_schemeList', 'bank_accountList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['document_date'], $request );

        $directdebit = $this->directdebit->findOrFail($id);

        $rules = $this->directdebit::$rules;

        $this->validate($request, $rules);

        $directdebit->update($request->all());

        return redirect()->route('sepasp.directdebits.show', $id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $directdebit->document_reference);
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /fsx_loggers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $directdebit = $this->directdebit->findOrFail($id);

        if ( $directdebit->nbrItems() > 0 )
            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because its Quantity or Value &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        $directdebit->delete();

        return redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    /**
     * 
     *
     * @param  int  $id
     * @return XML file
     */
    public function exportXml($id)
    {
        // return 'OK - '.$cod;

        $directdebit = $this->directdebit->with('vouchers')->findOrFail($id);

        if ( $directdebit->nbrItems() == 0 )
            return redirect()->back()
                    ->with('error', l('Unable to create XML File &#58&#58 (:id) ', ['id' => $id]) . l('Document has no Lines'));

        
        $directDebitFile = $directdebit->toXML();

        if ( ! $directDebitFile )
            return redirect()->back()
                    ->with('error', l('Unable to create XML File &#58&#58 (:id) ', ['id' => $id]).' :: '.$directdebit->getErrorMessage());


        $directdebit->confirm();

        // return $directDebitFile->download();
        $creDtTm  = $directdebit->document_date->format('Y-m-d\TH:i:s');
        $filename = 'Remesa_' . $directdebit->document_reference . '_' . $creDtTm . '_SEPA_' .$directdebit->scheme . '' . '.xml';
        $creId    = $directdebit->calculateCreditorID( Context::getContext()->company, $directdebit->bankaccount );

        // Play nice with barryvdh/laravel-DebugBar
        // https://github.com/barryvdh/laravel-debugbar/issues/621
        return $directDebitFile->downloadSepaFile( $filename, $creDtTm, $creId );

        return redirect()->route('sepasp.directdebits.show', $id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $directdebit->document_reference);
    }


    public function addVoucherForm($id, Request $request)
    {
        $voucher = $this->payment->findOrFail($id);

        $sdds = $this->directdebit->with('bankaccount')
                                ->where('status', 'pending')->orWhere('status', 'confirmed')
                                ->orderBy('document_date', 'desc')->orderBy('id', 'desc')
                                ->get();

        return view('sepa_es::customer_vouchers._form_add_voucher', compact('voucher', 'sdds'));
    }


    public function addVoucher(Request $request)
    {
        $voucher_id = (int) $request->input('voucher_id');
        $sdd_id     = (int) $request->input('sdd_id');

/*
        Needed for dropdown form in Voucher Index

        $array = $request->input('voucher_id');
        $voucher_id = (int) array_shift($array);
        $array = $request->input('sdd_id');
        $sdd_id     = (int) array_shift($array);
*/        

        $voucher = $this->payment->findOrFail($voucher_id);

        $sdd = $this->directdebit->findOrFail($sdd_id);

        // Do add vouchers, now!
        $sdd->vouchers()->save($voucher);

        // Update bankorder
        $sdd->updateTotal();


        

        if($request->ajax()){

            return response()->json( [
                    'msg' => 'OK',
                    'sdd' => $sdd->id,
                    'voucher' => $voucher->id,
            ] );

        }

        return redirect()->back()
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $voucher_id], 'layouts') );
    }
}