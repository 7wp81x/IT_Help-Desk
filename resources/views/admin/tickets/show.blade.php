@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    @include('admin.tickets.partials.header', ['ticket' => $ticket])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Ticket -->
            @include('admin.tickets.partials.ticket-content', ['ticket' => $ticket])
            
            <!-- Comments Section -->
            @include('admin.tickets.partials.comments-section', ['ticket' => $ticket])
        </div>

        <!-- Sidebar -->
        @include('admin.tickets.partials.sidebar', ['ticket' => $ticket])
    </div>
</div>

<!-- Modals -->
@include('admin.tickets.partials.modals', ['ticket' => $ticket, 'departments' => $departments ?? [], 'categories' => $categories ?? [], 'agents' => $agents ?? []])

<!-- Delete Comment Script -->
<script>
    function deleteComment(commentId, button) {
    fetch(`/admin/tickets/comment/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const commentDiv = button.closest('[data-comment-id]');
            const content = commentDiv.querySelector('[data-content]');
            content.textContent = 'This message was deleted';
            content.classList.add('italic', 'text-gray-400');
            button.remove();
        } else {
            alert(data.message || 'Failed to delete');
        }
    })
    .catch(err => {
        alert('Error deleting message');
    });
}


</script>
@push('scripts')
<script>
function changeStatus() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function assignAgent() {
    filterAssignOptions();
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}

function filterAssignOptions() {
    const departmentSelect = document.getElementById('departmentSelect');
    const categorySelect = document.getElementById('categorySelect');
    const agentSelect = document.getElementById('agentSelect');
    const selectedDept = departmentSelect.value;

    Array.from(categorySelect.options).forEach(option => {
        if (!option.value) {
            option.hidden = false;
            option.style.display = '';
            return;
        }
        const matches = selectedDept && option.dataset.departmentId === selectedDept;
        option.hidden = !matches;
        option.style.display = matches ? '' : 'none';
    });

    if (categorySelect.value && categorySelect.selectedOptions[0].hidden) {
        categorySelect.value = '';
    }

    Array.from(agentSelect.options).forEach(option => {
        if (!option.value) {
            option.hidden = false;
            option.style.display = '';
            return;
        }
        const matches = selectedDept && option.dataset.departmentId === selectedDept;
        option.hidden = !matches;
        option.style.display = matches ? '' : 'none';
    });

    if (agentSelect.value && agentSelect.selectedOptions[0].hidden) {
        agentSelect.value = '';
    }
}

function submitAssignForm() {
    const form = document.getElementById('assignForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(async response => {
        const text = await response.text();
        let data;
        try {
            data = text ? JSON.parse(text) : {};
        } catch (error) {
            throw new Error('Invalid JSON response from server: ' + error.message);
        }
        if (!response.ok) {
            const message = data.message || 'An error occurred while assigning the ticket.';
            throw new Error(message);
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unable to assign ticket.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while assigning the ticket. ' + error.message);
    });
}

function openImagePreview(event, url, name) {
    event.preventDefault();
    event.stopPropagation();
    const modal = document.getElementById('imagePreviewModal');
    document.getElementById('imagePreviewTitle').textContent = name;
    document.getElementById('imagePreviewContent').src = url;
    document.getElementById('imageDownloadLink').href = url;
    document.getElementById('imageDownloadLink').download = name;
    modal.classList.remove('hidden');
}

function closeImagePreview() {
    const modal = document.getElementById('imagePreviewModal');
    document.getElementById('imagePreviewContent').src = '';
    modal.classList.add('hidden');
}

function openAttachmentPreview(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    let target = event.currentTarget;
    if (!target) return;
    
    const url = target.getAttribute('data-url');
    const name = target.getAttribute('data-name');
    
    if (!url) return;
    
    // Check if it's an image
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
    const ext = name.split('.').pop().toLowerCase();
    
    if (imageExtensions.includes(ext)) {
        // Open image in image modal
        const modal = document.getElementById('imagePreviewModal');
        if (modal) {
            document.getElementById('imagePreviewTitle').textContent = name;
            document.getElementById('imagePreviewContent').src = url;
            document.getElementById('imageDownloadLink').href = url;
            document.getElementById('imageDownloadLink').download = name;
            modal.classList.remove('hidden');
        }
    } else {
        // Open document in document modal
        const modal = document.getElementById('attachmentPreviewModal');
        if (modal) {
            document.getElementById('attachmentPreviewTitle').textContent = name;
            document.getElementById('attachmentPreviewFrame').src = url;
            modal.classList.remove('hidden');
        }
    }
    
    return false;
}

function closeAttachmentPreview() {
    const modal = document.getElementById('attachmentPreviewModal');
    const frame = document.getElementById('attachmentPreviewFrame');
    if (frame) frame.src = '';
    if (modal) modal.classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImagePreview();
        closeAttachmentPreview();
        closeStatusModal();
        closeAssignModal();
    }
});

document.addEventListener('click', function(e) {
    const imageModal = document.getElementById('imagePreviewModal');
    const docModal = document.getElementById('attachmentPreviewModal');
    
    if (e.target === imageModal) closeImagePreview();
    if (e.target === docModal) closeAttachmentPreview();
});

const departmentSelect = document.getElementById('departmentSelect');
if (departmentSelect) {
    departmentSelect.addEventListener('change', filterAssignOptions);
}

if (document.readyState !== 'loading') {
    filterAssignOptions();
} else {
    document.addEventListener('DOMContentLoaded', filterAssignOptions);
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof TicketConversation !== 'undefined') {
        TicketConversation.init(
            {{ $ticket->id }},
            '{{ Auth::user()->role }}',
            {{ json_encode($ticket->comments->max('created_at')) ? "'" . $ticket->comments->max('created_at') . "'" : 'null' }}
        );
    }
});
</script>
@endpush

@endsection