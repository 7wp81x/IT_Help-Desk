@extends('layouts.app')

@section('title', $pageTitle ?? 'Department Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pageTitle ?? 'Department Management' }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage system departments and organizational units</p>
        </div>
      
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Departments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-total">{{ $totalDepartments }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-building text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Active Departments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-active">{{ $activeDepartments }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Inactive Departments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-inactive">{{ $inactiveDepartments }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                    <i class="bi bi-slash-circle text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Staffed Departments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-staffed">{{ $staffedDepartments }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-person-workspace text-purple-600 dark:text-purple-400 text-xl"></i>
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
                               placeholder="Search departments by name or description..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
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
                    <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading departments...
                </div>
            </div>
        </div>
        
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }} departments
            </p>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="hidden mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-5 py-3 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="bi bi-check2-square text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        <span id="selectedCount" class="font-semibold text-blue-600 dark:text-blue-400">0</span> department(s) selected
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

    <!-- Departments Table Container with Horizontal Scroll -->
    <div id="tableContainer" class="overflow-x-auto shadow-sm rounded-xl">
        <div class="min-w-[800px]">
            @include('admin.departments.partials.table')
        </div>
    </div>
</div>

<!-- Single Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Department</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="deleteDeptName" class="font-semibold text-red-600 dark:text-red-400"></span>?<br>
                This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button id="cancelDeleteBtn" 
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    Cancel
                </button>
                <button id="confirmDeleteBtn" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="bulkDeleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Departments</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="bulkDeleteCount" class="font-semibold text-red-600 dark:text-red-400">0</span> department(s)?<br>
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

<style>
    #tableContainer { 
        transition: opacity 0.2s ease;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Custom scrollbar styling */
    #tableContainer::-webkit-scrollbar {
        height: 6px;
    }
    
    #tableContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #tableContainer::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    #tableContainer::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .animate-spin { 
        animation: spin 0.6s linear infinite; 
    }
    
    @keyframes spin { 
        to { transform: rotate(360deg); } 
    }
    
    #deleteModalContent, #bulkDeleteModalContent {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Ensure text doesn't get cut off */
    .department-checkbox {
        flex-shrink: 0;
    }
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const cancelBulkSelect = document.getElementById('cancelBulkSelect');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');
    
    // Modal elements
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteDeptNameSpan = document.getElementById('deleteDeptName');
    
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteModalContent = document.getElementById('bulkDeleteModalContent');
    const cancelBulkDeleteBtn = document.getElementById('cancelBulkDeleteBtn');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDeleteBtn');
    const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
    
    let pendingDeleteId = null;
    let pendingBulkDeleteIds = [];
    let typingTimer;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Function to update stats
    function updateStats(data) {
        const totalEl = document.querySelector('.stat-total');
        const activeEl = document.querySelector('.stat-active');
        const inactiveEl = document.querySelector('.stat-inactive');
        const staffedEl = document.querySelector('.stat-staffed');
        
        if (totalEl && data.total !== undefined) totalEl.textContent = data.total;
        if (activeEl && data.active !== undefined) activeEl.textContent = data.active;
        if (inactiveEl && data.inactive !== undefined) inactiveEl.textContent = data.inactive;
        if (staffedEl && data.staffed !== undefined) staffedEl.textContent = data.staffed;
        if (resultsCount && data.results_count) resultsCount.textContent = data.results_count;
    }
    
    // Function to update bulk actions bar
    function updateBulkActionsBar() {
        const checkedBoxes = document.querySelectorAll('.department-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedCountSpan.textContent = count;
        } else {
            bulkActionsBar.classList.add('hidden');
        }
        
        if (selectAllCheckbox) {
            const totalCheckboxes = document.querySelectorAll('.department-checkbox').length;
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
    
    // Function to attach checkbox listeners
    function attachCheckboxListeners() {
        document.querySelectorAll('.department-checkbox').forEach(cb => {
            cb.removeEventListener('change', updateBulkActionsBar);
            cb.addEventListener('change', updateBulkActionsBar);
        });
        
        const newSelectAll = document.getElementById('selectAllCheckbox');
        if (newSelectAll) {
            newSelectAll.removeEventListener('change', selectAllHandler);
            newSelectAll.addEventListener('change', selectAllHandler);
        }
    }
    
    function selectAllHandler(e) {
        const isChecked = e.target.checked;
        document.querySelectorAll('.department-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActionsBar();
    }
    
    // Fetch departments function
    function fetchDepartments() {
        loadingIndicator.classList.remove('hidden');
        if (tableContainer) tableContainer.style.opacity = '0.5';
        
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            status: statusFilter?.value || 'all'
        });
        
        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                console.error('Error:', data.error);
                return;
            }
            
            if (tableContainer && data.table_html) {
                tableContainer.innerHTML = data.table_html;
                tableContainer.style.opacity = '1';
            }
            
            updateStats(data);
            updateBulkActionsBar();
            attachCheckboxListeners();
        })
        .catch(error => {
            console.error('Fetch Error:', error);
        })
        .finally(() => {
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        });
    }
    
    // ========== TOGGLE DEPARTMENT STATUS (EVENT DELEGATION) ==========
        document.addEventListener('click', function(e) {
        // Toggle Status Button
        const toggleBtn = e.target.closest('.toggle-status-btn');
        if (toggleBtn) {
            e.preventDefault();
            const departmentId = toggleBtn.getAttribute('data-id');
            
            // Debug log
            console.log('Toggle button clicked - Department ID:', departmentId);
            
            if (!departmentId) {
                console.error('Department ID not found on button');
                alert('Error: Department ID not found');
                return;
            }
            
            const originalHtml = toggleBtn.innerHTML;
            
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = '<div class="w-4 h-4 border-2 border-gray-600 border-t-transparent rounded-full animate-spin mx-auto"></div>';
            
            const url = '/admin/departments/' + departmentId + '/toggle';
            console.log('Fetching URL:', url);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update the button appearance
                    const row = toggleBtn.closest('tr');
                    const statusBadge = row.querySelector('.status-badge');
                    const userCount = row.querySelector('.inline-flex.items-center.gap-1\\.5.px-2\\.5.py-1.bg-blue-100');
                    
                    if (data.is_active) {
                        // Now active
                        toggleBtn.setAttribute('data-active', 'true');
                        toggleBtn.setAttribute('title', 'Deactivate Department');
                        toggleBtn.innerHTML = `
                            <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 flex items-center justify-center">
                                <i class="bi bi-toggle-on text-base"></i>
                            </div>
                        `;
                        statusBadge.className = 'status-badge inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                        statusBadge.innerHTML = '<i class="bi bi-check-circle-fill text-xs"></i> Active';
                    } else {
                        // Now inactive
                        toggleBtn.setAttribute('data-active', 'false');
                        toggleBtn.setAttribute('title', 'Activate Department');
                        toggleBtn.innerHTML = `
                            <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center">
                                <i class="bi bi-toggle-off text-base"></i>
                            </div>
                        `;
                        statusBadge.className = 'status-badge inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
                        statusBadge.innerHTML = '<i class="bi bi-circle text-xs"></i> Inactive';
                    }
                    
                    // Update stats if visible
                    if (data.stats) {
                        updateStats(data.stats);
                    }
                    
                    toggleBtn.disabled = false;
                } else {
                    alert('Error: ' + data.message);
                    toggleBtn.disabled = false;
                    toggleBtn.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while toggling department status: ' + error.message);
                toggleBtn.disabled = false;
                toggleBtn.innerHTML = originalHtml;
            });
            return;
        }
        
        // Delete Button
        const deleteBtn = e.target.closest('.delete-dept-btn');
        if (deleteBtn) {
            e.preventDefault();
            const departmentId = deleteBtn.getAttribute('data-id');
            const departmentName = deleteBtn.getAttribute('data-name');
            
            if (!departmentId) {
                console.error('Delete button missing department ID', deleteBtn);
                alert('Unable to delete department. Missing department ID.');
                return;
            }
            
            pendingDeleteId = departmentId;
            deleteDeptNameSpan.textContent = departmentName;
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
            setTimeout(() => {
                deleteModalContent.classList.remove('scale-95', 'opacity-0');
                deleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            return;
        }
    });

    
    // Single Delete Modal Functions
    function closeSingleModal() {
        deleteModalContent.classList.remove('scale-100', 'opacity-100');
        deleteModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            deleteModal.classList.remove('flex');
            deleteModal.classList.add('hidden');
            pendingDeleteId = null;
        }, 200);
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
    
    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeSingleModal);
    if (deleteModal) deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) closeSingleModal();
    });
    
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (!pendingDeleteId) {
                alert('Invalid department selected for deletion.');
                return;
            }
            
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            const deleteUrl = `/admin/departments/${encodeURIComponent(pendingDeleteId)}`;
            console.log('Deleting department URL:', deleteUrl);
            
            fetch(deleteUrl, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP status ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                    closeSingleModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the department: ' + error.message);
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                closeSingleModal();
            });
        });
    }
    
    // Bulk Delete
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', selectAllHandler);
    }
    
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList && e.target.classList.contains('department-checkbox')) {
            updateBulkActionsBar();
        }
    });
    
    if (cancelBulkSelect) {
        cancelBulkSelect.addEventListener('click', function() {
            document.querySelectorAll('.department-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkActionsBar();
        });
    }
    
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.department-checkbox:checked');
            pendingBulkDeleteIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (pendingBulkDeleteIds.length === 0) {
                alert('Please select at least one department to delete.');
                return;
            }
            
            if (bulkDeleteCountSpan) {
                bulkDeleteCountSpan.textContent = pendingBulkDeleteIds.length;
            }
            
            bulkDeleteModal.classList.remove('hidden');
            bulkDeleteModal.classList.add('flex');
            setTimeout(() => {
                bulkDeleteModalContent.classList.remove('scale-95', 'opacity-0');
                bulkDeleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        });
    }
    
    if (cancelBulkDeleteBtn) {
        cancelBulkDeleteBtn.addEventListener('click', closeBulkModal);
    }
    
    if (bulkDeleteModal) {
        bulkDeleteModal.addEventListener('click', function(e) {
            if (e.target === bulkDeleteModal) closeBulkModal();
        });
    }
    
    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', function() {
            if (pendingBulkDeleteIds.length === 0) {
                closeBulkModal();
                return;
            }
            
            confirmBulkDeleteBtn.disabled = true;
            confirmBulkDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            const bulkDeleteUrl = '{{ route("admin.departments.bulk-destroy") }}';
            fetch(bulkDeleteUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: pendingBulkDeleteIds.join(',') })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    confirmBulkDeleteBtn.disabled = false;
                    confirmBulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                    closeBulkModal();
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('An error occurred: ' + error.message);
                confirmBulkDeleteBtn.disabled = false;
                confirmBulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                closeBulkModal();
            });
        });
    }
    
    // Event listeners for filters
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchDepartments, 500);
        });
    }
    
    if (statusFilter) statusFilter.addEventListener('change', fetchDepartments);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = 'all';
            fetchDepartments();
        });
    }
    
    // Initialize
    attachCheckboxListeners();
    updateBulkActionsBar();
});
</script>

<style>
    #tableContainer { transition: opacity 0.2s ease; }
    .animate-spin { animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    
    #deleteModalContent, #bulkDeleteModalContent {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endsection