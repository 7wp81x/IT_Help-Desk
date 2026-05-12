<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get unread notifications for the user
        $notifications = $user->notifications()
            ->where('read', false)
            ->latest()
            ->take(10)
            ->get();
        
        return response()->json($notifications);
    }
}