<?php

namespace App\Listeners;

// use App\Events\Registered;
// use Illuminate\Auth\Events\Registered;
use App\Events\CustomerRegistered;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Todo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

// https://laracasts.com/discuss/channels/laravel/laravel-events-on-registration

class NewCustomerRegistered
{
    protected $customeruser;
    protected $todo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        //

        $this->customeruser = $event->user;

        // Create Todo
        $data = [
            'name' => l('Permitir acceso al Centro de Clientes'), 
            'description' => l('Un Cliente se ha registrado en el Centro de Clientes y solicita permiso de acceso.'), 
            'url' => route('customers.edit', [$this->customeruser->id]) . '#customeruser', 
            'due_date' => null, 
            'completed' => 0, 
            'user_id' => $this->customeruser->id,
        ];

        $this->todo = Todo::create($data);



        // Send Confirmation email to Customer
        $this->handleCustomerNotification();

        // Send mail to admin
        $this->handleAdminNotification();

/*
         $data = [
           'user' => $event->user,
              'from' => 'hello@test.dev',
              'subject' => 'Welcome to test'
        ];


        $this->mailer->send('emails.auth.verify', $data, function($message) {
            $message->to($data['user']->email, $data['user']->matric)
                    ->subject($data['subject']);
        });
*/
    }

    public function handleCustomerNotification()
    {
        // Notify Customer
        // 
        $customer = $this->customeruser;


        // MAIL stuff
        try {

            $template_vars = array(
                'customer'   => $customer,
//                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => Configuration::get('ABCC_EMAIL'),         // config('mail.from.address'  ),
                'fromName' => Configuration::get('ABCC_EMAIL_NAME'),    // config('mail.from.name'    ),
                'to'       => $customer->email,         // $cinvoice->customer->address->email,
                'toName'   => $customer->name_fiscal,    // $cinvoice->customer->name_fiscal,
                'subject'  => l(' :_> Ha solicita acceso al Centro de Clientes de :company', ['company' => Context::getcontext()->company->name_fiscal]),
                );

            

            $send = Mail::send('emails.'.Context::getContext()->language->iso_code.'.customer_registration_sent', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

            // abi_r($e->getMessage());

            return false;
        }
        // MAIL stuff ENDS

        return true;

    }

    public function handleAdminNotification()
    {
        // Notify Admin
        // 
        $customer = $this->customeruser;


        // MAIL stuff
        try {

            $template_vars = array(
                'customer'       => $customer,
                'todo'        => $this->todo,
//                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => Configuration::get('ABCC_EMAIL'),         // config('mail.from.address'  ),
                'fromName' => Configuration::get('ABCC_EMAIL_NAME'),    // config('mail.from.name'    ),
                'to'       => Configuration::get('ABCC_EMAIL'),         // $cinvoice->customer->address->email,
                'toName'   => Configuration::get('ABCC_EMAIL_NAME'),    // $cinvoice->customer->name_fiscal,
                'subject'  => l(' :_> Un Cliente solicita acceso al Centro de Clientes'),
                );

            

            $send = Mail::send('emails.'.Context::getContext()->language->iso_code.'.customer_registration', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

            return false;

            return redirect()->route('abcc.orders.index')
                    ->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
                        $e->getMessage());
        }
        // MAIL stuff ENDS

        return true;

    }
}
