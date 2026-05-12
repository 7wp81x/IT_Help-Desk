@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ticket #{{ $ticket->id }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $ticket->subject }}</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <button onclick="changeStatus({{ $ticket->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Change Status
            </button>
            <button onclick="assignAgent({{ $ticket->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Assign Agent
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Ticket -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center">
                            <i class="bi bi-person-fill text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                    </div>
                </div>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300">{{ $ticket->description }}</p>
                </div>
                
                @if($ticket->attachment)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="#" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-2">
                            <i class="bi bi-paperclip"></i> View Attachment
                        </a>
                    </div>
                @endif
            </div>

            <!-- Comments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Conversation</h3>
                
                @foreach($ticket->comments as $comment)
                    <div class="mb-4 last:mb-0">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-person text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('M d, Y h:i A') }}</span>
                                        @if($comment->is_internal)
                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Internal</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                    @if($comment->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach($comment->attachments as $attachment)
                                                <a href="{{ route('admin.tickets.download', $attachment) }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1">
                                                    <i class="bi bi-paperclip"></i> {{ $attachment->original_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Comment Form -->
                <div class="mt-6">
                    <form action="{{ route('admin.tickets.comment', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add Comment</label>
                        <textarea name="message" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500" placeholder="Type your response here..."></textarea>
                        <div class="mt-3 flex items-center">
                            <input type="checkbox" name="is_internal" id="is_internal" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_internal" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Internal note (not visible to user)</label>
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments</label>
                            <input type="file" name="attachments[]" multiple class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        </div>
                        <div class="flex justify-end mt-3">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Send Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ticket Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Ticket Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block">Status</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block">Priority</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ ucfirst($ticket->priority) }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block">Category</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $ticket->category->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block">Assigned Agent</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $ticket->assignedAgent->name ?? 'Unassigned' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block">Created</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block">Last Updated</label>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <button class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="bi bi-tag mr-2"></i> Change Priority
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="bi bi-folder mr-2"></i> Change Category
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="bi bi-envelope-paper mr-2"></i> Send Email
                    </button>
                    <hr class="border-gray-200 dark:border-gray-700 my-2">
                    <button class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                        <i class="bi bi-archive mr-2"></i> Close Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeStatus(ticketId) {
    alert('Change status for ticket #' + ticketId);
}

function assignAgent(ticketId) {
    alert('Assign agent for ticket #' + ticketId);
}
</script>
@endpush

@endsection