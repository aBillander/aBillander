<?php 

namespace App\Http\Controllers\CustomerCenter;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ContactMessage as ContactMessage;
use View, Mail;

class AbccContactMessagesController extends Controller {


   protected $comment;

   public function __construct(ContactMessage $comment)
   {
        $this->comment = $comment;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $customer_user = Auth::user();
        $customer      = optional(Auth::user())->customer;

        if (!$customer) return 'ERROR';

		// Poor Man validation
		// $this->validate($request, ContactMessage::$rules);
		// if ($validation->fails())
		$body = $request->input('notes');
		if ( !$body )
		{
			$return = '';
			// foreach ($validation->errors()->all() as $err) {
			$return .= '<li class="error">' . l('The Comments field is required.', [], 'layouts') . '</li>';
			// }
			return $return;
		}

		// return 1;

		// $comment = $this->comment->create($request->all());

		// ToDo: validate if send to and send from fields are defined

		if ( !stripos( $body, '<br' ) ) $body = nl2br($body);

		$to_email = \App\Configuration::get('ABCC_EMAIL');
		$to_name  = \App\Configuration::get('ABCC_EMAIL_NAME');

		$from_email = $request->input('email') ? $request->input('email') : $to_email ;
		$from_name  = $request->input('name' ) ? $request->input('name' ) : $to_name  ;
		// dd($from_email);

		try{

			$template_vars = [
	            'user_message' => $body,
	            'customer' => $customer,
	    		];

			$send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.abcc.new_customer_message',
		        $template_vars, function($message) use ( $to_email, $to_name, $from_email, $from_name )
		    {
		        $message->from( $from_email, $from_name );			// Pone en email from la dirección de GMail desde la que se envía
		        $message->sender( $from_email, $from_name );		// ?
		        $message->replyTo( $from_email, $from_name );		// Si funciona
		        $message->to(   $to_email  , $to_name   )->subject( l(' :_> New Customer Message [Customer Center]') );
		    });
		}
		catch(Exception $e){
		    	return 'ERROR';
		}

		return 'OK';
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
