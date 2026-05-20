<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->user->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex gap-2">
                <span class="px-2 py-0.5 text-xs font-medium rounded-full" style="background-color: {{ $ticket->priority_color }}; color: #fff;">
                    {{ $ticket->priority_label }}
                </span>
                <span class="px-2 py-0.5 text-xs font-medium rounded-full" style="background-color: {{ $ticket->status_color }}; color: #fff;">
                    {{ $ticket->status_label }}
                </span>
            </div>
        </div>
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $ticket->description }}</p>
        </div>
        
        @if($ticket->attachments->count() > 0)
            @include('agent.tickets.partials.attachments', ['attachments' => $ticket->attachments])
        @endif
    </div>
</div>