<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CustomerUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class CustomerPasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.passwords.customer_forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:customer_users,email'],
        ]);

        // Delete expired tokens
        \Artisan::call('auth:clear-resets');

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $this->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    /**
     * Mimic Password::sendResetLink()
     * 
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @param  \Closure|null  $callback
     * @return string
     */
    public function sendResetLink(array $credentials, Closure $callback = null)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = CustomerUser::where('email', $credentials['email'])->first();

        if (is_null($user)) {
            return Password::INVALID_USER;
        }

//        if ($this->tokens->recentlyCreatedToken($user)) {
//            return Password::RESET_THROTTLED;
//        }

        // Create token
        // $token = $this->tokens->create($user);
        $token = \Str::random(64);
        \DB::table('password_resets')->insert([
                 'email'=>$credentials['email'],
                 'token'=>$token,
                 'model_name' => CustomerUser::class,
                 'created_at'=>Carbon::now(),
        ]);

        $action_link = route('customer.password.reset',['token'=>$token]);

        if ($callback) {
            $callback($user, $token);
        } else {
            // Once we have the reset token, we are ready to send the message out to this
            // user with a link to reset their password. We will then redirect back to
            // the current URI having nothing set in the session to indicate errors.
            $user->sendPasswordResetNotification($token);
        }

        return Password::RESET_LINK_SENT;
    }
}
