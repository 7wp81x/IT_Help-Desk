<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $departments = $query->orderBy('name')->paginate(20);

        $totalDepartments = Department::count();
        $activeDepartments = Department::where('is_active', true)->count();
        $inactiveDepartments = Department::where('is_active', false)->count();

        // ✅ FIXED: agent-only system (department_id)
        $staffedDepartments = Department::whereHas('users', function ($q) {
            $q->whereNotNull('department_id');
        })->count();

        if ($request->ajax()) {
            $tableHtml = view('admin.departments.partials.table', compact('departments'))->render();

            return response()->json([
                'success' => true,
                'table_html' => $tableHtml,
                'results_count' => "Showing " . $departments->firstItem() . " to " . $departments->lastItem() . " of " . $departments->total(),
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

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully!');
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
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully!');
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

                    // ✅ FIXED (NO department column)
                    'staffed' => Department::whereHas('users', function ($q) {
                        $q->whereNotNull('department_id');
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
            return response()->json(['success' => false, 'message' => 'Department not found!'], 404);
        }

        if ($department->users()->count() > 0 || $department->tickets()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department with assigned agents or tickets!'
            ]);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully!'
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = explode(',', $request->ids ?? $request->department_ids);
        $ids = array_filter(array_map('intval', $ids));

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No departments selected.']);
        }

        $hasRelations = Department::whereIn('id', $ids)
            ->withCount(['users', 'tickets'])
            ->get()
            ->some(fn ($d) => $d->users_count > 0 || $d->tickets_count > 0);

        if ($hasRelations) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete departments with assigned agents or tickets.'
            ]);
        }

        $deleted = Department::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => $deleted . ' department(s) deleted successfully.'
        ]);
    }

    public function specializations($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['specializations' => []], 404);
        }

        return response()->json([
            'specializations' => $department->specializations ?? []
        ]);
    }

    public function generateEmployeeId($id)
    {
        $department = Department::find($id);

        $prefix = 'AGT';
        $code = $department ? substr(preg_replace('/[^A-Z0-9]/', '', strtoupper($department->name)), 0, 4) : 'GEN';

        do {
            $random = strtoupper(Str::random(4));
            $employeeId = "{$prefix}-{$code}-{$random}";
        } while (User::where('employee_id', $employeeId)->exists());

        return response()->json(['employee_id' => $employeeId]);
    }
}