@extends('admin.users.index')  <!-- Change this line -->

@section('user-content')  <!-- Change this line -->
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Audit Logs</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track all user activities and system changes</p>
        </div>
        <div>
            <a href="{{ route('admin.users.admins') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Admins</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchInput" placeholder="Search by user or action..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    </div>
                </div>
                
                <div class="w-[160px]">
                    <select id="actionFilter" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        <option value="all">All Actions</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>
                
                <div class="w-[160px]">
                    <select id="userFilter" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        <option value="all">All Users</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <button id="resetFilters" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
            </div>
        </div>
        
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500" id="resultsCount">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs</p>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Details</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'System' }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $actionColors = [
                                    'create' => 'bg-green-100 text-green-700',
                                    'update' => 'bg-blue-100 text-blue-700',
                                    'delete' => 'bg-red-100 text-red-700',
                                    'login' => 'bg-purple-100 text-purple-700',
                                    'logout' => 'bg-gray-100 text-gray-700',
                                ];
                                $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $color }}">
                                <i class="bi bi-{{ $log->action === 'create' ? 'plus-circle' : ($log->action === 'delete' ? 'trash' : 'pencil') }} mr-1"></i>
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $log->description }}</p>
                            @if($log->details)
                            <p class="text-xs text-gray-500 mt-1">{{ json_encode($log->details) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="bi bi-clock-history text-4xl text-gray-400"></i>
                                <p class="text-gray-500">No audit logs found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const actionFilter = document.getElementById('actionFilter');
    const userFilter = document.getElementById('userFilter');
    const resetBtn = document.getElementById('resetFilters');
    let typingTimer;
    
    function fetchLogs() {
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            action: actionFilter?.value || 'all',
            user_id: userFilter?.value || 'all',
            ajax: 1
        });
        
        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-xl').outerHTML = html;
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchLogs, 500);
        });
    }
    
    if (actionFilter) actionFilter.addEventListener('change', fetchLogs);
    if (userFilter) userFilter.addEventListener('change', fetchLogs);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (actionFilter) actionFilter.value = 'all';
            if (userFilter) userFilter.value = 'all';
            fetchLogs();
        });
    }
});
</script>
@endsection