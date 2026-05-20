<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('user.tickets.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
            ← Back to Tickets
        </a>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->ticket_number }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $ticket->subject ?? '' }}</p>
        </div>
        
        <div class="flex gap-2 items-center">
            @if($ticket->status === 'open')
                <button type="button" onclick="openCancelModal()"
                        class="px-3 py-1.5 text-sm bg-orange-600 hover:bg-orange-700 text-white rounded-md transition">
                    Cancel Ticket
                </button>
            @endif

            @if($ticket->status === 'resolved')
                <button type="button" onclick="openCloseModal()"
                        class="px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                    Close Ticket
                </button>
            @endif

            @if(in_array($ticket->status, ['resolved', 'closed', 'canceled']))
                <button type="button" onclick="openDeleteModal()"
                        class="px-3 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                    Delete
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="cancelForm" action="{{ route('user.tickets.cancel', $ticket) }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="closeForm" action="{{ route('user.tickets.update-status', $ticket) }}" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="closed">
</form>

<form id="deleteForm" action="{{ route('user.tickets.destroy', $ticket) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="cancelModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="bi bi-x-circle text-3xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Cancel Ticket</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Cancel ticket <span class="font-semibold text-orange-600 dark:text-orange-400">{{ $ticket->ticket_number }}</span>?<br>
                This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button onclick="closeCancelModal()" 
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    No, Go Back
                </button>
                <button onclick="submitCancel()" 
                        class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-x-circle"></i>
                    Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Close Modal -->
<div id="closeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="closeModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-check-circle text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Close Ticket</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Close ticket <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $ticket->ticket_number }}</span>?<br>
                You can reopen it later if needed.
            </p>
            <div class="flex gap-3">
                <button onclick="closeCloseModal()" 
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    Cancel
                </button>
                <button onclick="submitClose()" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-check-lg"></i>
                    Yes, Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-200">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Ticket</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-6">
                Delete ticket <span class="font-semibold text-red-600 dark:text-red-400">{{ $ticket->ticket_number }}</span>?<br>
                This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                    Cancel
                </button>
                <button onclick="submitDelete()" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-medium flex items-center justify-center gap-2">
                    <i class="bi bi-trash"></i>
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId, modalContentId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalContentId);
    if (modal && content) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function closeModal(modalId, modalContentId) {
    const content = document.getElementById(modalContentId);
    if (content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
    }
    const modal = document.getElementById(modalId);
    if (modal) {
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 200);
    }
}

// Cancel Modal functions
function openCancelModal() {
    openModal('cancelModal', 'cancelModalContent');
}
function closeCancelModal() {
    closeModal('cancelModal', 'cancelModalContent');
}
function submitCancel() {
    document.getElementById('cancelForm').submit();
}

// Close Modal functions
function openCloseModal() {
    openModal('closeModal', 'closeModalContent');
}
function closeCloseModal() {
    closeModal('closeModal', 'closeModalContent');
}
function submitClose() {
    document.getElementById('closeForm').submit();
}

// Delete Modal functions
function openDeleteModal() {
    openModal('deleteModal', 'deleteModalContent');
}
function closeDeleteModal() {
    closeModal('deleteModal', 'deleteModalContent');
}
function submitDelete() {
    document.getElementById('deleteForm').submit();
}

// Close modal when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            const modalId = this.id;
            const contentId = modalId + 'Content';
            closeModal(modalId, contentId);
        }
    });
});

// Escape key to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal.flex');
        openModals.forEach(modal => {
            const modalId = modal.id;
            const contentId = modalId + 'Content';
            closeModal(modalId, contentId);
        });
    }
});
</script>