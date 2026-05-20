<div class="space-y-6">
    <!-- Ticket Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Ticket Details</h3>
        </div>
        <div class="p-5 space-y-3">
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full" style="background-color: {{ $ticket->status_color }}; color: #fff;">
                        {{ $ticket->status_label }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Priority</dt>
                <dd class="mt-1">
                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full" style="background-color: {{ $ticket->priority_color }}; color: #fff;">
                        {{ $ticket->priority_label }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Department</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->department->name ?? 'Unassigned' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Category</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->category->name ?? 'Pending classification' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Assigned To</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Created</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->created_at->format('M d, Y h:i A') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->updated_at->diffForHumans() }}</dd>
            </div>
            @if($ticket->resolved_at)
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Resolved</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->resolved_at->format('M d, Y h:i A') }}</dd>
            </div>
            @endif
            @if($ticket->closed_at)
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Closed</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->closed_at->format('M d, Y h:i A') }}</dd>
            </div>
            @endif
        </div>
    </div>
</div>