<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Department;
use Illuminate\Http\Request;

class AgentUserController extends Controller
{
    /**
     * Display agents only
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'agent')->with('department');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department_id', $request->department);
        }
        
        $agents = $query->latest()->paginate(10)->withQueryString();
        
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        
        $stats = [
            'agents' => User::where('role', 'agent')->count(),
            'online_agents' => User::where('role', 'agent')->where('status', 'active')->count(),
            'total_assigned' => Ticket::whereNotNull('assigned_to')->count(),
        ];
        
       if ($request->ajax() || $request->has('ajax')) {
    // Return only the table body HTML, not the whole container
    $html = view('admin.users.partials.agents_table_body', compact('agents'))->render();
    return response()->json([
        'html' => $html,
        'results_count' => "Showing " . ($agents->firstItem() ?? 0) . " to " . ($agents->lastItem() ?? 0) . " of " . $agents->total() . " agents",
        'stats' => $stats,
        'pagination' => $agents->appends(request()->only('search', 'status', 'department'))->links()->toHtml()
    ]);
}

        return view('admin.users.tabs.agents', compact('agents', 'stats', 'departments'));
    }
}