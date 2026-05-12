<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-4 md:px-6 py-4 md:py-5 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="bi bi-clock-history text-blue-500 text-xl"></i>
                Recent Tickets
            </h3>
            <a href="{{ route('admin.tickets.all') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                View All Tickets →
            </a>
        </div>
    </div>
    
    @if($recentTickets->count() > 0)
    <div class="overflow-x-auto max-h-80 overflow-y-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                <tr class="text-left">
                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ticket #</th>
                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                \)

            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($recentTickets as $ticket)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                        <span class="text-sm font-mono font-medium text-gray-900 dark:text-white">#{{ $ticket->ticket_number ?? $ticket->id }}</span>
                    </td>
                    <td class="px-4 md:px-6 py-3">
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline line-clamp-1">
                            {{ $ticket->subject }}
                        </a>
                    </td>
                    <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-medium">
                                {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->user->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full font-medium 
                            @if($ticket->status == 'open') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                            @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                            @elseif($ticket->status == 'resolved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $ticket->created_at->format('M d, Y') }}
                    </td>
                \)

                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="py-12 text-center">
        <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
            <i class="bi bi-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
        </div>
        <p class="text-gray-500 dark:text-gray-400">No tickets found yet.</p>
    </div>
    @endif
</div>