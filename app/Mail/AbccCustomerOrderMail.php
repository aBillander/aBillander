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
    private $template_vars;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $data = [], $template_vars = [] )
    {
        $this->data          = $data;
        $this->template_vars = $template_vars;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data          = $this->data;
        $template_vars = $this->template_vars;

        return $this->from( $data['from'], $data['fromName'] )
//                    ->bcc( $data['from'] )
                    ->subject( $data['subject'] )
                    ->view('emails.'.$data['iso_code'].'.abcc.new_customer_order')
                    ->with( $template_vars + $data );
    }
}
