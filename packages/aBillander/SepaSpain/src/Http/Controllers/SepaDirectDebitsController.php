<?php

namespace aBillander\SepaSpain\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Configuration;
use App\Sequence;
use App\Currency;
use App\Payment;

use App\Traits\ViewFormatterTrait;
use App\Traits\DateFormFormatterTrait;

use aBillander\SepaSpain\SepaDirectDebit;

use AbcAeffchen\SepaUtilities\SepaUtilities;
use AbcAeffchen\Sephpa\SephpaDirectDebit;

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

        $sdds = $sdds->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $sdds->setPath('sepasp/directdebits');

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

		$bank_accountList = \App\Context::getContext()->company->bankaccounts->pluck('bank_name', 'id')->toArray();

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
        $this->mergeFormDates( ['document_date', 'date_from', 'date_to'], $request );

        $rules = $this->directdebit::$rules;

        $this->validate($request, $rules);

        $extradata = [  'user_id'              => \App\Context::getContext()->user->id,

 //                       'sequence_id'          => $request->input('sequence_id') ?? Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_SEQUENCE'),

                        'created_via'          => 'manual',
                        'status'               => 'pending',
                        'total'               => 0.0,
                     ];

        $request->merge( $extradata );


        $date_from = $request->input('date_from', '');
        $date_to   = $request->input('date_to', '');
        
        // Lets see:
        $vouchers =  $this->payment
                    ->when($date_from, function($query) use ($date_from) {

                            $query->where('due_date', '>=', $date_from);
                    })
                    ->when($date_to, function($query) use ($date_to) {

                            $query->where('due_date', '<=', $date_to);
                    })
                    ->where('auto_direct_debit', '>', 0)
                    ->doesnthave('bankorder')
                    ->get();

// abi_r($vouchers);die();

        if ( $vouchers->count() == 0 )
        {
            return redirect()->route('sepasp.directdebits.index')
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . ' :: ' .  l('No records selected. ', 'layouts') . $request->input('date_from_form', '') . ' -> ' . $request->input('date_to_form', ''));
        }



        // create Direct Debit
        $sdds = $this->directdebit->create($request->all());

        // Sequence
        $seq_id = $sdds->sequence_id;
        $seq = \App\Sequence::find( $seq_id );
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

        return view('sepa_es::direct_debits.show', compact('directdebit'));
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

        $bank_accountList = \App\Context::getContext()->company->bankaccounts->pluck('bank_name', 'id')->toArray();

        // $document_date = abi_date_short( \Carbon\Carbon::now() );

        return view('sepa_es::direct_debits.edit', compact('directdebit', 'sepa_sp_schemeList', 'bank_accountList', 'sequenceList'));
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
                    ->with('error', l('Unable to create XML File &#58&#58 (:id) ', ['id' => $id]));


        $directdebit->confirm();

        return $directDebitFile->download();

        return redirect()->route('sepasp.directdebits.show', $id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $directdebit->document_reference);
    }

}