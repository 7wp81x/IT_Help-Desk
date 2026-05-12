<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }
        
        $departments = $query->orderBy('name')->paginate(20);
        
        // Calculate department-specific stats
        $totalDepartments = Department::count();
        $activeDepartments = Department::where('is_active', true)->count();
        $inactiveDepartments = Department::where('is_active', false)->count();
        $staffedDepartments = Department::whereHas('users', function($q) {
            $q->whereNotNull('department');
        })->count();
        
        // If AJAX request, return only the table HTML
        if ($request->ajax()) {
            $tableHtml = view('admin.departments.partials.table', compact('departments'))->render();
            
            return response()->json([
                'success' => true,
                'table_html' => $tableHtml,
                'results_count' => "Showing " . $departments->firstItem() . " to " . $departments->lastItem() . " of " . $departments->total() . " departments",
                'total' => $totalDepartments,
                'active' => $activeDepartments,
                'inactive' => $inactiveDepartments,
                'staffed' => $staffedDepartments
            ]);
        }
        
        return view('admin.departments.index', compact(
            'departments', 
            'totalDepartments', 
            'activeDepartments', 
            'inactiveDepartments', 
            'staffedDepartments'
        ));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
        ]);

        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully!');
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        $users = $department->users()->paginate(10);
        $tickets = $department->tickets()->paginate(10);
        
        return view('admin.departments.show', compact('department', 'users', 'tickets'));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$id,
            'description' => 'nullable|string',
        ]);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully!');
    }
    public function toggleStatus($id)
{
    $department = Department::findOrFail($id);
    $department->is_active = !$department->is_active;
    $department->save();
    
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Department status updated successfully.',
            'is_active' => $department->is_active,
            'stats' => [
                'active' => Department::where('is_active', true)->count(),
                'inactive' => Department::where('is_active', false)->count(),
                'total' => Department::count(),
                'staffed' => Department::whereHas('users', function($q) {
                    $q->whereNotNull('department');
                })->count()
            ]
        ]);
    }
    
    return redirect()->back()->with('success', 'Department status updated successfully.');
}

    public function destroy($id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found!'
                ], 404);
            }
            return redirect()->back()->with('error', 'Department not found!');
        }
        
        if ($department->users()->count() > 0 || $department->tickets()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete department with associated users or tickets!'
                ]);
            }
            return redirect()->back()->with('error', 'Cannot delete department with associated users or tickets!');
        }
        
        $department->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Department deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = explode(',', $request->ids ?? $request->department_ids);
        $ids = array_filter(array_map('intval', $ids)); // Ensure valid integer IDs
        
        if (empty($ids)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No departments selected.'
                ]);
            }
            return redirect()->back()->with('error', 'No departments selected.');
        }
        
        // Verify all departments exist
        $foundCount = Department::whereIn('id', $ids)->count();
        if ($foundCount !== count($ids)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more departments do not exist.'
                ], 404);
            }
            return redirect()->back()->with('error', 'One or more departments do not exist.');
        }
        
        $hasRelations = Department::whereIn('id', $ids)
            ->withCount(['users', 'tickets'])
            ->get()
            ->some(function($dept) {
                return $dept->users_count > 0 || $dept->tickets_count > 0;
            });
        
        if ($hasRelations) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete departments with associated users or tickets.'
                ]);
            }
            return redirect()->back()->with('error', 'Cannot delete departments with associated users or tickets.');
        }
        
        $deletedCount = Department::whereIn('id', $ids)->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' department(s) deleted successfully.',
                'deleted_count' => $deletedCount
            ]);
        }
        
        return redirect()->back()->with('success', $deletedCount . ' department(s) deleted successfully.');
    }
}