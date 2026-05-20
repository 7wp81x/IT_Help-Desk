<!-- Departments Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Departments List</h3>
        <a href="{{ route('admin.departments.create') }}" 
           class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
            <i class="bi bi-plus-circle"></i> Add Department
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-4 w-12">
                        <input type="checkbox" id="selectAllCheckbox" 
                            class="appearance-none w-4 h-4 bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:border-red-500 checked:bg-transparent relative
                            after:content-['✓'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2">
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Department Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Users</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($departments as $department)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                    <td class="px-4 py-4">
                        <input type="checkbox" class="department-checkbox appearance-none w-4 h-4 bg-transparent dark:bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:bg-transparent checked:border-red-500 relative after:content-['✓'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2" value="{{ $department->id }}">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                                {{ strtoupper(substr($department->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]" title="{{ $department->name }}">
                                    {{ $department->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    ID: #{{ $department->id }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 max-w-[300px]" title="{{ $department->description ?? 'No description' }}">
                            {{ $department->description ?? '—' }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            <i class="bi bi-people text-xs"></i>
                            {{ $department->users()->where('role', 'agent')->count() }} Agents
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($department->is_active)
                            <span class="status-badge inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <i class="bi bi-check-circle-fill text-xs"></i>
                                Active
                            </span>
                        @else
                            <span class="status-badge inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                <i class="bi bi-circle text-xs"></i>
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $department->created_at->format('M d, Y') }}
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $department->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <!-- Toggle Status Button -->
                            <button type="button" 
                                    class="toggle-status-btn w-8 h-8 rounded-lg transition-all duration-200 flex items-center justify-center group"
                                    data-id="{{ $department->id }}"
                                    data-active="{{ $department->is_active ? 'true' : 'false' }}"
                                    title="{{ $department->is_active ? 'Deactivate Department' : 'Activate Department' }}">
                                @if($department->is_active)
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 flex items-center justify-center">
                                        <i class="bi bi-toggle-on text-base"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center">
                                        <i class="bi bi-toggle-off text-base"></i>
                                    </div>
                                @endif
                            </button>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('admin.departments.edit', $department->id) }}" 
                               class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                               title="Edit Department">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </a>
                            
                            <!-- Delete Button -->
                            <button type="button" 
                                    class="delete-dept-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                    data-id="{{ $department->id }}"
                                    data-name="{{ addslashes($department->name) }}"
                                    title="Delete Department">
                                <i class="bi bi-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <i class="bi bi-building text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No departments found</p>
                            <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                                <i class="bi bi-plus-circle"></i> Create your first department
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($departments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $departments->links() }}
    </div>
    @endif
</div>