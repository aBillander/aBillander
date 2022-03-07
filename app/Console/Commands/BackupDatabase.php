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

        // https://www.google.com/search?q=laravel+artisan+command+to+backup+database+with+simfony+Process+class
        // https://learn2torials.com/a/laravel-weekly-mysql-backup

        if (!\is_dir( \storage_path( abi_tenant_db_backups_path() ) )) {
              \mkdir( \storage_path( abi_tenant_db_backups_path() ) );
        }

        $date = str_replace( [' ', ':'], '_', \Carbon\Carbon::now()->toDateTimeString() );

        $file = storage_path( abi_tenant_db_backups_path() ) . '/backup_'.config('database.connections.mysql.database').'_'.$date.'.sql';

            try {
                // This can fail because:
                // The Process class relies on proc_open, which is not available on your PHP installation.

                $this->process new Process([
                    'mysqldump',
                    '--user='     . config('database.connections.mysql.username'),
                    '--password=' . config('database.connections.mysql.password'),
                    '--host='     . config('database.connections.mysql.host'),
                    '--port='     . config('database.connections.mysql.port'),

                    config('database.connections.mysql.database'),

                    '--result-file=' . $file,
                ]);
            }
            catch (\Exception $e) {
                // $this->error("The Process class can not be instantiated.\n\n" . $e->getMessage());
            }
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

            $this->info(l('The database backup has been proceed successfully.', 'layouts'));
        } catch (ProcessFailedException $exception) {
            
            $this->error(l('Error: The database backup process has been failed.', 'layouts')."\n\n" . $exception->getMessage());
        }
          catch (\Throwable $exception) {
            $this->error(l('Error: The database backup process has been failed.', 'layouts')."\n\n" .  $exception->getMessage());
        }
    }
}
