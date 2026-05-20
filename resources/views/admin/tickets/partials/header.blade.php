<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.tickets.all') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
            ← Back to Tickets
        </a>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->ticket_number }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $ticket->subject }}</p>
            @if($ticket->status === 'canceled')
                <div class="mt-3 rounded-lg bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                    <i class="bi bi-info-circle mr-1"></i>
                    This ticket has been canceled. No further actions can be taken.
                </div>
            @endif
        </div>
        
        <div class="flex gap-2 items-center">
            @if($ticket->status !== 'canceled')
                <button onclick="changeStatus()" 
                        class="px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                    Change Status
                </button>
                <button onclick="assignAgent()" 
                        class="px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded-md transition">
                    Assign Agent
                </button>
                <form action="{{ route('admin.tickets.cancel', $ticket->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to cancel this ticket? This action cannot be undone and will notify the requester.')"
                            class="px-3 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                        Cancel Ticket
                    </button>
                </form>
            @else
               
                    <span class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md">
                        Canceled
                    </span>
            @endif
        </div>
    </div>
</div>