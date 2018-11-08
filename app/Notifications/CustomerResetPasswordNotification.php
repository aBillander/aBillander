<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
//                    ->view('auth.passwords.customer_reset_email')
                    ->markdown('auth.passwords.customer_reset_email')      // From: resources/views/auth/passwords (See: https://laravel.com/docs/5.5/passwords#resetting-views)
                    // See: https://thewebtier.com/laravel/modify-password-reset-email-text-laravel/
//                    ->from('info@example.com')
                    ->subject( 'Recuperar la contraseña ['.config('app.name').']' )
                    ->line('Recibe este email porque hemos recibido una solicitud para recuperar la contraseña de su Cuenta.')
                    ->action('Recuperar la Contraseña', route('customer.password.reset', $this->token))
 //                   ->attach('reset.attachment')
                    ->line('Si no solicitó recuperar su contraseña, no necesita hacer nada más.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
