<!-- Single Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete User</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="deleteUserName" class="font-semibold text-red-600 dark:text-red-400"></span>?<br>
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
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    let pendingDeleteId = null;
    let pendingBulkDeleteIds = [];
    
    // Modal elements
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteUserNameSpan = document.getElementById('deleteUserName');
    
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteModalContent = document.getElementById('bulkDeleteModalContent');
    const cancelBulkDeleteBtn = document.getElementById('cancelBulkDeleteBtn');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDeleteBtn');
    const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
    
    // Single Delete Modal
    window.showDeleteModal = function(userId, userName) {
        pendingDeleteId = userId;
        deleteUserNameSpan.textContent = userName;
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        setTimeout(() => {
            deleteModalContent.classList.remove('scale-95', 'opacity-0');
            deleteModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    };
    
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
            if (!pendingDeleteId) return;
            
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            fetch('/admin/users/' + pendingDeleteId + '/ajax', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
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
                alert('An error occurred');
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                closeSingleModal();
            });
        });
    }
    
    // Bulk Delete
    if (cancelBulkDeleteBtn) cancelBulkDeleteBtn.addEventListener('click', closeBulkModal);
    if (bulkDeleteModal) bulkDeleteModal.addEventListener('click', function(e) {
        if (e.target === bulkDeleteModal) closeBulkModal();
    });
    
    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', function() {
            if (pendingBulkDeleteIds.length === 0) {
                closeBulkModal();
                return;
            }
            
            confirmBulkDeleteBtn.disabled = true;
            confirmBulkDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            fetch('/admin/users/bulk-destroy', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ user_ids: pendingBulkDeleteIds.join(',') })
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
                console.error('Error:', error);
                alert('An error occurred');
                confirmBulkDeleteBtn.disabled = false;
                confirmBulkDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                closeBulkModal();
            });
        });
    }
    
    // Bulk selection logic for all tabs
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const cancelBulkSelect = document.getElementById('cancelBulkSelect');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
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
                selectAllCheckbox.indeterminate = true;
            }
        }
    }
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionsBar();
        });
    }
    
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList && e.target.classList.contains('user-checkbox')) {
            updateBulkActionsBar();
        }
    });
    
    if (cancelBulkSelect) {
        cancelBulkSelect.addEventListener('click', function() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkActionsBar();
        });
    }
    
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            pendingBulkDeleteIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (pendingBulkDeleteIds.length === 0) {
                alert('Please select at least one user to delete.');
                return;
            }
            
            if (bulkDeleteCountSpan) bulkDeleteCountSpan.textContent = pendingBulkDeleteIds.length;
            bulkDeleteModal.classList.remove('hidden');
            bulkDeleteModal.classList.add('flex');
            setTimeout(() => {
                bulkDeleteModalContent.classList.remove('scale-95', 'opacity-0');
                bulkDeleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        });
    }
    
    updateBulkActionsBar();
});
</script>

<style>
    .animate-spin { animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    
    #deleteModalContent, #bulkDeleteModalContent {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>