<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

// https://pineco.de/scheduling-mysql-backups-with-laravel/
// php artisan make:command BackupDatabase

// https://www.youtube.com/watch?v=RC5ipiIFE9g
// https://www.youtube.com/watch?v=C7bXmhB1Iq4&list=PLe30vg_FG4OR3b24WlxeTWsj7Z2wOtYrH&index=13

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $date = str_replace( [' ', ':'], '_', \Carbon\Carbon::now()->toDateTimeString() );

        $this->process = new Process(sprintf(
            'mysqldump -u%s -p"%s" -h%s -P%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.port'),
            config('database.connections.mysql.database'),
            storage_path('backups/backup_'.config('database.connections.mysql.database').'_'.$date.'.sql')
        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // If we run the php artisan db:backup command, we export the database and save it to the storage/backups/backup.sql file

        try {
            $this->process->mustRun();

            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            
            $this->error("The backup process has been failed.\n\n" . $exception->getMessage());
        }
    }
}
