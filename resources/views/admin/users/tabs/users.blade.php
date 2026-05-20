@extends('admin.users.index')

@section('title', 'End Users')

@section('user-content')
<div>
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">End Users</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage end users and their activity</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-calendar3 text-blue-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                    </div>
                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock text-blue-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 stat-total">{{ $stats['users'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Registered users</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-people text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Active Users</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 stat-active">{{ $stats['active_users'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Currently active</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="bi bi-person-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Tickets</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 stat-tickets">{{ $stats['user_tickets'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Submitted tickets</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="bi bi-ticket-detailed text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR with AJAX loading -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search by name, email, or employee ID..." 
                               value="{{ request('search') }}"
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="statusFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
            </div>
            
            <div id="loadingIndicator" class="hidden mt-3 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                    <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading users...
                </div>
            </div>
        </div>
        
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </p>
        </div>
    </div>

    <div id="statusMessage" class="hidden mb-6 rounded-xl p-3 text-sm"></div>

    <!-- Users Table Container -->
    <div id="tableContainer">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">End User Accounts</h3>
                <a href="{{ route('admin.users.create', ['role' => 'user']) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg text-sm transition-all shadow-sm">
                    <i class="bi bi-plus-circle"></i> Add User
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <div class="min-w-[900px]">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Tickets</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[120px]">Joined</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white text-base font-bold shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">ID: {{ $user->employee_id ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        <i class="bi bi-ticket text-xs"></i>
                                        {{ $user->tickets_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 user-status-cell whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button"
                                                class="toggle-status-btn w-8 h-8 rounded-lg transition-all duration-200 flex items-center justify-center group"
                                                data-id="{{ $user->id }}"
                                                data-active="{{ $user->status === 'active' ? 'true' : 'false' }}"
                                                title="{{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}">
                                            @if($user->status === 'active')
                                                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 flex items-center justify-center">
                                                    <i class="bi bi-toggle-on text-base"></i>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center">
                                                    <i class="bi bi-toggle-off text-base"></i>
                                                </div>
                                            @endif
                                        </button>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
                                           title="View User">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                                           title="Edit User">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>
                                        <button onclick="showDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')" 
                                                class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                                title="Delete User">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <i class="bi bi-people text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">No end users found</p>
                                        <a href="{{ route('admin.users.create', ['role' => 'user']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg text-sm transition-all">
                                            <i class="bi bi-plus-circle"></i> Create your first user
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                {{ $users->appends(request()->only('search', 'status'))->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@include('admin.users.partials.modals')

<!-- Single Delete Modal -->
<div id="singleDeleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="singleDeleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete User</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="singleDeleteUserName" class="font-semibold text-red-600 dark:text-red-400"></span>?<br>
                This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button id="cancelSingleDeleteBtn" 
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    Cancel
                </button>
                <button id="confirmSingleDeleteBtn" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="bulkDeleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Users</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="bulkDeleteCount" class="font-semibold text-red-600 dark:text-red-400">0</span> user(s)?<br>
                This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button id="cancelBulkDeleteBtn" 
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    Cancel
                </button>
                <button id="confirmBulkDeleteBtn" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Live Date and Time
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options);
    const formattedTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');
    if (dateElement) dateElement.textContent = formattedDate;
    if (timeElement) timeElement.textContent = formattedTime;
}

updateDateTime();
setInterval(updateDateTime, 1000);

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');
    const statusMessage = document.getElementById('statusMessage');
    let typingTimer;
    
    // Single Delete Modal elements
    const singleDeleteModal = document.getElementById('singleDeleteModal');
    const singleDeleteModalContent = document.getElementById('singleDeleteModalContent');
    const cancelSingleDeleteBtn = document.getElementById('cancelSingleDeleteBtn');
    const confirmSingleDeleteBtn = document.getElementById('confirmSingleDeleteBtn');
    const singleDeleteUserName = document.getElementById('singleDeleteUserName');
    let pendingSingleDeleteId = null;
    let pendingSingleDeleteName = null;
    
    // Bulk Delete Modal elements
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteModalContent = document.getElementById('bulkDeleteModalContent');
    const cancelBulkDeleteBtn = document.getElementById('cancelBulkDeleteBtn');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDeleteBtn');
    const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
    let pendingBulkDeleteIds = [];
    
    function showStatusMessage(type, message) {
        if (!statusMessage) return;
        statusMessage.textContent = message;
        statusMessage.className = 'mb-6 rounded-xl p-3 text-sm border';
        if (type === 'success') {
            statusMessage.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
        } else {
            statusMessage.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
        }
        statusMessage.classList.remove('hidden');
        clearTimeout(showStatusMessage.timer);
        showStatusMessage.timer = setTimeout(() => {
            statusMessage.classList.add('hidden');
        }, 5000);
    }
    
    // AJAX fetch users
    function fetchUsers() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (tableContainer) tableContainer.style.opacity = '0.5';
        
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            status: statusFilter?.value || 'all',
            ajax: 1
        });
        
        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (tableContainer) {
                tableContainer.innerHTML = data.html;
                tableContainer.style.opacity = '1';
            }
            if (resultsCount && data.results_count) resultsCount.innerHTML = data.results_count;
            
            if (data.stats) {
                const statTotal = document.querySelector('.stat-total');
                const statActive = document.querySelector('.stat-active');
                const statTickets = document.querySelector('.stat-tickets');
                if (statTotal) statTotal.textContent = data.stats.users;
                if (statActive) statActive.textContent = data.stats.active_users;
                if (statTickets) statTickets.textContent = data.stats.user_tickets;
            }
            
            attachToggleEvents();
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        });
    }
    
    // Toggle status functionality
    function handleToggleClick(e) {
        e.preventDefault();
        const button = this;
        const userId = button.dataset.id;
        const isActive = button.dataset.active === 'true';
        
        fetch(`/admin/users/${userId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatusMessage('success', data.message || 'User status updated successfully.');
                fetchUsers();
            } else {
                showStatusMessage('error', data.message || 'Error updating status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatusMessage('error', 'An error occurred while updating status.');
        });
    }
    
    function attachToggleEvents() {
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.removeEventListener('click', handleToggleClick);
            btn.addEventListener('click', handleToggleClick);
        });
    }
    
    // Single Delete Functions
    function showSingleDeleteModal(userId, userName) {
        pendingSingleDeleteId = userId;
        pendingSingleDeleteName = userName;
        if (singleDeleteUserName) singleDeleteUserName.textContent = userName;
        if (singleDeleteModal) {
            singleDeleteModal.classList.remove('hidden');
            singleDeleteModal.classList.add('flex');
            setTimeout(() => {
                singleDeleteModalContent.classList.remove('scale-95', 'opacity-0');
                singleDeleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }
    
    function closeSingleDeleteModal() {
        singleDeleteModalContent.classList.remove('scale-100', 'opacity-100');
        singleDeleteModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            singleDeleteModal.classList.remove('flex');
            singleDeleteModal.classList.add('hidden');
            pendingSingleDeleteId = null;
            pendingSingleDeleteName = null;
        }, 200);
    }
    
    if (cancelSingleDeleteBtn) {
        cancelSingleDeleteBtn.addEventListener('click', closeSingleDeleteModal);
    }
    
    if (singleDeleteModal) {
        singleDeleteModal.addEventListener('click', function(e) {
            if (e.target === singleDeleteModal) closeSingleDeleteModal();
        });
    }
    
    // Confirm single delete
    if (confirmSingleDeleteBtn) {
        confirmSingleDeleteBtn.addEventListener('click', function() {
            if (!pendingSingleDeleteId) {
                closeSingleDeleteModal();
                return;
            }
            
            confirmSingleDeleteBtn.disabled = true;
            confirmSingleDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            fetch(`/admin/users/${pendingSingleDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusMessage('success', data.message || 'User deleted successfully.');
                    fetchUsers();
                    closeSingleDeleteModal();
                } else {
                    showStatusMessage('error', data.message || 'Error deleting user.');
                    closeSingleDeleteModal();
                }
                confirmSingleDeleteBtn.disabled = false;
                confirmSingleDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
            })
            .catch(error => {
                console.error('Delete error:', error);
                showStatusMessage('error', 'An error occurred while deleting the user.');
                closeSingleDeleteModal();
                confirmSingleDeleteBtn.disabled = false;
                confirmSingleDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
            });
        });
    }
    
    // Show single delete modal from delete button
    window.showDeleteModal = function(userId, userName) {
        showSingleDeleteModal(userId, userName);
    };
    
    // Event Listeners for filters
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchUsers, 500);
        });
    }
    
    if (statusFilter) statusFilter.addEventListener('change', fetchUsers);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = 'all';
            fetchUsers();
        });
    }
    
    // Initialize
    attachToggleEvents();
});
</script>
@endsection