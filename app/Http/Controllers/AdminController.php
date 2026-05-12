<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function allTickets()
    {
        $tickets = Ticket::with(['user', 'category', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::whereIn('status', ['open', 'in_progress', 'pending'])->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'urgent' => Ticket::where('priority', 'urgent')->count(),
        ];
        
        return view('admin.tickets', compact('tickets', 'stats'));
    }

    public function users()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();
        
        return view('admin.users', compact('users', 'roles'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);
        
        $user->syncRoles([$request->role]);
        
        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully!");
    }

    public function categories()
    {
        $categories = Category::withCount('tickets')->orderBy('tickets_count', 'desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);
        
        Category::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'icon' => $request->icon ?? 'fa-ticket',
        ]);
        
        return redirect()->back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $category->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color ?? $category->color,
            'icon' => $request->icon ?? $category->icon,
            'is_active' => $request->is_active ?? $category->is_active,
        ]);
        
        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->tickets()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated tickets!');
        }
        
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function reports()
    {
        // Get statistics
        $totalTickets = Ticket::count();
        $avgResolutionTime = Ticket::whereNotNull('resolved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours'))
            ->first()
            ->avg_hours ?? 0;
            
        $ticketsByUser = User::withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->take(10)
            ->get();
            
        $ticketsByCategory = Category::withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->get();
            
        $dailyTickets = Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return view('admin.reports', compact('totalTickets', 'avgResolutionTime', 'ticketsByUser', 'ticketsByCategory', 'dailyTickets'));
    }
}