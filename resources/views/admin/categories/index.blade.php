@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Categories</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Organize tickets with searchable categories, status control and bulk actions.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-total">{{ $totalCategories }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-tags text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Active Categories</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-active">{{ $activeCategories }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Inactive Categories</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-inactive">{{ $inactiveCategories }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                    <i class="bi bi-slash-circle text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Categories in Use</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-used">{{ $usedCategories }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <i class="bi bi-list-task text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[220px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text"
                               id="searchInput"
                               placeholder="Search categories by name, slug or description..."
                               value="{{ request('search') }}"
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="w-[160px]">
                    <div class="relative">
                        <i class="bi bi-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="statusFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div>
                    <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
            </div>

            <div id="filterSummary" class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                {{ request('status') === 'active' ? 'Filtering active categories only.' : (request('status') === 'inactive' ? 'Filtering inactive categories only.' : 'Showing all categories.') }}
            </div>

            <div id="loadingIndicator" class="hidden mt-3 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                    <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading categories...
                </div>
            </div>
        </div>

        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
            </p>
        </div>
    </div>

    <div id="bulkActionsBar" class="hidden mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-5 py-3 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="bi bi-check2-square text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        <span id="selectedCount" class="font-semibold text-blue-600 dark:text-blue-400">0</span> category(s) selected
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

    <div id="tableContainer" class="overflow-x-auto shadow-sm rounded-xl">
        <div class="min-w-[800px]">
            @include('admin.categories.partials.table')
        </div>
    </div>
</div>

<!-- Delete Modals -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Category</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="deleteCategoryName" class="font-semibold text-red-600 dark:text-red-400"></span>?<br>
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

<div id="bulkDeleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="bulkDeleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Categories</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="bulkDeleteCount" class="font-semibold text-red-600 dark:text-red-400">0</span> category(s)?<br>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    const filterSummary = document.getElementById('filterSummary');

    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteCategoryName = document.getElementById('deleteCategoryName');

    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteModalContent = document.getElementById('bulkDeleteModalContent');
    const cancelBulkDeleteBtn = document.getElementById('cancelBulkDeleteBtn');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDeleteBtn');
    const bulkDeleteCount = document.getElementById('bulkDeleteCount');

    let pendingDeleteId = null;
    let pendingBulkDeleteIds = [];
    let typingTimer;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateStats(data) {
        const totalEl = document.querySelector('.stat-total');
        const activeEl = document.querySelector('.stat-active');
        const inactiveEl = document.querySelector('.stat-inactive');
        const usedEl = document.querySelector('.stat-used');

        if (totalEl && data.total !== undefined) totalEl.textContent = data.total;
        if (activeEl && data.active !== undefined) activeEl.textContent = data.active;
        if (inactiveEl && data.inactive !== undefined) inactiveEl.textContent = data.inactive;
        if (usedEl && data.used !== undefined) usedEl.textContent = data.used;
        if (resultsCount && data.results_count) resultsCount.textContent = data.results_count;
    }

    function updateBulkActionsBar() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        const totalCheckboxes = document.querySelectorAll('.category-checkbox').length;
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedCountSpan.textContent = count;
        } else {
            bulkActionsBar.classList.add('hidden');
        }

        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count > 0 && count === totalCheckboxes;
            selectAllCheckbox.indeterminate = count > 0 && count < totalCheckboxes;
        }
    }

    function attachCheckboxListeners() {
        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.removeEventListener('change', updateBulkActionsBar);
            cb.addEventListener('change', updateBulkActionsBar);
        });

        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.removeEventListener('change', selectAllHandler);
            selectAllCheckbox.addEventListener('change', selectAllHandler);
        }
    }

    function selectAllHandler(e) {
        const isChecked = e.target.checked;
        document.querySelectorAll('.category-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActionsBar();
    }

    function updateFilterSummary(status) {
        if (!filterSummary) return;

        if (status === 'active') {
            filterSummary.textContent = 'Filtering active categories only.';
        } else if (status === 'inactive') {
            filterSummary.textContent = 'Filtering inactive categories only.';
        } else {
            filterSummary.textContent = 'Showing all categories.';
        }
    }

    function fetchCategories(url = null) {
        if (url instanceof Event) {
            url = null;
        }

        loadingIndicator.classList.remove('hidden');

        const targetUrl = typeof url === 'string' && url.length > 0
            ? url
            : window.location.pathname + '?' + new URLSearchParams({
                search: searchInput?.value || '',
                status: statusFilter?.value || 'all'
            }).toString();

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                console.error('Error fetching categories:', data.message || data.error);
                return;
            }

            if (tableContainer && data.table_html) {
                tableContainer.innerHTML = '<div class="min-w-[800px]">' + data.table_html + '</div>';
            }

            updateStats(data);
            updateFilterSummary(statusFilter?.value || 'all');
            attachCheckboxListeners();
            updateBulkActionsBar();

            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, '', targetUrl);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
        })
        .finally(() => {
            loadingIndicator.classList.add('hidden');
        });
    }

    function openModal(modal, content) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal(modal, content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 200);
    }

    if (document.body) {
        document.body.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.toggle-status-btn');
            if (toggleBtn) {
                e.preventDefault();
                const categoryId = toggleBtn.getAttribute('data-id');
                if (!categoryId) return;

                toggleBtn.disabled = true;
                const originalContent = toggleBtn.innerHTML;
                toggleBtn.innerHTML = '<div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>';

                fetch(`/admin/categories/${categoryId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchCategories();
                    } else {
                        alert('Error: ' + (data.message || 'Unable to update status.'));
                    }
                })
                .catch(error => {
                    console.error('Toggle error:', error);
                    alert('An error occurred while updating category status.');
                })
                .finally(() => {
                    toggleBtn.disabled = false;
                    toggleBtn.innerHTML = originalContent;
                });
            }

            const deleteBtn = e.target.closest('.delete-category-btn');
            if (deleteBtn) {
                e.preventDefault();
                pendingDeleteId = deleteBtn.getAttribute('data-id');
                deleteCategoryName.textContent = deleteBtn.getAttribute('data-name') || 'this category';
                openModal(deleteModal, deleteModalContent);
                return;
            }

            const paginationLink = e.target.closest('.pagination a');
            if (paginationLink && tableContainer && tableContainer.contains(paginationLink)) {
                e.preventDefault();
                fetchCategories(paginationLink.href);
            }
        });
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function() {
            pendingDeleteId = null;
            closeModal(deleteModal, deleteModalContent);
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                pendingDeleteId = null;
                closeModal(deleteModal, deleteModalContent);
            }
        });
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (!pendingDeleteId) return;

            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';

            fetch(`/admin/categories/${encodeURIComponent(pendingDeleteId)}`, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchCategories();
                } else {
                    alert('Error: ' + (data.message || 'Unable to delete category.'));
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('An error occurred while deleting the category.');
            })
            .finally(() => {
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                closeModal(deleteModal, deleteModalContent);
            });
        });
    }

    if (cancelBulkSelect) {
        cancelBulkSelect.addEventListener('click', function() {
            document.querySelectorAll('.category-checkbox').forEach(checkbox => checkbox.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkActionsBar();
        });
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
            pendingBulkDeleteIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (!pendingBulkDeleteIds.length) {
                alert('Please select at least one category to delete.');
                return;
            }

            bulkDeleteCount.textContent = pendingBulkDeleteIds.length;
            openModal(bulkDeleteModal, bulkDeleteModalContent);
        });
    }

    if (cancelBulkDeleteBtn) {
        cancelBulkDeleteBtn.addEventListener('click', function() {
            closeModal(bulkDeleteModal, bulkDeleteModalContent);
        });
    }

    if (bulkDeleteModal) {
        bulkDeleteModal.addEventListener('click', function(e) {
            if (e.target === bulkDeleteModal) {
                closeModal(bulkDeleteModal, bulkDeleteModalContent);
            }
        });
    }

    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', function() {
            if (!pendingBulkDeleteIds.length) {
                closeModal(bulkDeleteModal, bulkDeleteModalContent);
                return;
            }

            confirmBulkDeleteBtn.disabled = true;
            confirmBulkDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';

            fetch('{{ route('admin.categories.bulk-destroy') }}', {
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
                    fetchCategories();
                } else {
                    alert('Error: ' + (data.message || 'Unable to delete selected categories.'));
                }
            })
            .catch(error => {
                console.error('Bulk delete error:', error);
                alert('An error occurred while deleting selected categories.');
            })
            .finally(() => {
                confirmBulkDeleteBtn.disabled = false;
                confirmBulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                closeModal(bulkDeleteModal, bulkDeleteModalContent);
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchCategories, 500);
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            fetchCategories();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = 'all';
            fetchCategories();
        });
    }

    attachCheckboxListeners();
    updateBulkActionsBar();
});
</script>
<style>
    #tableContainer { transition: opacity 0.2s ease; }
    .animate-spin { animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    #deleteModalContent, #bulkDeleteModalContent { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
@endpush
@endsection
