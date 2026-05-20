@extends('admin.users.index')

@section('title', 'Support Agents')

@section('user-content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Support Agents</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage support agents and their performance</p>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-calendar3 text-orange-500 text-lg"></i>
                    <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                </div>
                <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-clock text-orange-500 text-lg"></i>
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
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Agents</p>
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 stat-total">{{ $stats['agents'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Active support team members</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                <i class="bi bi-headset text-orange-600 dark:text-orange-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Online Now</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 stat-online">{{ $stats['online_agents'] ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1">Available for support</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <i class="bi bi-wifi text-green-600 dark:text-green-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tickets Assigned</p>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 stat-assigned">{{ $stats['total_assigned'] ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1">Currently assigned tickets</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <i class="bi bi-ticket-detailed text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>
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
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
            
            <div class="w-[140px]">
                <div class="relative">
                    <i class="bi bi-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <select id="departmentFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                        <option value="all">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
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
        
        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden mt-3 text-center">
            <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                <div class="w-4 h-4 border-2 border-orange-600 border-t-transparent rounded-full animate-spin"></div>
                Loading agents...
            </div>
        </div>
    </div>
    
    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
        <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
            Showing {{ $agents->firstItem() ?? 0 }} to {{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }} agents
        </p>
    </div>
</div>

<div id="statusMessage" class="hidden mb-6 rounded-xl p-3 text-sm"></div>

<!-- Agents Table Container -->
<div id="tableContainer">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Agent Accounts</h3>
            <a href="{{ route('admin.users.create', ['role' => 'agent']) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg text-sm transition-all shadow-sm">
                <i class="bi bi-plus-circle"></i> Add Agent
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <div class="min-w-[1000px]">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Agent</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Tickets</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Actions</th>
                        </tr>
                    </thead>
                    
                    <tbody id="agentsTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
    @include('admin.users.partials.agents_table_body', ['agents' => $agents])
</tbody>
                </table>
            </div>
        </div>
        
        @if($agents->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 pagination-wrapper">
            {{ $agents->appends(request()->only('search', 'status', 'department'))->links() }}
        </div>
        @endif
    </div>
</div>

@include('admin.users.partials.modals')

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
    const departmentFilter = document.getElementById('departmentFilter');
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
    
    // AJAX fetch agents - UPDATE ONLY THE TBODY
// AJAX fetch agents - UPDATE ONLY THE TBODY
function fetchAgents() {
    if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    
    const params = new URLSearchParams({
        search: searchInput?.value || '',
        department: departmentFilter?.value || 'all',
        status: statusFilter?.value || 'all',
        ajax: 1
    });
    
    fetch(window.location.pathname + '?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        // Update only the tbody content using its ID
        if (data.html) {
            const tbody = document.getElementById('agentsTableBody');
            if (tbody) {
                tbody.innerHTML = data.html;
            }
        }
        
        // Update pagination if provided
        if (data.pagination) {
            const paginationWrapper = document.querySelector('#tableContainer .pagination-wrapper');
            if (paginationWrapper) {
                paginationWrapper.innerHTML = data.pagination;
            }
        }
        
        if (resultsCount && data.results_count) resultsCount.innerHTML = data.results_count;
        
        if (data.stats) {
            const statTotal = document.querySelector('.stat-total');
            const statOnline = document.querySelector('.stat-online');
            const statAssigned = document.querySelector('.stat-assigned');
            if (statTotal) statTotal.textContent = data.stats.agents;
            if (statOnline) statOnline.textContent = data.stats.online_agents;
            if (statAssigned) statAssigned.textContent = data.stats.total_assigned;
        }
        
        attachToggleEvents();
    })
    .catch(error => console.error('Error:', error))
    .finally(() => {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
    });
}
    
    // Toggle status functionality
    function handleToggleClick(event) {
        event.preventDefault();
        const button = this;

        if (button.dataset.self === 'true') {
            showStatusMessage('error', 'You cannot change the status of your own account.');
            return;
        }

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
                showStatusMessage('success', data.message || 'Agent status updated successfully.');
                fetchAgents();
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
                    showStatusMessage('success', data.message || 'Agent deleted successfully.');
                    fetchAgents();
                    closeSingleDeleteModal();
                } else {
                    showStatusMessage('error', data.message || 'Error deleting agent.');
                    closeSingleDeleteModal();
                }
                confirmSingleDeleteBtn.disabled = false;
                confirmSingleDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
            })
            .catch(error => {
                console.error('Delete error:', error);
                showStatusMessage('error', 'An error occurred while deleting the agent.');
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
            typingTimer = setTimeout(fetchAgents, 500);
        });
    }
    
    if (departmentFilter) departmentFilter.addEventListener('change', fetchAgents);
    if (statusFilter) statusFilter.addEventListener('change', fetchAgents);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (departmentFilter) departmentFilter.value = 'all';
            if (statusFilter) statusFilter.value = 'all';
            fetchAgents();
        });
    }
    
    // Initialize
    attachToggleEvents();
});
</script>
@endsection