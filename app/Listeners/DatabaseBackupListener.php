<?php

namespace App\Listeners;

use App\Events\DatabaseBackup;
use App\Models\Configuration;
use App\Models\Context;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Mail;

class DatabaseBackupListener
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
     * @param  DatabaseBackup  $event
     * @return void
     */
    public function handle(DatabaseBackup $event)
    {
        // Do chores...

        // Check max backups
        $bk_folder = storage_path( abi_tenant_db_backups_path() );

        $listing =  
            \Arr::sort(File::files( $bk_folder ), function($file)
                {
                    return $file->getMTime();
                })
        ;

        // abi_r( $listing );

        $extra_backups = count($listing) - Configuration::getInt('MAX_DB_BACKUPS');

        if ( $extra_backups > 0 )
        {
            // Let's see what to do:
            switch ( Configuration::get('MAX_DB_BACKUPS_ACTION') ) {
                case 'delete':
                    # code...
                    // Delete oldest backups (first in $listing)
                    $i=0;
                    foreach ($listing as $line) {       // Quick & dirty!
                        # code...
                        $file = $bk_folder.'/'.$line->getFilename();

                        if(is_file($file))
                        {
                            // die($file);
                            unlink( $file );
                        }

                        $i++;
                        if ( $i >= $extra_backups ) break;
                    }
                    break;
                
                case 'email':
                    # code...



        // MAIL stuff
        try {

            $template_vars = array(
//              'company'       => $company,
//              'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => abi_mail_from_address(),          // config('mail.from.address'  ),
                'fromName' => abi_mail_from_name(),             // config('mail.from.name'    ),
                'to'       => abi_mail_from_address(),          // $cinvoice->customer->address->email,
                'toName'   => abi_mail_from_name(),             // $cinvoice->customer->name_fiscal,
                'subject'  => ' :_> [aBillander] ' . l('Maximum number of Database Backups reached'),
                );

            

            $send = Mail::send('emails.'.Context::getContext()->language->iso_code.'.dbbackup_warning', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

            // Die silently...

            // abi_r($e->getMessage(), true);

        }
        // MAIL stuff ENDS








                    break;
                
                case '':
                case 'nothing':
                    # code...
                    // break;
                
                default:
                    # code...
                    break;
            }
        }



        // MAIL notification
        if ( Configuration::isTrue('DB_EMAIL_NOTIFY') )
        try {

            $template_vars = [
                'status'  => $event->status,
                'message' => $event->message,
            ];

            $data = array(
                'from'     => abi_mail_from_address(),          // config('mail.from.address'  ),
                'fromName' => abi_mail_from_name(),             // config('mail.from.name'    ),
                'to'       => abi_mail_from_address(),          // $cinvoice->customer->address->email,
                'toName'   => abi_mail_from_name(),             // $cinvoice->customer->name_fiscal,
                'subject'  => ' :_> [aBillander] ' . l('Data Base Backup notification'),
                );

            

            $send = Mail::send('emails.'.Context::getContext()->language->iso_code.'.dbbackup_notification', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

            // Die silently...

            // abi_r($e->getMessage(), true);

        }
        // MAIL notification ENDS
    }
}

/*

php artisan make:event DatabaseBackup

php artisan make:listener DatabaseBackupListener --event="DatabaseBackup"

https://www.phpflow.com/php/event-and-listeners-example-using-laravel-5-6/

https://artisansweb.net/how-to-use-laravel-events-and-listeners-in-your-application/

https://laravel-news.com/laravel-model-events-getting-started

*/
