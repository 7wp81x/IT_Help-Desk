<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        
        return redirect()->route('user.dashboard');
    }
}