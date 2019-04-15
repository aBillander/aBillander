<?php

namespace App\Exceptions;

use Exception;

// php artisan make:exception StockMovementException

/*
    report() is used if you want to do some additional logging – send error to BugSnag, email, Slack etc.

    render() is used if you want to redirect back with error or return HTTP response (like your own Blade file) directly from Exception class

    Usage:         // something went wrong and you want to throw CustomException

        throw new \App\Exceptions\StockMovementException('Something Went Wrong.');
*/

class StockMovementException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
/*
    public function report()		// Exception $exception)
    {
    	// Mail to Admin
    }
*/
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
/*
    public function render($request)		// , Exception $exception)
    {
        return response()->view(
                'errors.custom',		// Exception details: <b>{{ $exception->getMessage() }}</b>
                array(
                    'exception' => $this
                )
        );
    }
*/
}
