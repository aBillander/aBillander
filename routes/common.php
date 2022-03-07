<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Unclassifiable routes
|
*/


// https://www.youtube.com/watch?v=Vb7G1Q2g66g&t=1931s
Route::get('clear-cache', function()
{
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    // If working with File Storage
    // Artisan::call('storage:link');
    // Or create simlink manually

    // php artisan clear-compiled       // https://stillat.com/blog/2016/12/07/laravel-artisan-general-command-the-clear-compiled-command
    // composer dump-autoload

    return redirect()->back()->with('success', l('Cache has been cleared &#58&#58 (:cache) ', ['cache' => 'cache:clear, config:clear, route:clear, view:clear'], 'layouts'));
});


Route::get('dbbackup', function()
{
    $security_token = request('security_token'); 

    if ( $security_token != \App\Configuration::isNotEmpty('DB_BACKUP_SECURITY_TOKEN') )
        die($security_token);


            if ( config('tenants.enable') )
            {
                // Extract the subdomain from URL.
                list($subdomain) = explode('.', request()->getHost(), 2);
    
                $tenants = config('tenants.names');
    
                // abi_r($tenants, true);
    
                // die(in_array($subdomain, $tenants));
                if ( !in_array($subdomain, $tenants) )
                {
                    // Logout user
                   die('Access denied');
                }
            
            } else {

                $subdomain = 'localhost';
            
            }

    \App\Context::getContext()->tenant = $subdomain; 

    Artisan::call('db:backup');
    // save it to the storage/backups/backup.sql file

    abi_r( Artisan::output() );

        // The backup has been proceed successfully.
        event(new \App\Events\DatabaseBackup());

    // return '<h1>Proceso terminado</h1>';    // <a class="navbar-brand" href="' .url('/'). '">Volver</a>';
    return ;
});


Route::get('eggtimer', function()
{
    $seconds = 1;

    $limit = max((int) ini_get('max_execution_time'), 60) + 10;

        // Start Logger
        $logger = \App\ActivityLogger::setup( 'Egg Timmer', 'Guess max_execution_time' );        // 'Import Categories :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $logger->log("INFO", "PHP says: ini_get('max_execution_time') = ".(int) ini_get('max_execution_time').' secs.');

        $i=0;

        while ( true ) {
            # code...

            $i++;

            // sleep( $seconds );
            usleep((int)($seconds * 1000000));

            $logger->log("INFO", "Round: ".(int) $i.' => '.(int) $i*$seconds.' secs.');

            if ($i*$seconds>$limit) break;
        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Egg Timmer :: Guess max_execution_time.'));
});


/* ********************************************************** */


// In case Laravel storage link won't work on production
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});


// Show error page
Route::get('error/{err}', function(Request $request, $err)
{
    return view('errors.' . $err);
});


/* ********************************************************** */


// Disable Registration Routes...
// See:
// https://stackoverflow.com/questions/29183348/how-to-disable-registration-new-user-in-laravel-5
// https://stackoverflow.com/questions/42695917/laravel-5-4-disable-register-route/42700000
if ( !env('ALLOW_USER_REGISTRATION', false) )
{
    Route::get('register', function()
        {
            return view('errors.404');
        })->name('register');

    Route::post('register', function()
        {
            return view('errors.404');
        });
}


/* ********************************************************** */






/* ********************************************************** */






/* ********************************************************** */



