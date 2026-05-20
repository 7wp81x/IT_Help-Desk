<!-- Include Ticket Conversation System -->
<script src="{{ asset('js/ticket-conversation.js') }}"></script>

<script>
function openImagePreview(event, url, name) {
    event.preventDefault();
    event.stopPropagation();
    const modal = document.getElementById('imagePreviewModal');
    document.getElementById('imagePreviewTitle').textContent = name;
    document.getElementById('imagePreviewContent').src = url;
    document.getElementById('imageDownloadLink').href = url;
    document.getElementById('imageDownloadLink').download = name;
    modal.classList.remove('hidden');
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeImagePreview();
        }
    });
}

function closeImagePreview() {
    const modal = document.getElementById('imagePreviewModal');
    document.getElementById('imagePreviewContent').src = '';
    modal.classList.add('hidden');
}

function openAttachmentPreview(event, url, name) {
    event.preventDefault();
    event.stopPropagation();
    const modal = document.getElementById('attachmentPreviewModal');
    if (!modal) return;
    document.getElementById('attachmentPreviewTitle').textContent = name;
    const frame = document.getElementById('attachmentPreviewFrame');
    if (frame) {
        frame.src = url;
    }
    modal.classList.remove('hidden');
}

function closeAttachmentPreview() {
    const modal = document.getElementById('attachmentPreviewModal');
    document.getElementById('attachmentPreviewFrame').src = '';
    modal.classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImagePreview();
        closeAttachmentPreview();
    }
});

document.addEventListener('click', function(e) {
    const imageModal = document.getElementById('imagePreviewModal');
    const docModal = document.getElementById('attachmentPreviewModal');
    
    if (e.target === imageModal) closeImagePreview();
    if (e.target === docModal) closeAttachmentPreview();
});

document.addEventListener('DOMContentLoaded', function() {
    TicketConversation.init(
        {{ $ticket->id }},
        '{{ Auth::user()->role }}',
        {{ json_encode($ticket->comments->max('created_at')) ? "'" . $ticket->comments->max('created_at') . "'" : 'null' }}
    );
});
</script>