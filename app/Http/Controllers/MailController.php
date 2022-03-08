<?php 

namespace App\Http\Controllers;

// See: https://mattstauffer.co/blog/introducing-mailables-in-laravel-5-3/

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;

use View, Mail;

class MailController extends Controller {

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
		
		// See: https://itsolutionstuff.com/post/laravel-5-ajax-request-validation-exampleexample.html
    	$validator = Validator::make($request->all(), [

			'to_name' => 'required',
            'to_email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',

        ]);


        if ( !$validator->passes() ) {

			return response()->json(['error'=>$validator->errors()->all()]);

        }


		// ToDo: validate if send to and send from fields are defined

		$body = $request->input('message');

		if ( !stripos( $body, '<br' ) ) $body = nl2br($body);

		// See ContactMessagesController
		try{
			$send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.basic',
		        array(
		            'user_email'   => $request->input('from_email'),
		            'user_name'    => $request->input('from_name'),
		            'user_message' => $body,
		        ), function($message) use ( $request )
		    {
		        $message->from(    config('mail.from.address'  ), config('mail.from.name'    ) );
		        $message->replyTo( $request->input('from_email'), $request->input('from_name') );
		        $message->to(      $request->input('to_email'  ), $request->input('to_name')   )->subject( $request->input('subject') );
		    });
		}
		catch(\Exception $e){

		    	return response()->json(['error'=>[
		    			l('There was an error. Your message could not be sent.', [], 'layouts'),
		    			$e->getMessage()
		    	]]);
		}
		

		return response()->json(['success'=>'Email sent.']);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function storeFeedback(Request $request)
	{
		
		// See: https://itsolutionstuff.com/post/laravel-5-ajax-request-validation-exampleexample.html
    	$validator = Validator::make($request->all(), [

			'name_feedback' => 'required',
            'email_feedback' => 'required|email',
//            'subject' => 'required',
            'notes_feedback' => 'required',

        ]);


        if ( !$validator->passes() ) {

			return response()->json(['error'=>$validator->errors()->all()]);

        }


		// ToDo: validate if send to and send from fields are defined

		$subject = ' **> aBillander Feed-Back';

		$url = 'Sending feedback from: ' . url()->previous();

		$body = $request->input('notes_feedback');

		if ( !stripos( $body, '<br' ) ) $body = nl2br($body);

		// See ContactMessagesController
		try{
			$send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.feedback',
		        array(
		            'user_email'   => $request->input('email_feedback'),
		            'user_name'    => $request->input('name_feedback'),
		            'url'          => $url,
		            'user_message' => $body,
		        ), function($message) use ( $request, $subject )
		    {
		        $message->from( config('mail.from.address'), config('mail.from.name') );
//		        $message->replyTo( $request->input('from_email'), $request->input('from_name') );
		        $message->to( env('MAIL_SUPPORT_FROM_ADDRESS', config('mail.from.address')), env('MAIL_SUPPORT_FROM_NAME', config('mail.from.name')) )->subject( $subject );
		    });
		}
		catch(\Exception $e){

		    	return response()->json(['error'=>[
		    			l('There was an error. Your message could not be sent.', [], 'layouts'),
		    			$e->getMessage()
		    	]]);
		}
		

		return response()->json(['success'=>'Email sent.']);
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
