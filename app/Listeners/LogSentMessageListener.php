<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;
use App\EmailLog;

class LogSentMessageListener
{
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
    public function handle( MessageSent $event )
    {
        $message = $event->message;

        // Attachments
        $attachments = [];
        foreach ($message->getChildren() as $child) {
            $attachments[] = $child->getFilename();
        }

        $emaillog = EmailLog::create([
            'from' => $this->formatAddressField($message, 'From'),
            'to' => $this->formatAddressField($message, 'To'),
            'cc' => $this->formatAddressField($message, 'Cc'),
            'bcc' => $this->formatAddressField($message, 'Bcc'),
            'subject' => $message->getSubject(),
            'body' => $message->getBody(),
            'headers' => (string)$message->getHeaders(),
            'attachments' => count( $attachments ) ? implode("\n\n", $attachments) : null,
            'created_at' => Carbon::now(),

 //           'userable_id' => \Auth::id(),
        ]);

        \Auth::user()->emaillogs()->save($emaillog);
    }
    /**
     * Format address strings for sender, to, cc, bcc.
     *
     * @param $message
     * @param $field
     * @return null|string
     */
    function formatAddressField($message, $field)
    {
        $headers = $message->getHeaders();
        if (!$headers->has($field)) {
            return null;
        }
        $mailboxes = $headers->get($field)->getFieldBodyModel();
        $strings = [];
        foreach ($mailboxes as $email => $name) {
            $mailboxStr = $email;
            if (null !== $name) {
                $mailboxStr = $name . ' <' . $mailboxStr . '>';
            }
            $strings[] = $mailboxStr;
        }
        return implode(', ', $strings);
    }

}
