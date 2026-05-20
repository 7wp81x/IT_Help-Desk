<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('welcome')->with('showLoginModal', true);
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return '/admin/dashboard';
        }
        
        if ($user->role === 'agent') {
            return '/agent/dashboard';
        }
        
        return '/user/dashboard';
    }

    /**
     * The user has been authenticated.
     * This is called after successful login.
     */
    protected function authenticated(Request $request, $user)
    {
        // Update last login timestamp and IP
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();
    }

    /**
     * Handle failed login attempts and keep the login modal open.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([$this->username() => trans('auth.failed')], 'login')
            ->with('showLoginModal', true);
    }
}