@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('agent.tickets.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->ticket_number }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $ticket->subject }}</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <form action="{{ route('agent.tickets.update-status', $ticket) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
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
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ticket->priority_color }}">
                            {{ $ticket->priority_label }}
                        </span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ticket->status_color }}">
                            {{ $ticket->status_label }}
                        </span>
                    </div>
                </div>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300">{{ $ticket->description }}</p>
                </div>
                
                @if($ticket->attachments->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments:</p>
                        @foreach($ticket->attachments as $attachment)
                            <a href="{{ route('agent.tickets.download', $attachment) }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-2 mr-4">
                                <i class="bi bi-paperclip"></i> {{ $attachment->original_name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Comments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Conversation</h3>
                
                @forelse($ticket->comments as $comment)
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
                                                <a href="{{ route('agent.tickets.download', $attachment) }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1">
                                                    <i class="bi bi-paperclip"></i> {{ $attachment->original_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No comments yet.</p>
                @endforelse

                <!-- Comment Form -->
                <div class="mt-6">
                    <form action="{{ route('agent.tickets.comment', $ticket) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add Comment</label>
                        <textarea name="content" rows="4" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500" placeholder="Type your comment here..."></textarea>
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
            <!-- Ticket Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ticket Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ticket->status_color }}">
                                {{ $ticket->status_label }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Priority</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ticket->priority_color }}">
                                {{ $ticket->priority_label }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Assigned To</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @if($ticket->resolved_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Resolved</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->resolved_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @endif
                    @if($ticket->closed_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Closed</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->closed_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection