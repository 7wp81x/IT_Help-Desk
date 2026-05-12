<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-4 w-12">
                        <input type="checkbox" id="selectAllCheckbox" 
                               class="appearance-none w-4 h-4 bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:border-red-500 checked:bg-transparent relative after:content-['✓'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2">      
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                    <td class="px-4 py-4">
                        <input type="checkbox" class="user-checkbox appearance-none w-4 h-4 bg-transparent dark:bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:bg-transparent checked:border-red-500 relative after:content-['✓'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2" value="{{ $user->id }}">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden flex-shrink-0 shadow-sm">
                                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?background=2563EB&color=fff&name=' . urlencode($user->name) }}"
                                     alt="{{ $user->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[150px]" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]" title="{{ $user->position ?? 'No position' }}">
                                    {{ $user->position ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[180px]" title="{{ $user->email }}">
                            {{ $user->email }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                            {{ $user->employee_id ?? '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role == 'admin')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                <i class="bi bi-shield-lock text-xs"></i>
                                Admin
                            </span>
                        @elseif($user->role == 'agent')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                <i class="bi bi-headset text-xs"></i>
                                Agent
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                <i class="bi bi-person text-xs"></i>
                                User
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->status == 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <i class="bi bi-check-circle-fill text-xs"></i>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                <i class="bi bi-circle text-xs"></i>
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[120px] block" title="{{ $user->department ?? '—' }}">
                            {{ $user->department ?? '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            @if($user->status === 'active')
                                <button type="button"
                                         class="activate-btn w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 transition-all duration-200 flex items-center justify-center"
                                        data-id="{{ $user->id }}"
                                        data-self="{{ $user->id === Auth::id() ? 'true' : 'false' }}"
                                        title="Deactivate User">
                                    <i class="bi bi-toggle-on text-base"></i>
                                </button>
                            @else
                                <button type="button"
                                        class="deactivate-btn w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800/50 transition-all duration-200 flex items-center justify-center"
                                        data-id="{{ $user->id }}"
                                        data-self="{{ $user->id === Auth::id() ? 'true' : 'false' }}"
                                        title="Activate User">
                                    <i class="bi bi-toggle-off text-base"></i>
                                </button>
                            @endif
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center group"
                               title="View User">
                                <i class="bi bi-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                               title="Edit User">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </a>
                            <button type="button" 
                                    onclick="showDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')" 
                                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                    title="Delete User">
                                <i class="bi bi-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <i class="bi bi-people text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No users found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $users->links() }}
    </div>
    @endif
</div>