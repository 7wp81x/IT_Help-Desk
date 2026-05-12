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

    /**
     * Override to prevent reset links for unverified email addresses and restrict by role.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->input('email'))->first();

        // Check if user exists and email is verified
        if (! $user || ! $user->email_verified_at) {
            return $this->sendResetLinkFailedResponse($request, Password::INVALID_USER);
        }

        // For agents: must have real email (not admin email)
        if ($user->hasRole('agent')) {
            // You can add additional checks here if needed to verify it's a real email
            // For now, agents with verified emails can reset passwords
        }
        // For endusers and admins: restrict password reset
        elseif ($user->hasRole('admin') || $user->role === 'user') {
            return $this->sendResetLinkFailedResponse($request, Password::INVALID_USER);
        }

        return parent::sendResetLinkEmail($request);
    }
}
