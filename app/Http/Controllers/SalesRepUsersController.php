<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\SalesRep;
use App\SalesRepUser;
use App\Configuration;
use App\Language;

use Mail;

class SalesRepUsersController extends Controller
{
    //

   protected $salesrepuser, $salesrep;

   public function __construct(SalesRepUser $salesrepuser, SalesRep $salesrep)
   {
        $this->salesrepuser = $salesrepuser;
        $this->salesrep = $salesrep;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//		$users = $this->user->with('language')->orderBy('id', 'ASC')->get();

//		return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
        
//        return view('customer_orders.create', compact('sequenceList'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $section = '#salesrepuser';
        $salesrep_id = $request->input('salesrep_id');

        $salesrep = $this->salesrep->find($salesrep_id);


        $validator = Validator::make($request->all(), SalesRepUser::$rules);

    

        if ( !$validator->passes() ) {

            return response()->json(['error'=>$validator->errors()->all()]);

        }

        // Do move on!
        
        $password = \Hash::make($request->input('password'));
        $request->merge( ['password' => $password] );

        // $request->merge( ['language_id' => Configuration::get('DEF_LANGUAGE')] );

        $salesrepuser = $this->salesrepuser->create($request->all());

        // Notify SalesRep
        // 
        // $salesrep = $this->customeruser->salesrep;


        // MAIL stuff
        if ( $request->input('notify_salesrep', 0) )
        try {

            $template_vars = array(
                'salesrep'   => $salesrep,
//                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => config('mail.from.address'),  // \App\Configuration::get('ABCC_EMAIL'),
                'fromName' => config('mail.from.name'   ),  // \App\Configuration::get('ABCC_EMAIL_NAME'),
                'to'       => $salesrepuser->email,         // $cinvoice->customer->address->email,
                'toName'   => $salesrepuser->getFullName(),    // $cinvoice->customer->name_fiscal,
                'subject'  => l(' :_> Confirmación de acceso al Centro de Agentes de :company', ['company' => \App\Context::getcontext()->company->name_fiscal]),
                );

            

            $send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.absrc_invitation_confirmation', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

            // abi_r($e->getMessage());
            return response()->json([ 'error' => $e->getMessage() ]);

/*            return redirect()->route('abcc.orders.index')
                    ->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
                        $e->getMessage());
*/
            // return false;
        }
        // MAIL stuff ENDS

        if($request->ajax()){

            return response()->json( [
                'success' => 'OK',
                'msg' => 'OK',
                'data' => $salesrepuser->toArray()
            ] );

        }



		return redirect(route('salesreps.edit', $salesrep_id) . $section)
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $salesrepuser->id], 'layouts') . $salesrepuser->getFullName());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function show(SalesRepUser $customeruser)
    {
//        return $this->edit( $customeruser );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesRepUser $customeruser)
    {
//        return view('customer_orders.edit', compact('customeruser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesRepUser $salesrepuser)
    {
        $section = '#salesrepuser';
        $salesrep_id = $request->input('salesrep_id');

        $vrules = SalesRepUser::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $salesrepuser->id.',id';  // Unique

		if ( $request->input('password') != '' ) {
			$this->validate( $request, $vrules );

			$password = \Hash::make($request->input('password'));
			$request->merge( ['password' => $password] );
			$salesrepuser->update($request->all());
		} else {
			$this->validate($request, Arr::except( $vrules, array('password')) );
			$salesrepuser->update($request->except(['password']));
		}


        if($request->ajax()){

            return response()->json( [
                'success' => 'OK',
                'msg' => 'OK',
                'data' => $salesrepuser->toArray()
            ] );

        }

        return redirect(route('salesreps.edit', $salesrep_id) . $section)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $salesrepuser->id], 'layouts') . $salesrepuser->getFullName());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesRepUser $salesrepuser)
    {
        $section = '#salesrepuser';
        $name = $salesrepuser->getFullName();

        $salesrepuser->delete();

        return redirect(url()->previous() . $section) // redirect()->to( url()->previous() . $section )
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $name], 'layouts'));
    }



    /**
     * Extra Stuff.
     *
     * 
     */  


    public function impersonate($id)
    {
        
        \Auth()->guard('salesrep')->loginUsingId($id);

        return redirect()->route('salesrep.dashboard');
    }


    public function getUser($id, Request $request)
    {
        $salesrep_user = $this->salesrepuser->findOrFail($id);

        return response()->json( ['user' => $salesrep_user] );
    }
}
