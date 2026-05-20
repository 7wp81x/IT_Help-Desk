<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('welcome')->with('showForgotModal', true);
    }

    /**
     * Override to prevent reset links for unverified email addresses and restrict by role.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'confirm' => 'nullable|in:1',
        ], [
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot be longer than 255 characters.',
            'phone.max' => 'Phone number cannot be longer than 50 characters.',
        ]);

        if (!$request->filled('email') && !$request->filled('phone')) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Please provide either your email address or phone number.'], 'forgot')
                ->with('showForgotModal', true);
        }

        $user = null;
        $method = 'mail';

        if ($request->filled('email')) {
            $user = User::where('email', $request->email)->first();
            $method = 'mail';
        } elseif ($request->filled('phone')) {
            $phone = User::normalizePhilippinesPhone($request->phone);
            $user = $phone ? User::where('phone', $phone)->first() : null;
            $method = 'sms';
        }

        $notFoundMessage = 'Sorry, we can’t find your account with that email or phone number.';

        if (!$user || ($method === 'mail' && !$user->email_verified_at)) {
            return back()
                ->withInput()
                ->withErrors(['email' => $notFoundMessage], 'forgot')
                ->with('showForgotModal', true);
        }

        if (!$request->filled('confirm')) {
            return back()
                ->withInput()
                ->with([
                    'showForgotModal' => true,
                    'forgotStep' => 'verify',
                    'foundUser' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar_url' => $user->avatar_url,
                        'method' => $method,
                    ],
                ]);
        }

        $token = Password::createToken($user);
        $user->notify(new \App\Notifications\ResetPasswordLink($token, $method));

        return $this->sendResetLinkResponse($request, Password::RESET_LINK_SENT);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return redirect()->back()
            ->with('status', trans($response))
            ->with('showForgotModal', true);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('identifier'))
            ->withErrors(['identifier' => trans($response)], 'forgot')
            ->with('showForgotModal', true);
    }
}
