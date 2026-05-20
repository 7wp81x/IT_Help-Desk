<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/user/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the verification code form.
     */
    public function showCodeForm(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('auth.verify-code');
    }

    /**
     * Verify the email using the verification code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ], [
            'code.regex' => 'The verification code must be 6 digits.',
            'code.size' => 'The verification code must be exactly 6 characters.',
        ]);

        $user = $request->user();

        // Check if user's email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath())
                ->with('info', 'Your email is already verified.');
        }

        // Check if verification code is valid
        if (!$user->isVerificationCodeValid($request->code)) {
            return back()
                ->withInput()
                ->withErrors(['code' => 'The verification code is invalid or has expired.'])
                ->with('open_verify_modal', true);
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            $returnUrl = $request->input('return_url');
            if ($returnUrl && Str::startsWith($returnUrl, url('/'))) {
                return redirect($returnUrl)
                    ->with('success', 'Email verified successfully!');
            }

            return redirect($this->redirectPath())
                ->with('success', 'Email verified successfully!');
        }

        return back()
            ->withErrors(['code' => 'Failed to verify email. Please try again.']);
    }

    /**
     * Resend the verification code.
     */
    public function resendCode(Request $request)
    {
        $user = $request->user();

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return back()
                ->with('info', 'Your email is already verified.');
        }

        // Check throttle: allow resend only once per minute
        $lastCodeSentAt = $user->verification_code_expires_at;
        if ($lastCodeSentAt && $lastCodeSentAt->diffInSeconds(now()) < -1740) { // 30 min - 60 sec
            return back()
                ->withErrors(['code' => 'Please wait before requesting a new code.']);
        }

        // Send new verification code
        $user->sendEmailVerificationNotification();

        return back()
            ->with('success', 'A new verification code has been sent to your email address.')
            ->with('open_verify_modal', true);
    }
}

