<?php

namespace App\Listeners;

use App\Models\EmailLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

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
     * Handle the actual logging.
     *
     * @param  MessageSent $event
     * @return void
     */
    public function handle( MessageSent $event )
    {
        // maybe we are testing email when installing aBillander (tables are NOT set):
        if ( ! \aBillander\Installer\Helpers\Installer::alreadyInstalled() )
            return ;

        $message = $event->message;

        // See: https://github.com/shvetsgroup/laravel-email-database-log/blob/master/src/ShvetsGroup/LaravelEmailDatabaseLog/EmailLogger.php


        $emaillog = EmailLog::create([
            'from' => $this->formatAddressField($message, 'From'),
            'to' => $this->formatAddressField($message, 'To'),
            'cc' => $this->formatAddressField($message, 'Cc'),
            'bcc' => $this->formatAddressField($message, 'Bcc'),
            'subject' => $message->getSubject(),
            'body' => $message->getBody()->bodyToString(),
            'headers' => $message->getHeaders()->toString(),
            'attachments' => $this->saveAttachments($message),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),

 //           'userable_id' => \Auth::id(),
        ]);

        if ( \Auth::user() )
        {
            \Auth::user()->emaillogs()->save($emaillog);

        } else {
            //
            $emaillog->userable_id = 0;
            $emaillog->userable_type = User::class;
            $emaillog->save();
        }
    }

    /**
     * Format address strings for sender, to, cc, bcc.
     *
     * @param Email $message
     * @param string $field
     * @return null|string
     */
    function formatAddressField(Email $message, string $field): ?string
    {
        $headers = $message->getHeaders();

        return $headers->get($field)?->getBodyAsString();
    }


    /**
     * Collect all attachments and format them as strings.
     *
     * @param Email $message
     * @return string|null
     */
    protected function saveAttachments(Email $message): ?string
    {
        if (empty($message->getAttachments())) {
            return null;
        }

        return collect($message->getAttachments())
            ->map(fn(DataPart $part) => $part->toString())
            ->implode("\n\n");
    }

}
