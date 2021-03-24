<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

    use Request;
    use Illuminate\Auth\AuthenticationException;
    use Response;

// https://stackoverflow.com/questions/30276325/laravel-5-how-do-i-handle-methodnotallowedhttpexception
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) 
        {
            return redirect('404')->with('info', 'Method is not allowed for the requested route');
/*
            return response()->json( [
                                        'success' => 0,
                                        'message' => 'Method is not allowed for the requested route',
                                    ], 405 );
*/        
        }

        return parent::render($request, $exception);
    }

    /**
     * See:
     *
     * https://stackoverflow.com/questions/45340855/laravel-5-5-change-unauthenticated-login-redirect-url
     */
    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guard = \Arr::get($exception->guards(), 0);

        switch ($guard) {
          case 'customer':
            $login = 'customer.login';
            break;
          case 'salesrep':
            $login = 'salesrep.login';
            break;
          default:
            $login = 'login';
            break;
        }

        return redirect()->guest(route($login));
    }
}
