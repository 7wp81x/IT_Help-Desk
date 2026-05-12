<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    
    public function getStats()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'resolved_tickets' => Ticket::where('status', 'resolved')->count(),
            'total_users' => User::count(),
        ];
        
        return response()->json($stats);
    }
}