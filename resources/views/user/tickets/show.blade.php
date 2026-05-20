@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    @include('user.tickets.partials.header', ['ticket' => $ticket])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Ticket -->
            @include('user.tickets.partials.ticket-content', ['ticket' => $ticket])
            
            <!-- Comments Section with Scroll -->
            @include('user.tickets.partials.comments-section', ['ticket' => $ticket])
        </div>

        <!-- Sidebar -->
        @include('user.tickets.partials.sidebar', ['ticket' => $ticket])
    </div>
</div>

<!-- Modals -->
@include('user.tickets.partials.modals')

<!-- Delete Comment Script -->
<script>
function deleteComment(commentId, button) {
    fetch(`/user/tickets/comment/${commentId}`, {
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

<!-- Scripts -->
@include('user.tickets.partials.scripts', ['ticket' => $ticket])
@endsection