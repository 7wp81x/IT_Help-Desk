<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Conversation</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $ticket->comments->count() }} comments</p>
    </div>

    <!-- New Message Indicator -->
    <div data-new-message-indicator class="hidden flex items-center justify-center p-3 bg-blue-50 dark:bg-blue-900 border-b border-blue-200 dark:border-blue-700 text-blue-800 dark:text-blue-100 text-sm">
        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        New message received
    </div>
    
    <!-- Scrollable Comments Area -->
    <div class="p-5 max-h-96 overflow-y-auto space-y-4" data-comments-container>
        @forelse($ticket->comments as $comment)
            @include('admin.tickets.partials.comment-item', ['comment' => $comment])
        @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                No comments yet.
            </div>
        @endforelse
    </div>

    <!-- Comment Form -->
    @include('admin.tickets.partials.comment-form', ['ticket' => $ticket])
</div>