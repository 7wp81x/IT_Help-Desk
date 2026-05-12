<!-- Categories Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Category List</h3>
        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
            <i class="bi bi-plus-circle"></i> Add Category
        </a>
    </div>
    <div class="overflow-x-auto">
        <div class="min-w-[900px]">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-4 w-12">
                            <input type="checkbox" id="selectAllCheckbox" 
                                class="appearance-none w-4 h-4 bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:border-red-500 checked:bg-transparent relative
                                after:content-['✓'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px]">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">Tickets</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Priority</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                        <td class="px-4 py-4">
                            <input type="checkbox" 
                                class="category-checkbox appearance-none w-4 h-4 bg-transparent dark:bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:bg-transparent checked:border-red-500 relative after:content-['✓'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2" 
                                value="{{ $category->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @php
                                    $iconClass = $category->icon && str_starts_with($category->icon, 'bi') ? $category->icon : 'bi bi-tags';
                                @endphp
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: {{ $category->color ?? '#3b82f6' }}20; color: {{ $category->color ?? '#3b82f6' }};">
                                    <i class="{{ $iconClass }} text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]" title="{{ $category->name }}">
                                        {{ $category->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px] mt-0.5" title="{{ $category->description ?? 'No description' }}">
                                        <i class="bi bi-file-text text-[10px] mr-1"></i>
                                        {{ $category->description ? Str::limit($category->description, 60) : '—' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400 truncate block max-w-[120px]" title="{{ $category->slug }}">
                                {{ $category->slug }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 whitespace-nowrap">
                                <i class="bi bi-ticket text-xs"></i>
                                {{ $category->tickets_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 whitespace-nowrap">
                                    <i class="bi bi-check-circle-fill text-xs"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 whitespace-nowrap">
                                    <i class="bi bi-circle text-xs"></i>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                // Get priority value (could be string or integer)
                                $priorityRaw = $category->priority_level ?? 'normal';
                                
                                // Map to priority level
                                if (is_numeric($priorityRaw)) {
                                    $priorityMap = [
                                        5 => 'critical',
                                        4 => 'high', 
                                        3 => 'medium',
                                        2 => 'normal',
                                        1 => 'low'
                                    ];
                                    $priorityLevel = $priorityMap[(int)$priorityRaw] ?? 'normal';
                                } else {
                                    $priorityLevel = strtolower($priorityRaw);
                                }
                                
                                // Color mapping
                                $priorityColors = [
                                    'critical' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'medium' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'normal' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'low' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
                                ];
                                
                                $priorityIcons = [
                                    'critical' => 'bi-flag-fill',
                                    'high' => 'bi-exclamation-triangle-fill',
                                    'medium' => 'bi-dash-circle-fill',
                                    'normal' => 'bi-circle-fill',
                                    'low' => 'bi-arrow-down-circle-fill'
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg whitespace-nowrap {{ $priorityColors[$priorityLevel] ?? $priorityColors['normal'] }}">
                                <i class="bi {{ $priorityIcons[$priorityLevel] ?? 'bi-circle' }} text-xs"></i>
                                {{ ucfirst($priorityLevel) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <!-- Toggle Status Button -->
                                <button type="button" 
                                        class="toggle-status-btn w-8 h-8 rounded-lg transition-all duration-200 flex items-center justify-center"
                                        data-id="{{ $category->id }}">
                                    @if($category->is_active)
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
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                                   title="Edit Category">
                                    <i class="bi bi-pencil-square text-sm"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <button type="button" 
                                        class="delete-category-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ addslashes($category->name) }}">
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
                                    <i class="bi bi-tags text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No categories found</p>
                                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                                    <i class="bi bi-plus-circle"></i> Create your first category
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $categories->appends(request()->only('search', 'status'))->links() }}
    </div>
    @endif
</div>