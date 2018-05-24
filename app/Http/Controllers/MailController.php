<?php 

namespace App\Http\Controllers;

// See: https://mattstauffer.co/blog/introducing-mailables-in-laravel-5-3/

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

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
		// Poor Man validation
		// $this->validate($request, ContactMessage::$rules);
		// if ($validation->fails())
		$body = $request->input('message');
		if ( !$body )
		{
			$return = '';
			// foreach ($validation->errors()->all() as $err) {
			$return .= '<li class="error">' . l('The Comments field is required.', [], 'layouts') . '</li>';
			// }
			return $return;
		}

		// return 1;

		// ToDo: validate if send to and send from fields are defined

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
		        $message->from(    $request->input('from_email'), $request->input('from_name') );
		        $message->replyTo( $request->input('from_email'), $request->input('from_name') );
		        $message->to(      $request->input('to_email'  ), $request->input('to_name')   )->subject( $request->input('subject') );
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
