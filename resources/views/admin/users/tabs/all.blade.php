@extends('admin.users.index')

@section('user-content')
<div>
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">All Users</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage all system users</p>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 stat-total">{{ $stats['total'] }}</p>
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
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 stat-active">{{ $stats['active'] }}</p>
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
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Admins</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 stat-admins">{{ $stats['admins'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Administrators</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="bi bi-shield-lock text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Agents</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 stat-agents">{{ $stats['agents'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Support agents</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="bi bi-headset text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">End Users</p>
                    <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400 stat-users">{{ $stats['users'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Regular users</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center">
                    <i class="bi bi-person text-cyan-600 dark:text-cyan-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
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
                        <i class="bi bi-person-badge absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="roleFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>End User</option>
                        </select>
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

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="hidden mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-5 py-3 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="bi bi-check2-square text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        <span id="selectedCount" class="font-semibold text-blue-600 dark:text-blue-400">0</span> user(s) selected
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="cancelBulkSelect" 
                            class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button id="bulkDeleteBtn" 
                            class="px-4 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-all text-sm font-medium flex items-center gap-2">
                        <i class="bi bi-trash"></i>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Container -->
    <div id="tableContainer">
        @include('admin.users.partials.table', ['users' => $users])
    </div>
</div>

@include('admin.users.partials.modals')

<!-- Bulk Delete Modal (same as categories) -->
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
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');
    const statusMessage = document.getElementById('statusMessage');
    let typingTimer;
    
    // Bulk Delete Modal elements
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteModalContent = document.getElementById('bulkDeleteModalContent');
    const cancelBulkDeleteBtn = document.getElementById('cancelBulkDeleteBtn');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDeleteBtn');
    const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
    let pendingBulkDeleteIds = [];
    
    function showBulkModal(count) {
        if (bulkDeleteCountSpan) bulkDeleteCountSpan.textContent = count;
        if (bulkDeleteModal) {
            bulkDeleteModal.classList.remove('hidden');
            bulkDeleteModal.classList.add('flex');
            setTimeout(() => {
                bulkDeleteModalContent.classList.remove('scale-95', 'opacity-0');
                bulkDeleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }
    
    function closeBulkModal() {
        bulkDeleteModalContent.classList.remove('scale-100', 'opacity-100');
        bulkDeleteModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            bulkDeleteModal.classList.remove('flex');
            bulkDeleteModal.classList.add('hidden');
            pendingBulkDeleteIds = [];
        }, 200);
    }
    
    if (cancelBulkDeleteBtn) {
        cancelBulkDeleteBtn.addEventListener('click', closeBulkModal);
    }
    
    if (bulkDeleteModal) {
        bulkDeleteModal.addEventListener('click', function(e) {
            if (e.target === bulkDeleteModal) closeBulkModal();
        });
    }
    
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
            role: roleFilter?.value || 'all',
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
                const statAdmins = document.querySelector('.stat-admins');
                const statAgents = document.querySelector('.stat-agents');
                const statUsers = document.querySelector('.stat-users');
                if (statTotal) statTotal.textContent = data.stats.total;
                if (statActive) statActive.textContent = data.stats.active;
                if (statAdmins) statAdmins.textContent = data.stats.admins;
                if (statAgents) statAgents.textContent = data.stats.agents;
                if (statUsers) statUsers.textContent = data.stats.users;
            }
            
            attachToggleEvents();
            initBulkSelect();
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
        const isSelf = button.dataset.self === 'true';
        
        if (isSelf) {
            showStatusMessage('error', 'You cannot change the status of your own account.');
            return;
        }
        
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
        document.querySelectorAll('.activate-btn, .deactivate-btn').forEach(btn => {
            btn.removeEventListener('click', handleToggleClick);
            btn.addEventListener('click', handleToggleClick);
        });
    }
    
    // Bulk select functionality
    let selectAllCheckbox = null;
    let bulkActionsBar = null;
    let cancelBulkSelect = null;
    let bulkDeleteBtn = null;
    let selectedCountSpan = null;
    
    function initBulkSelect() {
        selectAllCheckbox = document.getElementById('selectAllCheckbox');
        bulkActionsBar = document.getElementById('bulkActionsBar');
        cancelBulkSelect = document.getElementById('cancelBulkSelect');
        bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        selectedCountSpan = document.getElementById('selectedCount');
        
        if (selectAllCheckbox) {
            const newSelectAll = selectAllCheckbox.cloneNode(true);
            selectAllCheckbox.parentNode.replaceChild(newSelectAll, selectAllCheckbox);
            selectAllCheckbox = newSelectAll;
            
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateBulkActionsBar();
            });
        }
        
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.removeEventListener('change', handleCheckboxChange);
            checkbox.addEventListener('change', handleCheckboxChange);
        });
        
        if (cancelBulkSelect) {
            const newCancel = cancelBulkSelect.cloneNode(true);
            cancelBulkSelect.parentNode.replaceChild(newCancel, cancelBulkSelect);
            cancelBulkSelect = newCancel;
            cancelBulkSelect.addEventListener('click', function() {
                document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                updateBulkActionsBar();
            });
        }
        
        if (bulkDeleteBtn) {
            const newBulkDelete = bulkDeleteBtn.cloneNode(true);
            bulkDeleteBtn.parentNode.replaceChild(newBulkDelete, bulkDeleteBtn);
            bulkDeleteBtn = newBulkDelete;
            bulkDeleteBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
                const currentUserId = {{ Auth::id() }};
                
                if (selectedIds.includes(String(currentUserId))) {
                    showStatusMessage('error', 'You cannot delete your own account!');
                    return;
                }
                
                if (selectedIds.length === 0) {
                    showStatusMessage('error', 'Please select at least one user to delete.');
                    return;
                }
                
                pendingBulkDeleteIds = selectedIds;
                showBulkModal(selectedIds.length);
            });
        }
    }
    
    // Confirm bulk delete
    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', function() {
            if (pendingBulkDeleteIds.length === 0) {
                closeBulkModal();
                return;
            }
            
            confirmBulkDeleteBtn.disabled = true;
            confirmBulkDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            fetch('/admin/users/bulk-destroy', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ user_ids: pendingBulkDeleteIds })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showStatusMessage('success', data.message || `${pendingBulkDeleteIds.length} user(s) deleted successfully.`);
                    fetchUsers();
                    closeBulkModal();
                    // Clear all checkboxes
                    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    updateBulkActionsBar();
                } else {
                    showStatusMessage('error', data.message || 'Error deleting users.');
                    closeBulkModal();
                }
                confirmBulkDeleteBtn.disabled = false;
                confirmBulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
            })
            .catch(error => {
                console.error('Bulk delete error details:', error);
                console.error('Error message:', error.message);
                showStatusMessage('error', 'An error occurred while deleting users.');
                closeBulkModal();
                confirmBulkDeleteBtn.disabled = false;
                confirmBulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
            });
        });
    }
    
    function handleCheckboxChange() {
        updateBulkActionsBar();
    }
    
    function updateBulkActionsBar() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0 && bulkActionsBar) {
            bulkActionsBar.classList.remove('hidden');
            if (selectedCountSpan) selectedCountSpan.textContent = count;
        } else if (bulkActionsBar) {
            bulkActionsBar.classList.add('hidden');
        }
        
        if (selectAllCheckbox) {
            const totalCheckboxes = document.querySelectorAll('.user-checkbox').length;
            if (totalCheckboxes === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (count === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (count === totalCheckboxes) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }
    }
    
    // Event Listeners for filters
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchUsers, 500);
        });
    }
    
    if (roleFilter) roleFilter.addEventListener('change', fetchUsers);
    if (statusFilter) statusFilter.addEventListener('change', fetchUsers);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (roleFilter) roleFilter.value = 'all';
            if (statusFilter) statusFilter.value = 'all';
            fetchUsers();
        });
    }
    
    // Initialize everything
    attachToggleEvents();
    initBulkSelect();
    updateBulkActionsBar();
});
</script>
@endsection