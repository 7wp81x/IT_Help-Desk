@extends('admin.users.index')

@section('user-content')
<div>
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Administrators</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage admin users and permissions</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-calendar3 text-purple-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                    </div>
                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock text-purple-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Admins</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 stat-total">{{ $stats['admins'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">System administrators</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="bi bi-shield-lock text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Active Admins</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 stat-active">{{ $stats['active_admins'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Permissions</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_permissions'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Assigned permissions</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-key text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ADMIN FEATURES NAVIGATION -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.users.admins.permissions') }}" 
           class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950/30 dark:to-purple-900/20 rounded-xl shadow-sm p-4 border border-purple-200 dark:border-purple-800 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center group-hover:bg-purple-200 transition">
                    <i class="bi bi-shield-check text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Permissions</h3>
                    <p class="text-xs text-gray-500">Grant/revoke admin permissions</p>
                </div>
                <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        
        <a href="{{ route('admin.users.admins.audit-logs') }}" 
           class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950/30 dark:to-blue-900/20 rounded-xl shadow-sm p-4 border border-blue-200 dark:border-blue-800 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 transition">
                    <i class="bi bi-clock-history text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Audit Logs</h3>
                    <p class="text-xs text-gray-500">View all admin activity</p>
                </div>
                <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        
        <a href="{{ route('admin.users.admins.system-settings') }}" 
           class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800/50 dark:to-gray-700/30 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center group-hover:bg-gray-200 transition">
                    <i class="bi bi-gear text-gray-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">System Settings</h3>
                    <p class="text-xs text-gray-500">System configuration</p>
                </div>
                <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
        
        <a href="{{ route('admin.users.admins.team-view') }}" 
           class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950/30 dark:to-green-900/20 rounded-xl shadow-sm p-4 border border-green-200 dark:border-green-800 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 transition">
                    <i class="bi bi-people text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Team View</h3>
                    <p class="text-xs text-gray-500">View & manage team hierarchy</p>
                </div>
                <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search by name, email, or employee ID..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="statusFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
                    <div class="w-4 h-4 border-2 border-purple-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading admins...
                </div>
            </div>
        </div>
        
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() ?? 0 }} admins
            </p>
        </div>
    </div>

    <div id="statusMessage" class="hidden mb-6 rounded-xl p-3 text-sm"></div>

   <!-- ADMINS TABLE WITH AVATAR IMAGES -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Administrator Accounts</h3>
        <a href="{{ route('admin.users.create', ['role' => 'admin']) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg text-sm transition-all shadow-sm">
            <i class="bi bi-plus-circle"></i> Add Admin
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-[1100px] w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-24">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[180px]">Last Active</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="adminsTableBody">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                @if($admin->avatar && file_exists(storage_path('app/public/avatars/' . $admin->avatar)))
                                    <img src="{{ asset('storage/avatars/' . $admin->avatar) }}" 
                                         class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700"
                                         id="avatarPreview-{{ $admin->id }}">
                                @elseif($admin->avatar_url)
                                    <img src="{{ $admin->avatar_url }}" 
                                         class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700"
                                         id="avatarPreview-{{ $admin->id }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-base font-bold shadow-sm">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                @endif
                                <button type="button" onclick="document.getElementById('avatarInput-{{ $admin->id }}').click()" 
                                        class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-purple-500 hover:bg-purple-600 text-white flex items-center justify-center shadow-md transition text-[10px]">
                                    <i class="bi bi-camera-fill"></i>
                                </button>
                                <input type="file" id="avatarInput-{{ $admin->id }}" class="avatar-upload hidden" accept="image/*" data-user-id="{{ $admin->id }}">
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $admin->name }}</p>
                                <p class="text-xs text-gray-500">{{ $admin->position ?? 'Administrator' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $admin->email }}</td>
                    <td class="px-6 py-4">
                        @if($admin->is_super_admin ?? false)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 whitespace-nowrap">
                                <i class="bi bi-star-fill text-xs"></i> Super Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 whitespace-nowrap">
                                <i class="bi bi-shield-lock text-xs"></i> Admin
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 user-status-cell">
                        @if($admin->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 whitespace-nowrap">
                                <i class="bi bi-check-circle-fill text-xs"></i> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 whitespace-nowrap">
                                <i class="bi bi-circle text-xs"></i> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($admin->last_login_at)
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $admin->last_login_at->format('M d, Y h:i A') }}</div>
                            <div class="text-xs text-gray-400">{{ $admin->last_login_at->diffForHumans() }}</div>
                        @else
                            <span class="text-sm text-gray-400">Never logged in</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button" class="toggle-status-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200" 
                                    data-id="{{ $admin->id }}" 
                                    data-active="{{ $admin->status === 'active' ? 'true' : 'false' }}"
                                    data-self="{{ $admin->id === auth()->id() ? 'true' : 'false' }}"
                                    title="{{ $admin->id === auth()->id() ? 'You cannot change the status of your own account.' : ($admin->status === 'active' ? 'Deactivate Admin' : 'Activate Admin') }}">
                                @if($admin->status === 'active')
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                        <i class="bi bi-toggle-on text-base"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                        <i class="bi bi-toggle-off text-base"></i>
                                    </div>
                                @endif
                            </button>
                            <a href="{{ route('admin.users.edit', $admin) }}" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 hover:bg-green-100 hover:text-green-600 flex items-center justify-center">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </a>
                            <button type="button" onclick="showDeleteModal('{{ $admin->id }}', '{{ addslashes($admin->name) }}')" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-600 flex items-center justify-center">
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
                                <i class="bi bi-shield-lock text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No admin users found</p>
                            <a href="{{ route('admin.users.create', ['role' => 'admin']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg text-sm">
                                <i class="bi bi-plus-circle"></i> Create your first admin
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($admins->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $admins->appends(request()->only('search', 'status'))->links() }}
    </div>
    @endif
</div>

@include('admin.users.partials.modals')

<script>
// Live Date and Time
function updateDateTime() {
    const now = new Date();
    const formattedDate = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    const formattedTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');
    if (dateElement) dateElement.textContent = formattedDate;
    if (timeElement) timeElement.textContent = formattedTime;
}

updateDateTime();
setInterval(updateDateTime, 1000);

function showStatusMessage(type, message) {
    const statusMessage = document.getElementById('statusMessage');
    if (!statusMessage) return;
    statusMessage.textContent = message;
    statusMessage.className = 'mb-6 rounded-xl p-3 text-sm border';
    
    const classes = type === 'success' 
        ? ['bg-green-50', 'text-green-700', 'border-green-200']
        : ['bg-red-50', 'text-red-700', 'border-red-200'];
    
    classes.forEach(cls => statusMessage.classList.add(cls));
    statusMessage.classList.remove('hidden');
    setTimeout(() => statusMessage.classList.add('hidden'), 5000);
}

// Avatar upload functionality
function initAvatarUpload() {
    document.querySelectorAll('.avatar-upload').forEach(input => {
        // Remove old listener
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
        
        newInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const userId = this.dataset.userId;
            const formData = new FormData();
            formData.append('avatar', file);
            
            fetch(`/admin/users/${userId}/avatar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const avatarPreview = document.getElementById(`avatarPreview-${userId}`);
                    if (avatarPreview) {
                        avatarPreview.src = data.avatar_url + '?' + Date.now();
                    }
                    showStatusMessage('success', 'Avatar updated successfully!');
                } else {
                    showStatusMessage('error', data.message || 'Failed to upload avatar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusMessage('error', 'An error occurred while uploading avatar');
            });
        });
    });
}

// Toggle Status Button Handler
function attachToggleStatusListener(btn) {
    if (!btn) return;
    
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const userId = this.dataset.id;
        const isSelf = this.dataset.self === 'true';
        
        if (isSelf) {
            showStatusMessage('error', 'You cannot change the status of your own account.');
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/admin/users/${userId}/toggle`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                return res.text().then(text => {
                    throw new Error(`HTTP ${res.status}: ${text}`);
                });
            }
            return res.json().catch(err => {
                throw new Error('Invalid JSON response from server');
            });
        })
        .then(data => {
            console.log('Toggle response:', data);
            if (data.success) {
                showStatusMessage('success', data.message || 'Status updated successfully');
                // Refresh the page or just update that row
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showStatusMessage('error', data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Toggle Status Error:', error);
            console.error('Full error:', error);
            showStatusMessage('error', error.message || 'An error occurred while updating status');
        });
    });
}

// Initialize toggle status buttons
function initToggleStatusButtons() {
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        attachToggleStatusListener(btn);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const resultsCount = document.getElementById('resultsCount');
    const tableContainer = document.querySelector('.overflow-x-auto');
    const paginationContainer = document.querySelector('.border-t.bg-gray-50');
    let typingTimer;
    
    // Initialize avatar upload on page load
    initAvatarUpload();
    
    // Initialize toggle status buttons on page load
    initToggleStatusButtons();
    
    function fetchAdmins() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        
        const searchValue = searchInput?.value || '';
        const statusValue = statusFilter?.value || 'all';
        
        const params = new URLSearchParams({
            search: searchValue,
            status: statusValue,
            ajax: 1
        });
        
        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            // Update the entire table container with new HTML
            const newContainer = document.createElement('div');
            newContainer.innerHTML = data.table_html;
            
            const newTableContainer = newContainer.querySelector('.overflow-x-auto');
            const newPagination = newContainer.querySelector('.border-t.bg-gray-50');
            
            if (tableContainer && newTableContainer) {
                tableContainer.innerHTML = newTableContainer.innerHTML;
            }
            if (paginationContainer && newPagination) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }
            
            if (resultsCount && data.results_count) {
                resultsCount.innerHTML = data.results_count;
            }
            
            // Update stats
            if (data.stats) {
                const statTotal = document.querySelector('.stat-total');
                const statActive = document.querySelector('.stat-active');
                if (statTotal) statTotal.textContent = data.stats.admins || 0;
                if (statActive) statActive.textContent = data.stats.active_admins || 0;
            }
            
            // Re-initialize avatar upload after table update
            initAvatarUpload();
            
            // Re-initialize toggle status buttons after table update
            initToggleStatusButtons();
            
            document.querySelectorAll('.delete-admin-btn, .delete-category-btn, .delete-dept-btn, .delete-user-btn').forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
            });
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchAdmins, 500);
        });
    }
    
    if (statusFilter) statusFilter.addEventListener('change', fetchAdmins);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = 'all';
            fetchAdmins();
        });
    }
});
</script>
@endsection