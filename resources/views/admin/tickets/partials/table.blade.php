<!-- Tickets Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket List</h3>
        <a href="{{ route('admin.tickets.create') }}" 
           class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
            <i class="bi bi-plus-circle"></i> New Ticket
        </a>
    </div>
    <div class="overflow-x-auto">
        <div class="min-w-[1100px]">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-4 w-12">
                            <input type="checkbox" id="selectAllCheckbox" 
                                class="appearance-none w-4 h-4 bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:border-red-500 checked:bg-transparent relative
                                after:content-['\2713'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[200px]">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Requester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Agent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Created</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                        <td class="px-4 py-4">
                            <input type="checkbox" 
                                class="ticket-checkbox appearance-none w-4 h-4 bg-transparent dark:bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:bg-transparent checked:border-red-500 relative after:content-['\2713'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2" 
                                value="{{ $ticket->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">#{{ $ticket->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                               class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block max-w-[250px]" 
                               title="{{ $ticket->subject }}">
                                {{ \Illuminate\Support\Str::limit($ticket->subject, 50) }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($ticket->user->name ?? 'N/A', 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[120px]" title="{{ $ticket->user->name ?? 'N/A' }}">
                                    {{ $ticket->user->name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'assigned' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'pending' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'pending_user_response' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'pending_admin_approval' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'resolved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'closed' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                    'canceled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'escalated' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $statusIcons = [
                                    'open' => 'bi bi-circle',
                                    'assigned' => 'bi bi-person-check',
                                    'in_progress' => 'bi bi-arrow-repeat',
                                    'pending' => 'bi bi-clock',
                                    'pending_user_response' => 'bi bi-person-clock',
                                    'pending_admin_approval' => 'bi bi-shield-lock',
                                    'resolved' => 'bi bi-check-circle-fill',
                                    'closed' => 'bi bi-check-circle',
                                    'canceled' => 'bi bi-x-circle',
                                    'escalated' => 'bi bi-exclamation-triangle-fill',
                                ];
                                $statusText = ucfirst(str_replace('_', ' ', $ticket->status));
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }} whitespace-nowrap">
                                <i class="{{ $statusIcons[$ticket->status] ?? 'bi bi-circle' }} text-xs"></i>
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    'medium' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'urgent' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $priorityIcons = [
                                    'low' => 'bi bi-arrow-down-circle-fill',
                                    'medium' => 'bi bi-circle',
                                    'high' => 'bi bi-arrow-up-circle-fill',
                                    'urgent' => 'bi bi-exclamation-triangle-fill',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg {{ $priorityColors[$ticket->priority] ?? 'bg-blue-100 text-blue-700' }} whitespace-nowrap">
                                <i class="{{ $priorityIcons[$ticket->priority] ?? 'bi bi-circle' }} text-xs"></i>
                                {{ ucfirst($ticket->priority ?? 'Normal') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 whitespace-nowrap">
                                <i class="bi bi-tag text-xs"></i>
                                {{ $ticket->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($ticket->assignedAgent)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($ticket->assignedAgent->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[100px]" title="{{ $ticket->assignedAgent->name }}">
                                        {{ $ticket->assignedAgent->name }}
                                    </span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 whitespace-nowrap">
                                    <i class="bi bi-person-circle text-xs"></i>
                                    Unassigned
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $ticket->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
                                   title="View Ticket">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                
                                <!-- Edit button - Hidden when status is canceled -->
                                @if($ticket->status !== 'canceled')
                                <a href="{{ route('admin.tickets.edit', $ticket->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                                   title="Edit Ticket">
                                    <i class="bi bi-pencil-square text-sm"></i>
                                </a>
                                @endif
                                
                                <!-- Delete button - Always visible -->
                                <button type="button" 
                                        class="delete-ticket-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                        data-id="{{ $ticket->id }}"
                                        data-name="{{ addslashes($ticket->subject) }}"
                                        title="Delete Ticket">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <i class="bi bi-ticket text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No tickets found</p>
                                <a href="{{ route('admin.tickets.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                                    <i class="bi bi-plus-circle"></i> Create your first ticket
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $tickets->links() }}
    </div>
    @endif

    <form id="ticketDeleteForm" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>


<!-- Bulk Actions Bar -->
<div id="bulkActionsBar" class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 hidden mb-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-5 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-check2-square text-blue-600 dark:text-blue-400 text-sm"></i>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    <span id="selectedCount" class="font-semibold text-blue-600 dark:text-blue-400">0</span> ticket(s) selected
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

<!-- Single Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Ticket</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete "<span id="deleteTicketName" class="font-semibold text-red-600 dark:text-red-400"></span>"?<br>
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
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Tickets</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="bulkDeleteCount" class="font-semibold text-red-600 dark:text-red-400">0</span> ticket(s)?<br>
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
    #deleteModalContent, #bulkDeleteModalContent {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    #bulkActionsBar {
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translate(-50%, 20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const cancelBulkSelect = document.getElementById('cancelBulkSelect');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    
    // Modal elements
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteTicketNameSpan = document.getElementById('deleteTicketName');
    
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    const bulkDeleteModalContent = document.getElementById('bulkDeleteModalContent');
    const cancelBulkDeleteBtn = document.getElementById('cancelBulkDeleteBtn');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDeleteBtn');
    const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
    
    const deleteForm = document.getElementById('ticketDeleteForm');
    
    let pendingDeleteId = null;
    let pendingBulkDeleteIds = [];
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Function to update bulk actions bar
    function updateBulkActionsBar() {
        const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedCountSpan.textContent = count;
        } else {
            bulkActionsBar.classList.add('hidden');
        }
        
        if (selectAllCheckbox) {
            const totalCheckboxes = document.querySelectorAll('.ticket-checkbox').length;
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
        document.querySelectorAll('.ticket-checkbox').forEach(cb => {
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
        document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActionsBar();
    }
    
    // Single Delete Modal Functions
    function openSingleModal(ticketId, ticketName) {
        pendingDeleteId = ticketId;
        deleteTicketNameSpan.textContent = ticketName;
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        setTimeout(() => {
            deleteModalContent.classList.remove('scale-95', 'opacity-0');
            deleteModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
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
    
    // Delete button handlers
    const deleteButtons = document.querySelectorAll('.delete-ticket-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const ticketId = this.dataset.id;
            const ticketName = this.dataset.name || 'this ticket';
            openSingleModal(ticketId, ticketName);
        });
    });
    
    // Single delete confirm
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (!pendingDeleteId) {
                alert('Invalid ticket selected for deletion.');
                return;
            }
            
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Deleting...';
            
            if (deleteForm) {
                deleteForm.action = `/admin/tickets/${pendingDeleteId}`;
                deleteForm.submit();
            } else {
                // If no form, use fetch
                fetch(`/admin/tickets/${pendingDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
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
                    alert('An error occurred while deleting the ticket.');
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = '<i class="bi bi-trash"></i> Delete';
                    closeSingleModal();
                });
            }
        });
    }
    
    // Cancel single delete
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', closeSingleModal);
    }
    
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) closeSingleModal();
        });
    }
    
    // Bulk Select Cancel
    if (cancelBulkSelect) {
        cancelBulkSelect.addEventListener('click', function() {
            document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkActionsBar();
        });
    }
    
    // Bulk Delete button click
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
            pendingBulkDeleteIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (pendingBulkDeleteIds.length === 0) {
                alert('Please select at least one ticket to delete.');
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
    
    // Cancel bulk delete
    if (cancelBulkDeleteBtn) {
        cancelBulkDeleteBtn.addEventListener('click', closeBulkModal);
    }
    
    if (bulkDeleteModal) {
        bulkDeleteModal.addEventListener('click', function(e) {
            if (e.target === bulkDeleteModal) closeBulkModal();
        });
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
            
            const bulkDeleteUrl = '{{ route("admin.tickets.bulk.delete") }}';
            
            fetch(bulkDeleteUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ticket_ids: pendingBulkDeleteIds })
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
    
    // Initialize
    attachCheckboxListeners();
    updateBulkActionsBar();
});
</script>
@endpush