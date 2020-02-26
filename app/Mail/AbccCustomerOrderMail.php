<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

// https://www.youtube.com/watch?v=iQoRh_9LkjU

class AbccCustomerOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;      // Change to "public" to make it (automatically) available to view

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data = [] )
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;

        return $this->view('view.name', compact('data'));
    }
}
