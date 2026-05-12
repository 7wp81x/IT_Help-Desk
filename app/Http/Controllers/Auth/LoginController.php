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

    protected function redirectTo()
    {
        $user = Auth::user();
        
        // If pending agent, redirect to pending approval page
        if ($user->role === 'pending_agent') {
            return '/pending-approval';
        }
        
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
}