<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        $status = strtolower(trim($request->input('status', 'all')));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['active', 'inactive'], true)) {
            $query->where('is_active', $status === 'active');
        }

        $categories = $query->withCount('tickets')->orderBy('priority_level', 'desc')->paginate(20)->withQueryString();

        // Calculate stats based on current query if filtering is applied
        $isFiltered = $request->filled('search') || in_array($status, ['active', 'inactive'], true);

        if ($isFiltered) {
            // For filtered results, calculate stats from the current query
            $filteredQuery = clone $query;
            $totalCategories = $filteredQuery->count();
            $activeCategories = (clone $filteredQuery)->where('is_active', true)->count();
            $inactiveCategories = (clone $filteredQuery)->where('is_active', false)->count();
            $usedCategories = (clone $filteredQuery)->whereHas('tickets')->count();
        } else {
            // For unfiltered results, show total system stats
            $totalCategories = Category::count();
            $activeCategories = Category::where('is_active', true)->count();
            $inactiveCategories = Category::where('is_active', false)->count();
            $usedCategories = Category::whereHas('tickets')->count();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'table_html' => view('admin.categories.partials.table', compact('categories'))->render(),
                'results_count' => 'Showing ' . ($categories->firstItem() ?? 0) . ' to ' . ($categories->lastItem() ?? 0) . ' of ' . $categories->total() . ' categories',
                'total' => $totalCategories,
                'active' => $activeCategories,
                'inactive' => $inactiveCategories,
                'used' => $usedCategories,
                'status' => $status,
            ]);
        }

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories',
            'usedCategories'
        ));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'priority_level' => 'required|integer|min:1|max:5',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $this->buildSlug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-tag',
            'color' => $request->color ?? '#6B7280',
            'priority_level' => $request->priority_level,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'priority_level' => 'required|integer|min:1|max:5',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $this->buildSlug($request->name, $category->id),
            'description' => $request->description,
            'icon' => $request->icon ?? $category->icon,
            'color' => $request->color ?? $category->color,
            'priority_level' => $request->priority_level,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }
    /**
 * Get categories by department for AJAX requests
 */
/**
 * Get categories by department for AJAX requests
 */
public function getByDepartment(Request $request)
{
    $departmentId = $request->input('department_id');
    
    if (!$departmentId) {
        return response()->json([
            'success' => false,
            'categories' => [],
            'message' => 'No department selected'
        ]);
    }
    
    // First, check if there are any categories with this department_id
    $categories = Category::where('is_active', true)
        ->where('department_id', $departmentId)
        ->orderBy('name')
        ->get(['id', 'name', 'department_id']);
    
    // If no categories found, return empty array
    return response()->json([
        'success' => true,
        'categories' => $categories,
        'count' => $categories->count()
    ]);
}

    public function toggleStatus(int $id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category status updated successfully.',
                'is_active' => $category->is_active,
                'stats' => [
                    'total' => Category::count(),
                    'active' => Category::where('is_active', true)->count(),
                    'inactive' => Category::where('is_active', false)->count(),
                    'used' => Category::whereHas('tickets')->count(),
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Category status updated successfully.');
    }

    public function destroy(int $id)
    {
        $category = Category::find($id);

        if (! $category) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found!'
                ], 404);
            }

            return redirect()->back()->with('error', 'Category not found!');
        }

        if ($category->tickets()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with associated tickets.'
                ]);
            }

            return redirect()->back()->with('error', 'Cannot delete category with associated tickets.');
        }

        $category->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = explode(',', $request->ids ?? $request->category_ids ?? '');
        $ids = array_filter(array_map('intval', $ids));

        if (empty($ids)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories selected.'
                ]);
            }

            return redirect()->back()->with('error', 'No categories selected.');
        }

        $foundCount = Category::whereIn('id', $ids)->count();
        if ($foundCount !== count($ids)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more categories do not exist.'
                ], 404);
            }

            return redirect()->back()->with('error', 'One or more categories do not exist.');
        }

        $hasRelations = Category::whereIn('id', $ids)
            ->withCount('tickets')
            ->get()
            ->some(function ($category) {
                return $category->tickets_count > 0;
            });

        if ($hasRelations) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete categories with associated tickets.'
                ]);
            }

            return redirect()->back()->with('error', 'Cannot delete categories with associated tickets.');
        }

        $deletedCount = Category::whereIn('id', $ids)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' category(ies) deleted successfully.',
                'deleted_count' => $deletedCount
            ]);
        }

        return redirect()->back()->with('success', $deletedCount . ' category(ies) deleted successfully.');
    }

    protected function buildSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)
            ->when($ignoreId, function ($query, $ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
