<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\ContactMessage;
use App\Models\Context;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

		$to_email = Configuration::get('ABCC_EMAIL')      ?: config('mail.from.address');
		$to_name  = Configuration::get('ABCC_EMAIL_NAME') ?: config('mail.from.name'   );

		$from_email = $to_email;	// Not allowed by server: $request->input('email') ? $request->input('email') : $to_email ;
		$from_name  = $request->input('name' ) ? $request->input('name' ) : $to_name  ;
		// dd($from_email);

		try{

			$template_vars = [
	            'user_message' => $body,
	            'customer' => $customer,
	            'customer_user' => Auth::user(),
	    		];

			$send = Mail::send('emails.'.Context::getContext()->language->iso_code.'.abcc.new_customer_message',
		        $template_vars, function($message) use ( $to_email, $to_name, $from_email, $from_name )
		    {
		        $message->from( $from_email, $from_name );			// Pone en email from la dirección de GMail desde la que se envía
		        $message->sender( $from_email, $from_name );		// ?
		        $message->replyTo( $from_email, $from_name );		// Si funciona
		        $message->to(   $to_email  , $to_name   )->subject( l(' :_> New Customer Message [Customer Center]') );
		    });
		}
		catch( \Symfony\Component\Mailer\Exception\TransportException $e){
		    	return response( [
		    		'status' => 'ERROR',
		    		'message' => $e->getMessage(),
		    	] );
		}

		return response( [
		    		'status' => 'OK',
		    		'message' => '',
		    	] );
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
