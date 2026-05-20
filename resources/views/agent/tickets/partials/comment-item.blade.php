<div class="flex items-start gap-3" data-comment-id="{{ $comment->id }}" data-message-anchor="{{ $comment->message_anchor ?? '' }}">
    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
        @if($comment->user->avatar_url)
            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover" title="{{ $comment->user->name }}">
        @else
            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                {{ substr($comment->user->name, 0, 1) }}
            </span>
        @endif
    </div>
    <div class="flex-1 relative">
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 @if($comment->trashed()) deleted-message @endif group">
            <div class="flex justify-between items-center mb-1">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                    @if($comment->user->role === 'agent')
                        <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-0.5 rounded">Agent</span>
                    @elseif($comment->user->role === 'admin')
                        <span class="text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 px-2 py-0.5 rounded">Admin</span>
                    @elseif($comment->user->role === 'user')
                        <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-0.5 rounded">Customer</span>
                    @endif
                    @if($comment->is_internal ?? false)
                        <span class="text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-0.5 rounded">Internal</span>
                    @endif
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('M d, h:i A') }}</span>
            </div>
            <p data-content class="text-sm text-gray-700 dark:text-gray-300 @if($comment->trashed()) italic text-gray-400 @endif">
                @if($comment->trashed())
                    This message was deleted
                @else
                    {{ $comment->content }}
                @endif
            </p>
            
            {{-- Display attachments below comment text --}}
            @if($comment->attachments && $comment->attachments->count() > 0 && !$comment->trashed())
                @include('agent.tickets.partials.comment-attachments', ['attachments' => $comment->attachments])
            @endif

            <!-- Delete Button - Only for user's own messages, Icon only -->
                @if(Auth::user()->id === $comment->user_id && !$comment->trashed())
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                        <button onclick="deleteComment({{ $comment->id }}, this)" 
                                class="p-1.5 bg-red-500 hover:bg-red-600 text-white rounded transition-colors"
                                title="Delete message">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endif
        </div>
    </div>
</div>