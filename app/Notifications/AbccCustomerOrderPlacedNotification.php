<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

// https://www.youtube.com/watch?v=EZqNu9Bqp7w&list=PLQa-BSVOzSMI89M57iXiOwvCeF0ws9Nek&index=2

use App\Mail\AbccCustomerOrderMail as Mailable;

class AbccCustomerOrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice = null)
    {
        // https://laravel.com/docs/5.5/notifications
        // https://laravel.com/docs/5.5/mail#generating-mailables
        $this->invoice = $invoice;
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
        // In addition, you may return a mailable object from the toMail method

        // return (new Mailable($this->invoice));  // ->to($this->user->email);




        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
