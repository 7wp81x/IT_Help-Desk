@forelse($tickets as $ticket)
<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
    <td class="px-6 py-4">
        <input type="checkbox" class="ticket-checkbox rounded border-gray-300" value="{{ $ticket->id }}">
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm font-mono text-gray-900 dark:text-white">#{{ $ticket->id }}</span>
    </td>
    <td class="px-6 py-4">
        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
            {{ Str::limit($ticket->subject, 50) }}
        </a>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center">
                <i class="bi bi-person-fill text-white text-xs"></i>
            </div>
            <span class="text-sm text-gray-900 dark:text-white">{{ $ticket->user->name }}</span>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($ticket->status == 'open')
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                Open
            </span>
        @elseif($ticket->status == 'in_progress')
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                In Progress
            </span>
        @elseif($ticket->status == 'resolved')
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                Resolved
            </span>
        @else
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                Closed
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($ticket->priority == 'low')
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                Low
            </span>
        @elseif($ticket->priority == 'medium')
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                Medium
            </span>
        @elseif($ticket->priority == 'high')
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                High
            </span>
        @else
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                Urgent
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->category->name ?? 'N/A' }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($ticket->assignedAgent)
            <span class="text-sm text-gray-900 dark:text-white">{{ $ticket->assignedAgent->name }}</span>
        @else
            <span class="text-sm text-gray-500 dark:text-gray-400 italic">Unassigned</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->created_at->format('M d, Y') }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex gap-2">
            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                <i class="bi bi-eye"></i>
            </a>
            <button onclick="assignTicket({{ $ticket->id }})" class="text-green-600 hover:text-green-800" title="Assign">
                <i class="bi bi-person-plus"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="10" class="px-6 py-12 text-center">
        <div class="flex flex-col items-center gap-3">
            <i class="bi bi-ticket-detailed text-5xl text-gray-400"></i>
            <p class="text-gray-500 dark:text-gray-400">No tickets found</p>
            <a href="{{ route('admin.tickets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="bi bi-plus-circle"></i>
                Create First Ticket
            </a>
        </div>
    </td>
</tr>
@endforelse