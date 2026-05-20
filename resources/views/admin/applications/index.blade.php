@extends('layouts.app')

@section('title', 'Agent Applications')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Agent Applications</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Search, filter, and review incoming agent applications</p>
        </div>
    </div>

    <!-- Orphaned Applications Warning -->
    @if($orphanedCount > 0)
    <div class="mb-6 rounded-xl border-l-4 border-amber-500 bg-amber-50 p-4 dark:border-amber-600 dark:bg-amber-950/30">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    <i class="bi bi-exclamation-triangle text-amber-600 dark:text-amber-400 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-amber-900 dark:text-amber-200">Orphaned Applications Detected</h3>
                    <p class="text-sm text-amber-800 dark:text-amber-300 mt-1">
                        There {{ $orphanedCount === 1 ? 'is' : 'are' }} <strong>{{ $orphanedCount }}</strong> approved application(s) without corresponding agent account(s). These should be cleaned up.
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.applications.cleanup-orphaned') }}" class="flex-shrink-0">
                @csrf
                <button type="submit" onclick="return confirm('Remove {{ $orphanedCount }} orphaned application(s)? This cannot be undone.')" 
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium transition">
                    <i class="bi bi-trash"></i> Cleanup
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Applications</p>
                    <p class="stat-total text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-file-text text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="stat-pending text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $pendingCount }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-clock-history text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Approved</p>
                    <p class="stat-approved text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $approvedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Rejected</p>
                    <p class="stat-rejected text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $rejectedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Automatic filtering - no submit button) -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               id="searchInput"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or email..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-funnel absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="statusFilter" name="status" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                    Loading applications...
                </div>
            </div>
        </div>
        
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} applications
            </p>
        </div>
    </div>

    <div id="bulkActionsBar" class="hidden mb-6 rounded-xl border border-gray-200 bg-white px-4 py-4 shadow-sm transition duration-200 dark:border-gray-700 dark:bg-gray-800">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <span id="selectedCount">0</span> application(s) selected
            </p>
            <div class="flex flex-wrap gap-3">
                <button id="cancelBulkSelect" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                    Cancel Selection
                </button>
                <button id="bulkDeleteBtn" type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                    Delete Selected
                </button>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div id="tableContainer" class="overflow-x-auto shadow-sm rounded-xl">
        @include('admin.applications.partials.table')
    </div>
</div>

<!-- Delete Application Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Application</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="deleteApplicationName" class="font-semibold text-red-600 dark:text-red-400"></span>?<br>
                This action cannot be undone.
            </p>
            <form id="deleteApplicationForm" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')
            </form>
            <div class="flex gap-3">
                <button id="cancelDeleteBtn" type="button"
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    Cancel
                </button>
                <button id="confirmDeleteBtn" type="button"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');
    let typingTimer;
    
    function fetchApplications() {
        loadingIndicator.classList.remove('hidden');
        if (tableContainer) tableContainer.style.opacity = '0.5';
        
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            status: statusFilter?.value || ''
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
            
            // Update results count
            if (resultsCount && data.results_count) {
                resultsCount.textContent = data.results_count;
            }
            
            // Update stats
            if (data.stats) {
                const totalEl = document.querySelector('.stat-total');
                const pendingEl = document.querySelector('.stat-pending');
                const approvedEl = document.querySelector('.stat-approved');
                const rejectedEl = document.querySelector('.stat-rejected');
                
                if (totalEl && data.stats.total !== undefined) totalEl.textContent = data.stats.total;
                if (pendingEl && data.stats.pending !== undefined) pendingEl.textContent = data.stats.pending;
                if (approvedEl && data.stats.approved !== undefined) approvedEl.textContent = data.stats.approved;
                if (rejectedEl && data.stats.rejected !== undefined) rejectedEl.textContent = data.stats.rejected;
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
        })
        .finally(() => {
            loadingIndicator.classList.add('hidden');
        });
    }
    
    // Auto-filter on input (with debounce)
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchApplications, 500);
        });
    }
    
    // Auto-filter on status change
    if (statusFilter) {
        statusFilter.addEventListener('change', fetchApplications);
    }
    
    // Reset filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            fetchApplications();
        });
    }

    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const cancelBulkSelect = document.getElementById('cancelBulkSelect');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const deleteApplicationName = document.getElementById('deleteApplicationName');
    const deleteApplicationForm = document.getElementById('deleteApplicationForm');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Function to open modal (centered)
    function openDeleteModal() {
        if (!deleteModal || !deleteModalContent) return;
        
        // Remove hidden class and add flex to center
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        
        // Animate in
        setTimeout(() => {
            deleteModalContent.classList.remove('scale-95', 'opacity-0');
            deleteModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    // Function to close modal
    function closeDeleteModal() {
        if (!deleteModal || !deleteModalContent) return;
        
        // Animate out
        deleteModalContent.classList.remove('scale-100', 'opacity-100');
        deleteModalContent.classList.add('scale-95', 'opacity-0');
        
        // Hide modal after animation
        setTimeout(() => {
            deleteModal.classList.remove('flex');
            deleteModal.classList.add('hidden');
        }, 200);
    }

    // Delete button handler
    document.addEventListener('click', function(event) {
        const deleteBtn = event.target.closest('.open-delete-modal-btn');
        if (!deleteBtn) return;

        event.preventDefault();
        if (!deleteApplicationForm || !deleteApplicationName) return;

        deleteApplicationForm.action = deleteBtn.dataset.deleteUrl;
        deleteApplicationName.textContent = deleteBtn.dataset.deleteTitle || 'this application';

        openDeleteModal();
    });

    // Cancel button
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);
    }

    // Click outside to close
    if (deleteModal) {
        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });
    }

    // Confirm delete
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (deleteApplicationForm) {
                deleteApplicationForm.submit();
            }
        });
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
        const count = checkedBoxes.length;
        if (bulkActionsBar) {
            bulkActionsBar.classList.toggle('hidden', count === 0);
        }
        if (selectedCountSpan) {
            selectedCountSpan.textContent = count;
        }
    }

    function getSelectedApplicationIds() {
        return Array.from(document.querySelectorAll('.application-checkbox:checked')).map(cb => cb.value);
    }

    document.addEventListener('change', function(event) {
        if (!event.target) return;
        if (event.target.classList && event.target.classList.contains('application-checkbox')) {
            updateBulkActions();
        }
        if (event.target.id === 'selectAllCheckbox') {
            const checked = event.target.checked;
            document.querySelectorAll('.application-checkbox:not(:disabled)').forEach(cb => {
                cb.checked = checked;
            });
            updateBulkActions();
        }
    });

    if (cancelBulkSelect) {
        cancelBulkSelect.addEventListener('click', function() {
            document.querySelectorAll('.application-checkbox').forEach(cb => cb.checked = false);
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            updateBulkActions();
        });
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const ids = getSelectedApplicationIds();
            if (!ids.length) {
                return;
            }

            if (!confirm('Delete the selected application(s)? This cannot be undone.')) {
                return;
            }

            fetch('{{ route('admin.applications.bulk-destroy') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ application_ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Unable to delete selected applications.');
                }
            })
            .catch(error => {
                console.error('Bulk delete error:', error);
                alert('An error occurred while deleting applications.');
            });
        });
    }
});
</script>

<style>
    #tableContainer { transition: opacity 0.2s ease; }
    .animate-spin { animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection