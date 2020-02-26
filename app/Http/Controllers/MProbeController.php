<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class MProbeController extends Controller
{
    
    public function send()
    {
    	$data = ['message' => 'Hello World!'];

    	Mail::to('onagrosan@gmail.com')->queue( new ContactFormMail( $data ) );
    }

    public function queue()
    {
    	\Artisan::call('queue:work');
    }
}
